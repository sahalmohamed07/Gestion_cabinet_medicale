<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Identifiants incorrects'], 401);
        }

        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $token = $user->createToken('al_firdaws_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Déconnexion réussie']);
    }

    /**
     * Inscription d'un patient :
     * - crée un user (role = patient)
     * - crée un enregistrement dans la table patients lié à ce user
     */
    public function register(Request $request)
    {
        // 1) Validation des données
        $data = $request->validate([
            'nom'                => 'required|string|max:255',
            'prenom'             => 'required|string|max:255',
            'email'              => 'required|email|unique:users,email',
            'password'           => 'required|string|min:6',
            'date_naissance'     => 'required|date',
            'sexe'               => 'required|string|in:M,F',
            'telephone'          => 'nullable|string|max:20',
            'adresse'            => 'nullable|string|max:255',
            'historique_medical' => 'nullable|string',
        ]);

        // 2) Création du user (role toujours = patient ici)
        $user = User::create([
            'nom'      => $data['nom'],
            'prenom'   => $data['prenom'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'patient',
        ]);

        // 3) Création du patient lié au user
        $patient = Patient::create([
            'user_id'           => $user->id,
            'nom'               => $data['nom'],
            'prenom'            => $data['prenom'],
            'date_naissance'    => $data['date_naissance'],
            'sexe'              => $data['sexe'],
            'telephone'         => $data['telephone'] ?? null,
            'adresse'           => $data['adresse'] ?? null,
            'historique_medical' => $data['historique_medical'] ?? null,
        ]);

        // 4) Token auto (le patient est connecté directement après inscription)
        $token = $user->createToken('al_firdaws_token')->plainTextToken;

        return response()->json([
            'message' => 'Compte patient créé avec succès',
            'user'    => $user,
            'patient' => $patient,
            'token'   => $token,
        ], 201);
    }
}
