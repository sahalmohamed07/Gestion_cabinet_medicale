<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class PaiementController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Paiement::with('consultation.patient', 'consultation.medecin')->paginate(10));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'montant'         => 'required|numeric|min:0.01',
            'mode_reglement'  => 'nullable|string',
            'statut'          => 'required|string|in:en_attente,paye,annule',
            'date_paiement'   => 'nullable|date'
        ]);

        if ($data['statut'] == 'paye') {
            if (empty($data['date_paiement'])) {
                $data['date_paiement'] = Carbon::now();
            }
        }

        $paiement = Paiement::create($data);

        return response()->json($paiement->load('consultation.patient', 'consultation.medecin'), 201);
    }

    public function show(Paiement $paiement): JsonResponse
    {
        return response()->json($paiement->load('consultation.patient', 'consultation.medecin'));
    }

    public function update(Request $request, Paiement $paiement): JsonResponse
    {
        $data = $request->validate([
            'montant'         => 'sometimes|numeric|min:0.01',
            'mode_reglement'  => 'nullable|string',
            'statut'          => 'sometimes|string|in:en_attente,paye,annule'
        ]);

        if (isset($data['statut']) && $data['statut'] == 'paye') {
            $data['date_paiement'] = Carbon::now();
        }

        $paiement->update($data);

        return response()->json($paiement);
    }

    public function destroy(Paiement $paiement): JsonResponse
    {
        $paiement->delete();
        return response()->json(null, 204);
    }
}
