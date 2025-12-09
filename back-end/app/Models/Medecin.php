<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medecin extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'nom',
        'prenom',
        'email',
        'telephone',
        'specialite_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }

    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }
}
