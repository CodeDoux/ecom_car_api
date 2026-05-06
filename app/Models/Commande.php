<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'montant_total', 'status'];
 
    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
    public function ligne_commandes()
    {
        return $this->hasMany(LigneCommande::class);
    }
}
