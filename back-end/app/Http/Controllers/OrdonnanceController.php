<?php

namespace App\Http\Controllers;

use App\Models\Ordonnance;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrdonnanceController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Ordonnance::with('consultation.patient', 'consultation.medecin')->paginate(10)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'contenu'         => 'required|string'
        ]);

        // blocage : une seule ordonnance par consultation
        if (Ordonnance::where('consultation_id', $data['consultation_id'])->exists()) {
            return response()->json(['message' => 'Ordonnance déjà existante pour cette consultation.'], 422);
        }

        $ordonnance = Ordonnance::create($data);

        return response()->json($ordonnance->load('consultation.patient', 'consultation.medecin'), 201);
    }

    public function show(Ordonnance $ordonnance): JsonResponse
    {
        return response()->json($ordonnance->load('consultation.patient', 'consultation.medecin'));
    }

    public function update(Request $request, Ordonnance $ordonnance): JsonResponse
    {
        $data = $request->validate(['contenu' => 'required|string']);
        $ordonnance->update($data);
        return response()->json($ordonnance);
    }

    public function destroy(Ordonnance $ordonnance): JsonResponse
    {
        $ordonnance->delete();
        return response()->json(null, 204);
    }
}
