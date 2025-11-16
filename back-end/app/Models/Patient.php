<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'date_naissance',
        'sexe',
        'telephone',
        'adresse',
        'email',
        'historique_medical'
    ];

    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }
}
