<?php

namespace App\Models;

use App\Models\Municipality;
use Illuminate\Database\Eloquent\Model;

class Island extends Model
{
    // Relacions entre taules:
    public function municipalities()
    {
        return $this->hasMany(Municipality::class);  // 1:N
    }
}
