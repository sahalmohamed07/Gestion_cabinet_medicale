<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Consultation;
use App\Models\RendezVous;
use App\Models\Patient;
use App\Models\Medecin;
//use Carbon\Carbon;

class ConsultationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user(); // utilisateur connecté via Sanctum

        // Base query avec les relations
        $query = Consultation::with(['patient', 'medecin', 'rendezVous']);

        // 🔐 Filtrage selon le rôle
        if ($user->role === 'patient') {
            // On récupère le patient lié à ce user
            $patient = Patient::where('user_id', $user->id)->first();

            if (!$patient) {
                return response()->json([
                    'message' => 'Aucun profil patient associé à cet utilisateur.'
                ], 404);
            }

            $query->where('patient_id', $patient->id);

            // ⚠️ Un patient ne doit PAS pouvoir voir les consultations d’un autre patient
            // donc on ignore patient_id et medecin_id dans la requête
            if ($request->filled('date')) {
                $query->whereDate('date', $request->date);
            }
        } elseif ($user->role === 'medecin') {
            // On récupère le médecin lié à ce user
            $medecin = Medecin::where('user_id', $user->id)->first();

            if (!$medecin) {
                return response()->json([
                    'message' => 'Aucun profil médecin associé à cet utilisateur.'
                ], 404);
            }

            $query->where('medecin_id', $medecin->id);

            // Optionnel : le médecin peut filtrer par date
            if ($request->filled('date')) {
                $query->whereDate('date', $request->date);
            }
        } else {
            // 🧑‍💼 Admin (ou autre rôle avec droits) : il peut tout filtrer librement
            if ($request->filled('patient_id')) {
                $query->where('patient_id', $request->patient_id);
            }

            if ($request->filled('medecin_id')) {
                $query->where('medecin_id', $request->medecin_id);
            }

            if ($request->filled('date')) {
                $query->whereDate('date', $request->date);
            }
        }

        return response()->json($query->paginate(10));
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        // 🔐 Option : seuls les médecins / admins peuvent créer une consultation
        if (!in_array($user->role, ['medecin', 'admin'])) {
            return response()->json([
                'message' => 'Vous n’êtes pas autorisé à créer une consultation.'
            ], 403);
        }

        $data = $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'medecin_id'     => 'required|exists:medecins,id',
            'rendez_vous_id' => 'nullable|exists:rendez_vous,id',
            'date'           => 'required|date',
            'heure'          => 'nullable',
            'motif'          => 'nullable|string',
            'diagnostic'     => 'nullable|string',
            'notes'          => 'nullable|string',
        ]);

        // Si consultation liée à un RDV -> cohérence obligatoire
        if (!empty($data['rendez_vous_id'])) {
            $rdv = RendezVous::find($data['rendez_vous_id']);

            if ($rdv->patient_id != $data['patient_id'] || $rdv->medecin_id != $data['medecin_id']) {
                return response()->json(['message' => 'Consultation incohérente avec le RDV.'], 422);
            }

            if (Consultation::where('rendez_vous_id', $data['rendez_vous_id'])->exists()) {
                return response()->json(['message' => 'Consultation déjà existante pour ce RDV.'], 422);
            }

            // Si aucune date/heure fournie → on récupère depuis le rendez-vous
            $data['date']  = $data['date']  ?? $rdv->date_heure->toDateString();
            $data['heure'] = $data['heure'] ?? $rdv->date_heure->format('H:i');
        }

        $consultation = Consultation::create($data);

        if (!empty($data['rendez_vous_id'])) {
            $rdv->update(['statut' => 'termine']);
        }

        return response()->json($consultation->load(['patient', 'medecin']), 201);
    }

    public function show(Request $request, Consultation $consultation): JsonResponse
    {
        $user = $request->user();

        // 🔐 Vérifier que l’utilisateur a le droit de voir CETTE consultation
        if ($user->role === 'patient') {
            $patient = Patient::where('user_id', $user->id)->first();

            if (!$patient || $consultation->patient_id !== $patient->id) {
                return response()->json(['message' => 'Accès interdit à cette consultation.'], 403);
            }
        }

        if ($user->role === 'medecin') {
            $medecin = Medecin::where('user_id', $user->id)->first();

            if (!$medecin || $consultation->medecin_id !== $medecin->id) {
                return response()->json(['message' => 'Accès interdit à cette consultation.'], 403);
            }
        }

        // Admin : accès libre
        return response()->json($consultation->load(['patient', 'medecin', 'rendezVous']));
    }

    public function update(Request $request, Consultation $consultation): JsonResponse
    {
        $user = $request->user();

        // 🔐 Seul le médecin lié ou admin peut modifier
        if ($user->role === 'medecin') {
            $medecin = Medecin::where('user_id', $user->id)->first();

            if (!$medecin || $consultation->medecin_id !== $medecin->id) {
                return response()->json(['message' => 'Vous ne pouvez pas modifier cette consultation.'], 403);
            }
        } elseif ($user->role === 'patient') {
            return response()->json(['message' => 'Un patient ne peut pas modifier une consultation.'], 403);
        }

        $data = $request->validate([
            'motif'      => 'nullable|string',
            'diagnostic' => 'nullable|string',
            'notes'      => 'nullable|string',
            'date'       => 'sometimes|date',
            'heure'      => 'sometimes'
        ]);

        $consultation->update($data);

        return response()->json($consultation->load(['patient', 'medecin']));
    }

    public function destroy(Request $request, Consultation $consultation): JsonResponse
    {
        $user = $request->user();

        // 🔐 Seul un admin (ou éventuellement médecin) peut supprimer
        if (!in_array($user->role, ['admin'])) {
            return response()->json(['message' => 'Vous n’êtes pas autorisé à supprimer cette consultation.'], 403);
        }

        $consultation->delete();
        return response()->json(null, 204);
    }
}
