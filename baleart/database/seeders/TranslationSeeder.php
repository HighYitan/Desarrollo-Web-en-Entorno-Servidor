<?php

namespace Database\Seeders;

use App\Models\Translation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = file_get_contents("C:\\temp\\baleart\\traduccions.json"); //Obtiene el contenido del archivo JSON
        $traduccions = json_decode($jsonData, true); //Convierte el JSON en un array

        foreach($traduccions["traduccions"]["terme"] as $terme){ //Recorre cada traducción
            Translation::create([
                "description_CA" => $terme["cat"],
                "description_ES" => $terme["esp"],
                "description_EN" => $terme["eng"]
            ]);
        }
    }
}
