<?php

namespace App\Models;

use App\Models\User;
use App\Models\Image;
use App\Models\Space;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // Atributs que es poden emplenar de manera automàtica: associat al mètode 'Post::create()'
    protected $fillable = [ 
        'comment',
        'score',
        'space_id',
        'user_id',
        'status'
    ];

    // Atributs que no es volen mostrar amb 'response()->json($posts)'
    protected $hidden = [
        'id',
    ];

    // Atributs que no es poden emplenar de manera automàtica
    protected $guarded = [
        'id',
        //"status"
    ];

    // Relacions entre taules:
    public function images()
    {
        return $this->hasMany(Image::class); // 1:N
    }
    public function space()
    {
        return $this->belongsTo(Space::class); // N:1
    }
    public function user()
    {
        return $this->belongsTo(User::class); // N:1
    }

    /*public static function boot()
    {
        parent::boot(); // Se ejecuta el método boot de la clase padre para que sus funciones por defecto se carguen antes de aplicar las personalizadas

        static::created(function ($comment) {
            if ($comment->status === 'Y') { //Comprueba que el nuevo comentario insertado tiene status "Y" (Aceptado por un administrador) para sumar la puntuación al espacio
                $space = $comment->space; //Obtiene el espacio asociado al comentario
                $space->totalScore += $comment->score ?? 0; //Suma la puntuación del comentario al totalScore del espacio, si el score es null suma 0
                //$space->countScore += 1;
                if ($comment->score !== null) { //Si el score del comentario no es null, suma 1 al countScore del espacio
                    $space->countScore += 1;
                }
                $space->save(); //Guarda los cambios en el espacio
            }
        });

        static::updated(function ($comment) {
            $space = $comment->space; //Obtiene el espacio asociado al comentario
            $newScore = $comment->status === 'Y' ? $comment->score ?? 0 : 0; //Comprueba si el comentario tiene status "Y" para sumar la puntuación, si no la tiene suma 0, si score es null suma 0.
            $oldScore = $comment->getOriginal('status') === 'Y' ? $comment->getOriginal('score') ?? 0 : 0; //Comprueba si el comentario tenía status "Y" para restar la puntuación, si no la tenía resta 0, si score era null resta 0.
            $space->totalScore += $newScore - $oldScore; //Suma la diferencia de la puntuación nueva y la antigua al totalScore del espacio (Puede restar si la nueva puntuación es inferior a la vieja)
            //$space->countScore += ($comment->status === 'Y' ? 1 : 0) - ($comment->getOriginal('status') === 'Y' ? 1 : 0);
            $newCount = ($comment->status === 'Y' && $comment->score !== null) ? 1 : 0; //Comprueba si el nuevo comentario tiene status "Y" y score no es null para sumar 1 al countScore del espacio
            $oldCount = ($comment->getOriginal('status') === 'Y' && $comment->getOriginal('score') !== null) ? 1 : 0; //Comprueba si el comentario anterior tenía status "Y" y score no era null para restar 1 al countScore del espacio
            $space->countScore += $newCount - $oldCount; //Suma la diferencia de la cantidad nueva y la antigua al countScore del espacio (Puede restar si el comentario editado no tiene score y el de antes si)
            $space->save(); //Guarda los cambios en el espacio
        });

        static::deleted(function ($comment) {
            if ($comment->status === 'Y') { //Comprueba que el comentario eliminado tiene status "Y" (Aceptado por un administrador) para restar la puntuación al espacio
                $space = $comment->space; //Obtiene el espacio asociado al comentario
                $space->totalScore -= $comment->score ?? 0; //Resta la puntuación del comentario al totalScore del espacio, si el score es null resta 0
                //$space->countScore -= 1;
                if ($comment->score !== null) { //Si el score del comentario no es null, resta 1 al countScore del espacio
                    $space->countScore -= 1;
                }
                $space->save(); //Guarda los cambios en el espacio
            }
        });
    }*/
}