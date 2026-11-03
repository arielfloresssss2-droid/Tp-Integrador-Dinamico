<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auto extends Model
{
    use HasFactory;

    protected $table = 'auto';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_marca',
        'user_id',
        'modelo',
        'anio',
        'precio',
        'kilometraje',
        'tipo',
        'combustible',
        'estado',
        'imagen',
        'descripcion',
    ];

    /**
     * Relación con la Marca
     */
    public function marca()
    {
        return $this->belongsTo(Marca::class, 'id_marca');
    }

    /**
     * Relación con el Usuario que publicó el auto
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación con las Ventas del auto
     */
    public function ventas()
    {
        return $this->hasMany(Ventas::class, 'id_auto');
    }

    /**
     * URL completa de la imagen o imagen por defecto
     */
    public function getImagenUrlAttribute()
    {
        if (!empty($this->imagen)) {
            if (str_starts_with($this->imagen, 'http://') || str_starts_with($this->imagen, 'https://')) {
                return $this->imagen;
            }
            return asset('uploads/autos/' . $this->imagen);
        }
        return asset('images/auto-placeholder.svg');
    }

    /**
     * Precio formateado en pesos argentinos
     */
    public function getPrecioFormateadoAttribute()
    {
        return '$ ' . number_format($this->precio ?? 0, 0, ',', '.');
    }

    /**
     * Kilometraje formateado
     */
    public function getKilometrajeFormateadoAttribute()
    {
        return number_format($this->kilometraje ?? 0, 0, ',', '.') . ' km';
    }
}
