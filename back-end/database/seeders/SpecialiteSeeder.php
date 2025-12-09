<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Specialite;

class SpecialiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialites = [
            ['nom' => 'Cardiologie',   'description' => 'Spécialité des maladies du cœur'],
            ['nom' => 'Pédiatrie',     'description' => 'Médecine des enfants'],
            ['nom' => 'Gynécologie',   'description' => 'Santé de la femme'],
            ['nom' => 'Dermatologie',  'description' => 'Peau et annexes'],
            ['nom' => 'Neurologie',    'description' => 'Système nerveux'],
        ];

        foreach ($specialites as $spec) {
            Specialite::firstOrCreate(['nom' => $spec['nom']], $spec);
        }
    }
}
