<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    use HasFactory;

    protected $table = 'ventas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'id_cliente',
        'id_auto',
        'id_vendedor',
        'fecha_venta',
        'precio_final',
        'metodo_pago',
    ];

    protected $casts = [
        'fecha_venta' => 'date',
    ];

    /**
     * Relación con el Auto comprado/vendido
     */
    public function auto()
    {
        return $this->belongsTo(Auto::class, 'id_auto');
    }

    /**
     * Relación con el Usuario comprador
     */
    public function comprador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con el Cliente (compatibilidad)
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    /**
     * Relación con el Vendedor
     */
    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class, 'id_vendedor');
    }

    /**
     * Precio final formateado
     */
    public function getPrecioFinalFormateadoAttribute()
    {
        return '$ ' . number_format($this->precio_final ?? 0, 0, ',', '.');
    }
}
