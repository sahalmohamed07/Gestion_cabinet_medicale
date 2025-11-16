<?php

namespace App\Http\Controllers;

use App\Models\Medecin;
use Illuminate\Http\Request;

class MedecinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Medecin::with('specialite')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:medecins,email',
            'telephone' => 'required|string|max:20',
            'specialite_id' => 'required|exists:specialites,id',
        ]);

        $medecin = Medecin::create($data);
        return response()->json($medecin->load('specialite'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Medecin $medecin)
    {
        return response()->json($medecin->load('specialite'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medecin $medecin)
    {
        $data = $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:medecins,email,' . $medecin->id,
            'telephone' => 'sometimes|required|string|max:20',
            'specialite_id' => 'sometimes|required|exists:specialites,id',
        ]);

        $medecin->update($data);
        return response()->json($medecin->load('specialite'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medecin $medecin)

    {
        $medecin->delete();
        return response()->json(null, 204);
    }
}
