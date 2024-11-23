<?php

namespace Database\Seeders;

use App\Models\Island;
use App\Models\Municipality;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MunicipalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = file_get_contents("C:\\temp\\baleart\\municipis.json"); //Obtiene el contenido del archivo JSON
        $municipis = json_decode($jsonData, true); //Convierte el JSON en un array

        foreach($municipis["municipis"]["municipi"] as $municipi){ //Recorre cada municipio
            Municipality::create([
                "name" => $municipi["Nom"],
                "island_id" => Island::where("name", $municipi["Illa"])->value("id")
            ]);
        }
    }
}
