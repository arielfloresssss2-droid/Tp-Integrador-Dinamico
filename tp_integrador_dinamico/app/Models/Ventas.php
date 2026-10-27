<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    use HasFactory;
}

public function cliente() {
    return $this->belongsTo(cliente::class);
}

public function auto() {
    return $this->belongsTo(Auto::class);
}
