<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        // Patient 1
        $user1 = User::firstOrCreate(
            ['email' => 'patient1@alfirdaws.test'],
            [
                'nom'      => 'Ahmed',
                'prenom'   => 'Ali',
                'password' => Hash::make('password'),
                'role'     => 'patient',
            ]
        );

        Patient::firstOrCreate(
            ['user_id' => $user1->id],
            [
                'nom'               => 'Ahmed',
                'prenom'            => 'Ali',
                'date_naissance'    => '1995-03-21',
                'sexe'              => 'M',
                'telephone'         => '780000001',
                'adresse'           => 'Dakar',
                'historique_medical' => 'Hypertension légère',
            ]
        );

        // Patient 2
        $user2 = User::firstOrCreate(
            ['email' => 'patient2@alfirdaws.test'],
            [
                'nom'      => 'Fatou',
                'prenom'   => 'Diop',
                'password' => Hash::make('password'),
                'role'     => 'patient',
            ]
        );

        Patient::firstOrCreate(
            ['user_id' => $user2->id],
            [
                'nom'               => 'Fatou',
                'prenom'            => 'Diop',
                'date_naissance'    => '2000-07-10',
                'sexe'              => 'F',
                'telephone'         => '780000002',
                'adresse'           => 'Dakar',
                'historique_medical' => 'Allergies saisonnières',
            ]
        );
    }
}
