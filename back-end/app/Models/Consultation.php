<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'medecin_id',
        'rendez_vous_id',
        'date',
        'heure',
        'motif',
        'diagnostic',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }

    public function rendezVous()
    {
        return $this->belongsTo(RendezVous::class, 'rendez_vous_id');
    }

    public function ordonnance()
    {
        return $this->hasOne(Ordonnance::class);
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }
}
