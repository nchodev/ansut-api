<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           $ecolesParVille = [
            'Abidjan' => ['Lycée Classique', 'Collège Notre-Dame', 'Université FHB'],
            'Yamoussoukro' => ['INPHB', 'Lycée Scientifique'],
            'Bouaké' => ['Université A. Ouattara', 'Collège Victor Hugo'],
            'San Pedro' => ['Lycée Moderne', 'Collège Saint-Pierre'],
            'Daloa' => ['Lycée Mamie Adjoua', 'Collège Moderne'],
            'Korhogo' => ['Université Péléforo Gon', 'Lycée Moderne'],
            'Man' => ['Lycée Gbamé', 'Collège la Réussite'],
            'Gagnoa' => ['Lycée Moderne 2', 'Institut Sainte Marie'],
            'Abengourou' => ['Collège Charles Lwanga', 'Lycée Moderne'],
            'Bondoukou' => ['Lycée Moderne', 'Collège Protestant']
        ];

        foreach ($ecolesParVille as $nomVille => $ecoles) {
            $ville = City::where('name', $nomVille)->first();

            foreach ($ecoles as $nomEcole) {
                School::create([
                    'name' => $nomEcole,
                    'city_id' => $ville->id
                ]);
            }
        }
    }
}
