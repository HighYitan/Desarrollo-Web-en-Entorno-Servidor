<?php

namespace App\Models;

use App\Models\Post;
use App\Models\User;
use App\Models\Image;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    // Relación 1:N con Image
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function post()
    {
        return $this->BelongsTo(Post::class);
    }
    public function user()
    {
        return $this->BelongsTo(User::class);
    }
}
