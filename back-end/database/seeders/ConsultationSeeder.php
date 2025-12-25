<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Consultation;
use App\Models\RendezVous;
use Carbon\Carbon;

class ConsultationSeeder extends Seeder
{
    public function run(): void
    {
        $rdv = RendezVous::first();

        if (!$rdv) {
            return;
        }

        Consultation::firstOrCreate(
            ['rendez_vous_id' => $rdv->id],
            [
                'patient_id'  => $rdv->patient_id,
                'medecin_id'  => $rdv->medecin_id,
                'date'        => $rdv->date_heure->toDateString(),
                'heure'       => $rdv->date_heure->format('H:i'),
                'motif'       => 'Consultation de contrôle',
                'diagnostic'  => 'Rien d\'inquiétant',
                'notes'       => 'Suivi dans 3 mois conseillé.',
            ]
        );

        // marquer le RDV comme terminé
        $rdv->update(['statut' => 'termine']);
    }
}
