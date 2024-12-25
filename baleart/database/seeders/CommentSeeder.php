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
            $comment = Comment::create([
                "comment" => $comentari["text"],
                "score" => $comentari["puntuacio"],
                "status" => ["N", "Y"][array_rand(["N", "Y"])],//Selecciona aleatoriamente entre "N" y "Y"
                "space_id" => Space::where("regNumber", $comentari["espai"])->value("id"), //Obtiene el id del espacio buscando con el número de registro del json el espacio creado con el mismo número de registro.
                "user_id" => User::where("email", $comentari["usuari"])->value("id") //Obtiene el id del usuario buscando con el email del json el usuario creado con el mismo email.
            ]);
            //Hago el cálculo del totalScore y countScore aquí en lugar de en Space porque este seeder se ejecuta necesariamente después del seeder de Space.
            if ($comment->status === 'Y') { //Comprueba que el nuevo comentario insertado tiene status "Y" (Aceptado por un administrador) para sumar la puntuación al espacio
                $space = $comment->space; //Obtiene el espacio asociado al comentario
                $scores = $space->calculateScores(); //Calcula los scores del espacio
                $space->totalScore = $scores['totalScore']; //Asigna el totalScore calculado al totalScore del espacio
                $space->countScore = $scores['countScore']; //Asigna el countScore calculado al countScore del espacio
                $space->save(); //Guarda los cambios en el espacio
            }
        }
    }
}
