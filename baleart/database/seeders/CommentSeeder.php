<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Space;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonData = file_get_contents("C:\\temp\\baleart\\comentaris.json"); //Obtiene el contenido del archivo JSON
        $comentaris = json_decode($jsonData, true); //Convierte el JSON en un array

        foreach($comentaris["comentaris"]["comentari"] as $comentari){ //Recorre cada comentario
            Comment::create([
                "comment" => $comentari["text"],
                "score" => $comentari["puntuacio"],
                "status" => ["N", "Y"][array_rand(["N", "Y"])],//Selecciona aleatoriamente entre "N" y "Y"
                "space_id" => Space::where("regNumber", $comentari["espai"])->value("id"), //Obtiene el id del espacio buscando con el número de registro del json el espacio creado con el mismo número de registro.
                "user_id" => User::where("email", $comentari["usuari"])->value("id") //Obtiene el id del usuario buscando con el email del json el usuario creado con el mismo email.
            ]);
        }
    }
}
