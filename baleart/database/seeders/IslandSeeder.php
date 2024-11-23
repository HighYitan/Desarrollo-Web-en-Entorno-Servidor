<?php

namespace Database\Seeders;

use App\Models\Island;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IslandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $illes = [
            "Mallorca",
            "Menorca",
            "Eivissa",
            "Formentera",
            "Cabrera",
            "Dragonera"
        ];
        foreach($illes as $illa){ //Recorre cada illa de l'array
            Island::create([ //Crea una illa
                "name" => $illa
            ]);
        }

    }
}
