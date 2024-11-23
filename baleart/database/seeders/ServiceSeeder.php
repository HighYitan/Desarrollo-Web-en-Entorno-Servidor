<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = file_get_contents("C:\\temp\\baleart\\serveis.json"); //Obtiene el contenido del archivo JSON
        $serveis = json_decode($jsonData, true); //Convierte el JSON en un array

        foreach($serveis["serveis"]["servei"] as $servei){ //Recorre cada servei
            Service::create([
                "name" => $servei["cat"],
                "description_CA" => $servei["cat"],
                "description_ES" => $servei["esp"],
                "description_EN" => $servei["eng"]
            ]);
        }
    }
}
