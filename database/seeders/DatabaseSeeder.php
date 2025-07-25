<?php

namespace Database\Seeders;

use App\Models\Symptom;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

         $this->call([
              CitySeeder::class,
              SchoolSeeder::class,
              GradeSeeder::class,
              MotherTongueSeeder::class,
              SocialStatutSeeder::class,
              SymptomSeeder::class,
              AdvisorSeeder::class,
            ]);
        

    }
}
