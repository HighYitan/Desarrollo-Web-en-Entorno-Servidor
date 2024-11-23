<?php

namespace App\Models;

use App\Models\User;
use App\Models\Image;
use App\Models\Space;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
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
}
