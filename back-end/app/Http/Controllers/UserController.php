<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller


// 🧾 Liste des utilisateurs (admin only)
{
    public function index(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        return response()->json(User::all());
    }

    // 👁️ Voir un utilisateur (admin ou lui-même)
    public function show(Request $request, User $user)
    {
        $authUser = $request->user();

        if (!$authUser->isAdmin() && $authUser->id !== $user->id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        return response()->json($user);
    }

    // ✏️ Modifier un utilisateur (admin ou lui-même)
    public function update(Request $request, User $user)
    {
        $authUser = $request->user();

        // seul admin peut modifier n'importe qui
        // sinon, user ne peut modifier que lui-même
        if (!$authUser->isAdmin() && $authUser->id !== $user->id) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $data = $request->validate([
            'nom'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:6',
            'role'     => 'sometimes|string|in:admin,medecin,patient',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // si c’est pas un admin, il ne peut pas modifier son role
        if (!$authUser->isAdmin()) {
            unset($data['role']);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Utilisateur mis à jour avec succès',
            'user'    => $user,
        ], 200);
    }

    // 🗑️ Supprimer un utilisateur (admin only)
    public function destroy(Request $request, User $user)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé'], 200);
    }
}
