<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = file_get_contents("C:\\temp\\baleart\\rols.json"); //Obtiene el contenido del archivo JSON
        $roles = json_decode($jsonData, true); //Convierte el JSON en un array

        foreach($roles["roles"]["role"] as $role){ //Recorre cada rol
            Role::create([
                "name" => $role["Nom"]
            ]);
        }
    }
}
