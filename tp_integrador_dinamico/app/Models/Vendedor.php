<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    use HasFactory;

    protected $table = 'vendedor';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'apellido',
        'legajo',
        'comision',
        'fecha_ingreso',
        'activo',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'activo'        => 'boolean',
        'comision'      => 'decimal:2',
    ];

    public function ventas()
    {
        return $this->hasMany(Ventas::class, 'id_vendedor');
    }

    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombre} {$this->apellido}");
    }

    public function getTotalVentasAttribute()
    {
        return $this->ventas->sum('precio_final');
    }

    public function getTotalComisionesAttribute()
    {
        $total = 0;
        foreach ($this->ventas as $venta) {
            $total += ($venta->precio_final * ($this->comision ?? 0)) / 100;
        }
        return $total;
    }
}
