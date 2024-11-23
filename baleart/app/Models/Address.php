<?php

namespace App\Models;

use App\Models\Zone;
use App\Models\Space;
use App\Models\Municipality;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    // Relacions entre taules:
    public function municipality()
    {
        return $this->belongsTo(Municipality::class);  // N:1
    }
    public function spaces()
    {
        return $this->hasMany(Space::class);  // 1:N
    }
    public function zone()
    {
        return $this->belongsTo(Zone::class);  // N:1
    }
}
