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
                /*
                    Status es default("N") en la migración lo que significa que el comentario por defecto no ha sido aceptado por un administrador
                    y por tanto no hace falta añadirlo aquí ya que el valor seguirá siendo "N" de No aceptado hasta que el administrador lo acepte
                    entonces saldrá "Y" en la base de datos.
                */
                "space_id" => Space::where("regNumber", $comentari["espai"])->value("id"),
                "user_id" => User::where("email", $comentari["usuari"])->value("id")
            ]);
        }
    }
}
