<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class); // N:1
    }
    public function comments()
    {
        return $this->hasMany(Comment::class); // 1:N
    }
    public function user()
    {
        return $this->belongsTo(User::class); // N:1
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class); // M:N
    }
}
