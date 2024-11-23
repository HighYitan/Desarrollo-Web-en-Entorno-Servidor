<?php

namespace App\Models;

use App\Models\Island;
use App\Models\Address;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    // Relacions entre taules:
    public function addresses()
    {
        return $this->hasMany(Address::class);  // 1:N
    }
    public function island()
    {
        return $this->belongsTo(Island::class);  // N:1
    }
}
