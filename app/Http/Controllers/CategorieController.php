<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use App\Services\CategorieService;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    protected $categorieService;

    public function __construct(CategorieService $categorieService)
    {
        $this->categorieService = $categorieService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         return response()->json(
            $this->categorieService->getAllCategories()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $categorie = $this->categorieService->createCategorie($data);

        return response()->json($categorie, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $categorie = $this->categorieService->getcategorieById($id);
        return response()->json($categorie);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, categorie $categorie)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $categorie = $this->categorieService->updatecategorie($categorie, $data);

        return response()->json($categorie);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(categorie $categorie)
    {
        $this->categorieService->deletecategorie($categorie);

        return response()->json([
            'message' => 'categorie deleted successfully'
        ]);
    }
}
