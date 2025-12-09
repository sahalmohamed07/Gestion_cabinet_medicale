<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use App\Models\User;
use App\Models\Medecin;
use App\Models\Specialite;

use Illuminate\Support\Facades\Hash;



class MedecinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cardio = Specialite::where('nom', 'Cardiologie')->first();
        $pedia  = Specialite::where('nom', 'Pédiatrie')->first();

        // Médecin 1
        $user1 = User::firstOrCreate(
            ['email' => 'medecin1@alfirdaws.test'],
            [
                'nom'      => 'Diallo',
                'prenom'   => 'Mariam',
                'password' => Hash::make('password'),
                'role'     => 'medecin',
            ]
        );

        Medecin::firstOrCreate(
            ['email' => 'medecin1@alfirdaws.test'],
            [
                'user_id'       => $user1->id,
                'nom'           => 'Diallo',
                'prenom'        => 'Mariam',
                'telephone'     => '770000001',
                'specialite_id' => $cardio?->id,
            ]
        );

        // Médecin 2
        $user2 = User::firstOrCreate(
            ['email' => 'medecin2@alfirdaws.test'],
            [
                'nom'      => 'Ben',
                'prenom'   => 'Youssef',
                'password' => Hash::make('password'),
                'role'     => 'medecin',
            ]
        );

        Medecin::firstOrCreate(
            ['email' => 'medecin2@alfirdaws.test'],
            [
                'user_id'       => $user2->id,
                'nom'           => 'Ben',
                'prenom'        => 'Youssef',
                'telephone'     => '770000002',
                'specialite_id' => $pedia?->id,
            ]
        );
    }
}
