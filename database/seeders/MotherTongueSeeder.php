<?php

namespace Database\Seeders;

use App\Models\MotherTongue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MotherTongueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $langues = [
            'Baoulé',
            'Bété',
            'Dioula',
            'Gouro',
            'Malinké',
            'Attié',
            'Agni',
            'Abbey',
            'Koulango',
            'Sénoufo',
            'Guéré'
        ];

        foreach ($langues as $langue) {
            MotherTongue::create(['name' => $langue]);
        }
    }
}
