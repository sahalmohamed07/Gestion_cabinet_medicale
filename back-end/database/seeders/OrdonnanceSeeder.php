<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ordonnance;
use App\Models\Consultation;

class OrdonnanceSeeder extends Seeder
{
    public function run(): void
    {
        $consultation = Consultation::first();

        if (!$consultation) {
            return;
        }

        Ordonnance::firstOrCreate(
            ['consultation_id' => $consultation->id],
            [
                'contenu' => "Doliprane 500mg : 1 comprimé matin et soir pendant 5 jours.\nBoire beaucoup d'eau.\nRepos conseillé."
            ]
        );
    }
}
