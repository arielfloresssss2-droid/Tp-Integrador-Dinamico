<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'apellido',
        'dni',
        'telefono',
        'direccion',
        'email',
    ];

    public function ventas()
    {
        return $this->hasMany(Ventas::class, 'id_cliente');
    }

    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombre} {$this->apellido}") ?: ($this->email ?? 'Cliente sin nombre');
    }
}
