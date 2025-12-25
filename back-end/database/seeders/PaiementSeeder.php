<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paiement;
use App\Models\Consultation;
use Carbon\Carbon;

class PaiementSeeder extends Seeder
{
    public function run(): void
    {
        $consultation = Consultation::first();

        if (!$consultation) {
            return;
        }

        Paiement::firstOrCreate(
            ['consultation_id' => $consultation->id],
            [
                'montant'        => 15000,
                'mode_reglement' => 'cash',
                'statut'         => 'paye',
                'date_paiement'  => Carbon::now(),
            ]
        );
    }
}
