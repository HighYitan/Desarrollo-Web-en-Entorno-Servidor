<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([ //Crea un usuario administrador
            "name" => "admin",
            "lastName" => "Yitan",
            "email" => "admin@baleart.com",
            //"email_verified_at" => now(), //Atributos de laravel para API
            "phone" => "971123456",
            "password" => Hash::make('12345678'),
            "role_id" => Role::where("name", "administrador")->value("id"),
            //'remember_token' => Str::random(10)
        ]);
        // Des d'un arxiu JSON
        $jsonData = file_get_contents("C:\\temp\\baleart\\usuaris.json"); //Obtiene el contenido del archivo JSON
        $usuaris = json_decode($jsonData, true); //Convierte el JSON en un array

        //Insertar cada registro en la tabla
        foreach($usuaris["usuaris"]["usuari"] as $usuari){ //Recorre cada usuario
            User::create([
                "name"     => $usuari["nom"],
                "lastName" => $usuari["llinatges"],
                "email"    => $usuari["email"],
                //"email_verified_at" => now(),
                "phone"    => $usuari["telefon"],
                "password" => Hash::make($usuari["password"]),
                "role_id"  => Role::where("name", "gestor")->value("id"),
                //"remember_token" => Str::random(10)
            ]);
        }
    }
}
