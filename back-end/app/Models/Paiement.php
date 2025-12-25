<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;
    protected $fillable = [
        'consultation_id',
        'montant',
        'mode_reglement',
        'statut',
        'date_paiement'
    ];

    protected $casts = [
        'date_paiement' => 'datetime'
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
