<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(AdminUserSeeder::class);
        $this->call(SpecialiteSeeder::class);
        $this->call(MedecinSeeder::class);
        $this->call(PatientSeeder::class);
        $this->call(RendezVousSeeder::class);
        $this->call(ConsultationSeeder::class);
        $this->call(OrdonnanceSeeder::class);
        $this->call(PaiementSeeder::class);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
