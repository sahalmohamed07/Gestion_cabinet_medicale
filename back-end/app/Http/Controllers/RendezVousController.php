<?php

namespace App\Http\Controllers;

use App\Models\RendezVous;

use Illuminate\Http\Request;

class RendezVousController extends Controller
{
    public function index()
    {
        $rendezVous = RendezVous::with([
            'medecin.user',
            'patient.user'
        ])->get();

        return response()->json($rendezVous);
    }

    public function show(RendezVous $rendezVous)
    {
        $rendezVous->load(['medecin.user', 'patient.user']);

        return response()->json($rendezVous);
    }
}
