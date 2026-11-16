<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VentaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación para la entidad Venta (Store y Update).
     */
    public function rules(): array
    {
        // En store validamos id_auto y selección/creación de cliente
        if ($this->isMethod('post')) {
            return [
                'id_auto'           => ['required', 'exists:auto,id'],
                'id_vendedor'       => ['nullable', 'exists:vendedor,id'],
                'cliente_tipo'      => ['required', 'in:existente,nuevo'],
                'id_cliente'        => ['nullable', 'required_if:cliente_tipo,existente', 'exists:cliente,id'],
                'cliente_nombre'    => ['nullable', 'required_if:cliente_tipo,nuevo', 'string', 'max:50'],
                'cliente_apellido'  => ['nullable', 'required_if:cliente_tipo,nuevo', 'string', 'max:50'],
                'cliente_dni'       => ['nullable', 'string', 'max:20'],
                'cliente_telefono'  => ['nullable', 'string', 'max:30'],
                'cliente_email'     => ['nullable', 'email', 'max:50'],
                'cliente_direccion' => ['nullable', 'string', 'max:100'],
                'fecha_venta'       => ['required', 'date'],
                'precio_final'      => ['required', 'numeric', 'min:0'],
                'metodo_pago'       => ['required', 'in:efectivo,transferencia,tarjeta'],
            ];
        }

        // En update (PUT/PATCH)
        return [
            'id_vendedor'  => ['nullable', 'exists:vendedor,id'],
            'id_cliente'   => ['nullable', 'exists:cliente,id'],
            'fecha_venta'  => ['required', 'date'],
            'precio_final' => ['required', 'numeric', 'min:0'],
            'metodo_pago'  => ['required', 'in:efectivo,transferencia,tarjeta'],
        ];
    }

    /**
     * Mensajes de error personalizados en español.
     */
    public function messages(): array
    {
        return [
            'id_auto.required'              => 'Debe seleccionar un vehículo disponible para la venta.',
            'id_auto.exists'                => 'El vehículo seleccionado no existe en el sistema.',
            'id_vendedor.exists'            => 'El vendedor seleccionado no es válido.',
            'cliente_tipo.required'         => 'Debe indicar si el cliente es existente o nuevo.',
            'id_cliente.required_if'        => 'Debe seleccionar un cliente registrado.',
            'id_cliente.exists'             => 'El cliente seleccionado no es válido.',
            'cliente_nombre.required_if'    => 'El nombre del cliente es obligatorio.',
            'cliente_apellido.required_if'  => 'El apellido del cliente es obligatorio.',
            'cliente_email.email'           => 'El correo electrónico debe ser una dirección válida.',
            'fecha_venta.required'          => 'La fecha de la venta es obligatoria.',
            'fecha_venta.date'              => 'La fecha de la venta debe tener un formato válido.',
            'precio_final.required'         => 'El precio final de la operación es obligatorio.',
            'precio_final.numeric'          => 'El precio final debe ser un valor numérico.',
            'precio_final.min'              => 'El precio final no puede ser menor a cero.',
            'metodo_pago.required'          => 'Debe seleccionar el método de pago.',
            'metodo_pago.in'                => 'El método de pago seleccionado no es válido.',
        ];
    }

    /**
     * Nombres legibles para los atributos validados.
     */
    public function attributes(): array
    {
        return [
            'id_auto'           => 'vehículo',
            'id_vendedor'       => 'vendedor asignado',
            'id_cliente'        => 'cliente',
            'cliente_nombre'    => 'nombre del cliente',
            'cliente_apellido'  => 'apellido del cliente',
            'fecha_venta'       => 'fecha de venta',
            'precio_final'      => 'precio final',
            'metodo_pago'       => 'método de pago',
        ];
    }
}
