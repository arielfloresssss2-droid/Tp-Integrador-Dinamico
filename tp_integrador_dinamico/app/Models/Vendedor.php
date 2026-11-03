<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    use HasFactory;

    protected $table = 'vendedor';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellido',
        'legajo',
        'comision',
        'fecha_ingreso',
        'activo',
    ];

    public function ventas()
    {
        return $this->hasMany(Ventas::class, 'id_vendedor');
    }
}
