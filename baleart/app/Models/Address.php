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
    public function space()
    {
        return $this->hasOne(Space::class);  // 1:1
    }
    public function zone()
    {
        return $this->belongsTo(Zone::class);  // N:1
    }
}
