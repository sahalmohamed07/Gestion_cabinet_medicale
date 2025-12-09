<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    use HasFactory;
    protected $table = 'rendez_vous';
    protected $fillable = [

        'medecin_id',
        'patient_id',
        'statut',
        'date_heure',
        'notes'
    ];

    protected $casts = [
        'date_heure' => 'datetime',
    ];


    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
