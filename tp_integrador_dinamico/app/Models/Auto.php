<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auto extends Model
{
    use HasFactory;
}

public function marca() {
    return $this->belongsTo(Marca::class);
}

public function ventas() {
    return $this->hasMany(Ventas::class);
}
