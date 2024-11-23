<?php

namespace Database\Seeders;

use App\Models\Zone;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = file_get_contents("C:\\temp\\baleart\\zones.json"); //Obtiene el contenido del archivo JSON
        $zones = json_decode($jsonData, true); //Convierte el JSON en un array

        foreach($zones["zones"]["zona"] as $zona){ //Recorre cada zona
            Zone::create([
                "name" => $zona["Nom"]
            ]);
        }
    }
}
