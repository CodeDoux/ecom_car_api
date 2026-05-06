<?php

namespace App\Services;

use App\Models\Categorie;

class CategorieService
{
   public function getAllCategories()
    {
        return Categorie::latest()->get();
    }

    public function getCategorieById($id)
    {
        return Categorie::findOrFail($id);
    }

    public function createCategorie(array $data)
    {
        return Categorie::create($data);
    }

    public function updateCategorie(Categorie $Categorie, array $data)
    {
        $Categorie->update($data);
        return $Categorie;
    }

    public function deleteCategorie(Categorie $Categorie)
    {
        return $Categorie->delete();
    }
}