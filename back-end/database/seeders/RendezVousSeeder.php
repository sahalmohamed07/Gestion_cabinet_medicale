<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RendezVous;
use App\Models\Patient;
use App\Models\Medecin;
use Carbon\Carbon;

class RendezVousSeeder extends Seeder
{
    public function run(): void
    {
        $patient1 = Patient::first();
        $medecin1 = Medecin::first();

        if (!$patient1 || !$medecin1) {
            return;
        }

        // RDV futur 1
        RendezVous::firstOrCreate(
            [
                'patient_id' => $patient1->id,
                'medecin_id' => $medecin1->id,
                'date_heure' => Carbon::now()->addDays(1)->setTime(10, 30),
            ],
            [
                'statut' => 'en_attente',
                'notes'  => 'Contrôle général',
            ]
        );

        // RDV futur 2
        RendezVous::firstOrCreate(
            [
                'patient_id' => $patient1->id,
                'medecin_id' => $medecin1->id,
                'date_heure' => Carbon::now()->addDays(2)->setTime(14, 00),
            ],
            [
                'statut' => 'en_attente',
                'notes'  => 'Suivi',
            ]
        );
    }
}
