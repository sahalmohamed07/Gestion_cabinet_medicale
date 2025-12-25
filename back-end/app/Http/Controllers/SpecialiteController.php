<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialite;

class SpecialiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Specialite::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $spceialte = Specialite::create($data);
        return response()->json($spceialte, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Specialite $specialite)
    {
        return response()->json($specialite);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Specialite $specialite)
    {
        $data = $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $specialite->update($data);
        return response()->json($specialite);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialite $specialite)
    {
        $specialite->delete();
        return response()->json(null, 204);
    }
}
