<?php

namespace Database\Seeders;

use App\Models\SocialStatut;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SocialStatutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $statuts = [
            'Élève au collège',
            'Élève au lycée',
            'Redoublant(e)',
            'Adolescent(e) déscolarisé(e)',
            'Adolescent(e) en formation professionnelle',
            'Apprenti(e)',
            'Adolescent(e) en situation de rue',
            'Mineur(e) non accompagné(e)',
            'Enfant en famille d’accueil',
            'Enfant en situation de handicap',
            'Enfant chef de famille',
            'Enfant travailleur',
            'Orphelin(e)',
            'Membre d’un groupe de jeunes',
            'Enfant déplacé ou réfugié',
            'Enfant bénéficiaire d’un programme social',
        ];

        foreach ($statuts as $statut) {
            SocialStatut::create(['name' => $statut]);
        }
    }
}
