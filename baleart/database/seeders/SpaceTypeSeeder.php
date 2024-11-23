<?php

namespace Database\Seeders;

use App\Models\SpaceType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SpaceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = file_get_contents("C:\\temp\\baleart\\tipus.json"); //Obtiene el contenido del archivo JSON
        $tipusEspais = json_decode($jsonData, true); //Convierte el JSON en un array

        foreach($tipusEspais["tipusespais"]["tipus"] as $tipus){ //Recorre cada tipus d'espai
            SpaceType::create([
                "name" => $tipus["cat"],
                "description_CA" => $tipus["cat"],
                "description_ES" => $tipus["esp"],
                "description_EN" => $tipus["eng"]
            ]);
        }
    }
}
