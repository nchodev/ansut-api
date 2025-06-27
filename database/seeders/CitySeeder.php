<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $villes = [
            'Abidjan',
            'Yamoussoukro',
            'Bouaké',
            'San Pedro',
            'Daloa',
            'Korhogo',
            'Man',
            'Gagnoa',
            'Abengourou',
            'Bondoukou',
        ];

        foreach ($villes as $ville) {
            City::create(['name' => $ville]);
        }
    }
}
