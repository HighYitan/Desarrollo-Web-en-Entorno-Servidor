<?php

namespace App\Models;

use App\Models\Address;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    // Relacions entre taules:
    public function addresses()
    {
        return $this->hasMany(Address::class);  // 1:N
    }
}
