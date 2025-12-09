<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class RendezVousController extends Controller
{
    /**
     * Liste des rendez-vous (avec filtres + pagination).
     */
    public function index(Request $request): JsonResponse
    {
        $user  = $request->user();
        $query = RendezVous::with(['patient', 'medecin.specialite']);

        // 1) FILTRAGE PAR RÔLE (admin / medecin / patient)
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            // Admin : voit tout, pas de filtre spécifique ici
        } elseif (method_exists($user, 'isMedecin') && $user->isMedecin()) {
            // Médecin : ne voit que ses RDV
            $medecin = $user->medecin; // relation hasOne dans User

            if ($medecin) {
                $query->where('medecin_id', $medecin->id);
            } else {
                // pas de profil médecin associé → aucun RDV
                $query->whereRaw('1 = 0');
            }
        } else {
            // Par défaut : on considère que c'est un patient
            $patient = $user->patient; // relation hasOne dans User

            if ($patient) {
                $query->where('patient_id', $patient->id);
            } else {
                // pas de profil patient associé → aucun RDV
                $query->whereRaw('1 = 0');
            }
        }

        // 2) FILTRES OPTIONNELS (complémentaires)
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('medecin_id')) {
            $query->where('medecin_id', $request->medecin_id);
        }

        if ($request->filled('date')) {
            // on filtre sur la date sans tenir compte de l'heure
            $query->whereDate('date_heure', $request->date);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // 3) Tri + pagination
        $rendezVous = $query
            ->orderBy('date_heure', 'asc')
            ->paginate(10);

        return response()->json($rendezVous);
    }


    /**
     * Création d'un rendez-vous.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medecin_id' => 'required|exists:medecins,id',
            'date_heure' => 'required|date',
            'statut'     => 'nullable|string|in:en_attente,confirme,annule,termine',
            'notes'      => 'nullable|string',
        ]);

        // Règle 1 : pas de rendez-vous dans le passé
        $dateHeure = Carbon::parse($data['date_heure']);
        if ($dateHeure->isPast()) {
            return response()->json([
                'message' => 'Impossible de créer un rendez-vous dans le passé.',
            ], 422);
        }

        // Règle 2 : pas de double booking pour le même médecin
        $collision = RendezVous::where('medecin_id', $data['medecin_id'])
            ->where('date_heure', $data['date_heure'])
            ->exists();

        if ($collision) {
            return response()->json([
                'message' => 'Le médecin a déjà un rendez-vous à cette date et heure.',
            ], 422);
        }

        if (empty($data['statut'])) {
            $data['statut'] = 'en_attente';
        }

        $rdv = RendezVous::create($data);

        return response()->json($rdv->load(['patient', 'medecin.specialite']), 201);
    }

    /**
     * Détails d'un rendez-vous.
     */
    public function show(RendezVous $rendezVou): JsonResponse
    {
        return response()->json($rendezVou->load(['patient', 'medecin.specialite']));
    }

    /**
     * Mise à jour d'un rendez-vous.
     */
    public function update(Request $request, RendezVous $rendezVou): JsonResponse
    {
        $data = $request->validate([
            'patient_id' => 'sometimes|exists:patients,id',
            'medecin_id' => 'sometimes|exists:medecins,id',
            'date_heure' => 'sometimes|date',
            'statut'     => 'sometimes|string|in:en_attente,confirme,annule,termine',
            'notes'      => 'nullable|string',
        ]);

        // On copie l'ancien état pour vérifier si on change medecin/date_heure
        $nouveauMedecinId = $data['medecin_id'] ?? $rendezVou->medecin_id;
        $nouvelleDateHeure = $data['date_heure'] ?? $rendezVou->date_heure;

        // Si date_heure changée → pas dans le passé
        $dateHeure = Carbon::parse($nouvelleDateHeure);
        if ($dateHeure->isPast()) {
            return response()->json([
                'message' => 'Impossible de déplacer un rendez-vous dans le passé.',
            ], 422);
        }

        // Si medecin ou date_heure changent → re-check collision
        $collision = RendezVous::where('medecin_id', $nouveauMedecinId)
            ->where('date_heure', $nouvelleDateHeure)
            ->where('id', '!=', $rendezVou->id)
            ->exists();

        if ($collision) {
            return response()->json([
                'message' => 'Le médecin a déjà un rendez-vous à cette date et heure.',
            ], 422);
        }

        $rendezVou->update($data);

        return response()->json($rendezVou->load(['patient', 'medecin.specialite']));
    }

    /**
     * Suppression d'un rendez-vous.
     */
    public function destroy(RendezVous $rendezVou): JsonResponse
    {
        $rendezVou->delete();

        return response()->json(null, 204);
    }
}
