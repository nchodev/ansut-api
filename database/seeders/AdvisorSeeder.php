<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdvisorSeeder extends Seeder
{
    public function run(): void
    {
        $advisors = [
            [
                'full_name' => 'Mme Aïcha Kné',
                'email' => 'aicha.kone@example.com',
                'phone_number' => '0700000001',
                'specialty' => 'sante_menstruelle',
                'bio' => 'Infirmière spécialisée en santé reproductive des adolescentes.'
            ],
            [
                'full_name' => 'M. Koffi Kouadio',
                'email' => 'koffi.kouadio@example.com',
                'phone_number' => '0700000002',
                'specialty' => 'orientation',
                'bio' => 'Conseiller pédagogique depuis plus de 15 ans.'
            ],
            [
                'full_name' => 'Mme Fatou Diarra',
                'email' => 'fatou.diarra@example.com',
                'phone_number' => '0700000003',
                'specialty' => 'education_sexuelle',
                'bio' => 'Travailleuse sociale, animatrice de groupes jeunes sur les thématiques du genre.'
            ],
            [
                'full_name' => 'M. Jean-Baptiste N\'Guessan',
                'email' => 'jb.nguessan@example.com',
                'phone_number' => '0700000004',
                'specialty' => 'psychologique',
                'bio' => 'Psychologue scolaire intervenant dans les établissements secondaires.'
            ],
            [
                'full_name' => 'Mme Clarisse Yao',
                'email' => 'clarisse.yao@example.com',
                'phone_number' => '0700000005',
                'specialty' => 'juridique',
                'bio' => 'Juriste spécialisée dans les droits des filles et des mineurs.'
            ],
        ];

        foreach ($advisors as $advisor) {
            DB::table('advisors')->insert([
                ...$advisor,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
