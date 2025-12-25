<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;

use App\Models\Patient;



class PatientController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Patient::paginate(10));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nom'              => 'required|string|max:255',
            'prenom'           => 'required|string|max:255',
            'date_naissance'   => 'required|date',
            'sexe'             => 'required|in:M,F,Autre',
            'telephone'        => 'nullable|string|max:20',
            'adresse'          => 'nullable|string|max:255',
            'historique_medical' => 'nullable|string'
        ]);

        $patient = Patient::create($data);

        return response()->json($patient, 201);
    }

    public function show(Patient $patient): JsonResponse
    {
        return response()->json($patient);
    }

    public function update(Request $request, Patient $patient): JsonResponse
    {
        $data = $request->validate([
            'nom'              => 'sometimes|string|max:255',
            'prenom'           => 'sometimes|string|max:255',
            'date_naissance'   => 'sometimes|date',
            'sexe'             => 'sometimes|in:M,F,Autre',
            'telephone'        => 'nullable|string|max:20',
            'adresse'          => 'nullable|string|max:255',
            'historique_medical' => 'nullable|string'
        ]);

        $patient->update($data);

        return response()->json($patient);
    }

    public function destroy(Patient $patient): JsonResponse
    {
        $patient->delete();
        return response()->json(null, 204);
    }
}
