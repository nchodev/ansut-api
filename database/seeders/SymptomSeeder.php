<?php

namespace Database\Seeders;

use App\Models\Symptom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SymptomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
      public function run()
    {
        $symptoms = [
            ['label' => 'Cramps', 'emoji' => '😣'],
            ['label' => 'Fatigue', 'emoji' => '🛌'],
            ['label' => 'Maux de tête', 'emoji' => '🤕'],
            ['label' => 'Ballonnements', 'emoji' => '💨'],
            ['label' => 'Irritabilité', 'emoji' => '😤'],
            ['label' => 'Nausées', 'emoji' => '🤢'],
            ['label' => 'Douleurs lombaires', 'emoji' => '💢'],
        ];

        foreach ($symptoms as $symptom) {
            Symptom::create($symptom);
        }
    }
}
