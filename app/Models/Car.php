<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $fillable = ['nom','category_id','marque','model','annee','prix','kilometrage','carburant','transmission','couleur','description','status'];

    //un produit appartient à une catégorie
    public function categorie() {
        return $this->belongsTo(Categorie::class, 'category_id');
    }
     public function images()
    {
        return $this->hasMany(Image::class);
    }

}
