<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    // GET /api/commandes
    public function index()
    {
        try {
            $commandes = Commande::with([
                'user',
                'ligne_commandes.car.images'
            ])->latest()->get();

            return response()->json($commandes);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // GET /api/commandes/{id}
    public function show(Commande $commande)
    {
        try {
            return response()->json(
                $commande->load(['user', 'ligne_commandes.car.images'])
            );
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // PUT /api/commandes/{id}/status
    public function updateStatus(Request $request, Commande $commande)
    {
        try {
            $request->validate([
                'status' => 'required|in:en_cours,confirmee,livree,annulee'
            ]);

            $commande->update(['status' => $request->status]);

            return response()->json($commande);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => 'Statut invalide.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // DELETE /api/commandes/{id}
    public function destroy(Commande $commande)
    {
        try {
            $commande->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}