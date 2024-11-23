<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // Relacions entre taules:
    public function users()
    {
        return $this->hasMany(User::class);  // 1:N
    }
}
