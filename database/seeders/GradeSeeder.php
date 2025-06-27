<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $classes = ['6e', '5e', '4e', '3e', '2nde', '1ère', 'Terminale'];

        $ecoles = School::all();

        foreach ($ecoles as $ecole) {
            foreach ($classes as $nomClasse) {
                Grade::create([
                    'name' => $nomClasse,
                    'school_id' => $ecole->id,
                ]);
            }
        }
    }
}
