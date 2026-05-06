<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // GET /api/users
    public function index()
    {
        try {
            $users = User::withCount('commandes')->latest()->get()->map(function ($user) {
                return [
                    'id'               => $user->id,
                    'nomComplet'        => $user->nomComplet,
                    'email'            => $user->email,
                    'phone'            => $user->phone,
                    'role'             => $user->role,
                    'created_at'       => $user->created_at,
                    'commandes_count'  => $user->commandes_count,
                ];
            });

            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // GET /api/users/{id}
    public function show(User $user)
    {
        try {
            return response()->json([
                'id'         => $user->id,
                'nomComplet'   => $user->nomComplet,
                'email'      => $user->email,
                'phone'      => $user->phone,
                'role'       => $user->role,
                'created_at' => $user->created_at,
                'commandes'  => $user->commandes()->with('ligne_commandes.car')->latest()->get(),
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // PUT /api/users/{id}/role
    public function updateRole(Request $request, User $user)
    {
        try {
            $request->validate([
                'role' => 'required|in:admin,user'
            ]);

            $user->update(['role' => $request->role]);

            return response()->json([
                'id'        => $user->id,
                'firstName' => $user->first_name,
                'role'      => $user->role,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Rôle invalide.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // DELETE /api/users/{id}
    public function destroy(User $user)
    {
        try {
            if ($user->role === 'admin') {
                return response()->json(['message' => 'Impossible de supprimer un administrateur.'], 403);
            }

            $user->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}