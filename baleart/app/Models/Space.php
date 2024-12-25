<?php

namespace App\Models;

use App\Models\User;
use App\Models\Address;
use App\Models\Comment;
use App\Models\Service;
use App\Models\Modality;
use App\Models\SpaceType;
use Illuminate\Database\Eloquent\Model;

class Space extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name",
        "regNumber",
        "observation_CA",
        "observation_ES",
        "observation_EN",
        "email",
        "phone",
        "website",
        "accessType",
        "address_id",
        "space_type_id",
        "user_id",
    ];

    protected $guarded = [
        'id',
        'totalScore',
        'countScore',
        'created_at',
        'updated_at',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
        'totalScore',
        'countScore',
        'created_at',
        'updated_at',
    ];
    // Relacions entre taules:
    public function address()
    {
        return $this->belongsTo(Address::class);  // 1:1
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);  // 1:N
    }
    public function modalities()
    {
        return $this->belongsToMany(Modality::class); // N:M
    }
    public function services()
    {
        return $this->belongsToMany(Service::class); // N:M
    }
    public function spaceType()
    {
        return $this->belongsTo(SpaceType::class); // N:1
    }
    public function user()
    {
        return $this->belongsTo(User::class); // N:1
    }

    public function calculateScores() // Sirve para recalcular la puntuación total y el número de puntuaciones de un espacio si se ha modificado/añadido/borrado algún comentario llamándolo desde store(), update() y destroy() de CommentController.  
    {
        $comments = $this->comments()->where('status', 'Y')->get(); // Solo los comentarios con status 'Y'
        $totalScore = $comments->sum('score'); // Suma de todas las puntuaciones
        $countScore = $comments->where('score', '!=', null)->count(); // Cuenta todas las puntuaciones que no sean null

        $this->totalScore = $totalScore; // Asigna la suma de todas las puntuaciones
        $this->countScore = $countScore; // Asigna el número de puntuaciones
        $this->save(); // Guarda los cambios en la base de datos
    }
}
