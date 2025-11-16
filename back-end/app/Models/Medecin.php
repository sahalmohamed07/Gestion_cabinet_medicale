<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medecin extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'specialite_id'
    ];

    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }

    public function rendezVous()
    {
        return $this->hasMany(Patient::class);
    }
}
