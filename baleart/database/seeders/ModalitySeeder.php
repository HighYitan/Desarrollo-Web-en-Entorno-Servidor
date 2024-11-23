<?php

namespace Database\Seeders;

use App\Models\Modality;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ModalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = file_get_contents("C:\\temp\\baleart\\modalitats.json"); //Obtiene el contenido del archivo JSON
        $modalitats = json_decode($jsonData, true); //Convierte el JSON en un array

        foreach($modalitats["modalitats"]["modalitat"] as $modalitat){ //Recorre cada modalitat
            Modality::create([
                "name" => $modalitat["cat"],
                "description_CA" => $modalitat["cat"],
                "description_ES" => $modalitat["esp"],
                "description_EN" => $modalitat["eng"]
            ]);
        }
    }
}
