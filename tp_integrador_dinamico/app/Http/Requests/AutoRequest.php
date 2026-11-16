<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutoRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para la entidad Auto (Store y Update).
     */
    public function rules(): array
    {
        $currentYear = (int) date('Y');

        return [
            'id_marca'    => ['required', 'exists:marcas,id'],
            'modelo'      => ['required', 'string', 'max:100'],
            'anio'        => ['required', 'integer', 'min:1950', 'max:' . ($currentYear + 1)],
            'precio'      => ['required', 'numeric', 'min:0'],
            'kilometraje' => ['required', 'integer', 'min:0'],
            'tipo'        => ['required', 'in:nuevo,usado'],
            'combustible' => ['required', 'in:diesel,nafta,hibrido,electrico'],
            'estado'      => ['sometimes', 'required', 'in:disponible,reservado,vendido'],
            'imagen'      => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Mensajes de error personalizados en español.
     */
    public function messages(): array
    {
        return [
            'id_marca.required'    => 'Debe seleccionar una marca para el vehículo.',
            'id_marca.exists'      => 'La marca seleccionada no es válida.',
            'modelo.required'      => 'El modelo del vehículo es obligatorio.',
            'anio.required'        => 'El año de fabricación es obligatorio.',
            'anio.integer'         => 'El año debe ser un número entero.',
            'anio.min'             => 'El año no puede ser anterior a 1950.',
            'anio.max'             => 'El año no puede ser mayor al año próximo.',
            'precio.required'      => 'El precio del vehículo es obligatorio.',
            'precio.numeric'       => 'El precio debe ser un número.',
            'precio.min'           => 'El precio no puede ser negativo.',
            'kilometraje.required' => 'El kilometraje es obligatorio.',
            'kilometraje.integer'  => 'El kilometraje debe ser un número entero.',
            'kilometraje.min'      => 'El kilometraje no puede ser negativo.',
            'tipo.required'        => 'Debe especificar si el auto es nuevo o usado.',
            'tipo.in'              => 'El tipo debe ser nuevo o usado.',
            'combustible.required' => 'Debe seleccionar el tipo de combustible.',
            'combustible.in'       => 'El tipo de combustible seleccionado no es válido.',
            'imagen.image'         => 'El archivo debe ser una imagen válida (jpeg, png, jpg, webp).',
            'imagen.max'           => 'La imagen no debe superar los 5MB de tamaño.',
        ];
    }

    /**
     * Nombres legibles para los atributos validados.
     */
    public function attributes(): array
    {
        return [
            'id_marca'    => 'marca',
            'modelo'      => 'modelo',
            'anio'        => 'año',
            'precio'      => 'precio',
            'kilometraje' => 'kilometraje',
            'tipo'        => 'tipo de vehículo',
            'combustible' => 'combustible',
            'estado'      => 'estado',
            'imagen'      => 'imagen',
            'descripcion' => 'descripción',
        ];
    }
}
