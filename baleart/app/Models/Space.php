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
    // Relacions entre taules:
    public function address()
    {
        return $this->belongsTo(Address::class);  // N:1
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
}
