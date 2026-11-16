<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VendedorRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para la entidad Vendedor (Store y Update).
     */
    public function rules(): array
    {
        $vendedorId = $this->route('id') ?? $this->route('vendedore');

        return [
            'nombre'        => ['required', 'string', 'max:50'],
            'apellido'      => ['required', 'string', 'max:50'],
            'legajo'        => [
                'required',
                'string',
                'max:20',
                Rule::unique('vendedor', 'legajo')->ignore($vendedorId),
            ],
            'comision'      => ['required', 'numeric', 'min:0', 'max:100'],
            'fecha_ingreso' => ['required', 'date'],
            'activo'        => ['nullable', 'boolean'],
        ];
    }

    /**
     * Mensajes de error personalizados en español.
     */
    public function messages(): array
    {
        return [
            'nombre.required'        => 'El nombre del vendedor es obligatorio.',
            'nombre.max'             => 'El nombre no puede superar los 50 caracteres.',
            'apellido.required'      => 'El apellido del vendedor es obligatorio.',
            'apellido.max'           => 'El apellido no puede superar los 50 caracteres.',
            'legajo.required'        => 'El número de legajo es obligatorio.',
            'legajo.unique'          => 'Ya existe un vendedor registrado con este número de legajo.',
            'comision.required'      => 'El porcentaje de comisión es obligatorio.',
            'comision.numeric'       => 'El porcentaje de comisión debe ser un valor numérico.',
            'comision.min'           => 'La comisión no puede ser negativa.',
            'comision.max'           => 'La comisión no puede superar el 100%.',
            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
            'fecha_ingreso.date'     => 'La fecha de ingreso debe tener un formato de fecha válido.',
        ];
    }

    /**
     * Nombres legibles para los atributos validados.
     */
    public function attributes(): array
    {
        return [
            'nombre'        => 'nombre',
            'apellido'      => 'apellido',
            'legajo'        => 'número de legajo',
            'comision'      => 'porcentaje de comisión',
            'fecha_ingreso' => 'fecha de ingreso',
            'activo'        => 'estado activo',
        ];
    }
}
