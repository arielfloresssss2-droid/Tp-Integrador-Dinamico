<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    protected $table = 'marcas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'foto',
    ];

    /**
     * Relación con los autos de esta marca
     */
    public function autos()
    {
        return $this->hasMany(Auto::class, 'id_marca');
    }

    /**
     * URL del logo/foto de la marca
     */
    public function getFotoUrlAttribute()
    {
        if (!empty($this->foto)) {
            if (str_starts_with($this->foto, 'http://') || str_starts_with($this->foto, 'https://')) {
                return $this->foto;
            }
            return asset('uploads/marcas/' . $this->foto);
        }
        return null;
    }
}
