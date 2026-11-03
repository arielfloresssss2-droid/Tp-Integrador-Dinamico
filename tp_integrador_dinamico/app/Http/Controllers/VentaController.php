<?php

namespace App\Http\Controllers;

use App\Models\Auto;
use App\Models\Cliente;
use App\Models\Ventas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    /**
     * Muestra la pantalla de confirmación de compra / checkout.
     */
    public function checkout($id)
    {
        $auto = Auto::with('marca', 'user')->findOrFail($id);

        if ($auto->estado !== 'disponible') {
            return redirect()->route('autos.show', $auto->id)
                ->with('warning', 'Este vehículo ya no se encuentra disponible para la venta.');
        }

        if ($auto->user_id === Auth::id()) {
            return redirect()->route('autos.show', $auto->id)
                ->with('error', 'No puedes comprar un vehículo que tú mismo publicaste.');
        }

        return view('ventas.checkout', compact('auto'));
    }

    /**
     * Procesa la compra del vehículo.
     */
    public function comprar(Request $request, $id)
    {
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,transferencia,tarjeta',
            'telefono'    => 'nullable|string|max:30',
            'direccion'   => 'nullable|string|max:100',
            'dni'         => 'nullable|string|max:20',
        ], [
            'metodo_pago.required' => 'Debe seleccionar un método de pago.',
            'metodo_pago.in'       => 'El método de pago seleccionado no es válido.',
        ]);

        $user = Auth::user();

        return DB::transaction(function () use ($id, $request, $user) {
            $auto = Auto::lockForUpdate()->findOrFail($id);

            if ($auto->estado !== 'disponible') {
                return redirect()->route('autos.show', $auto->id)
                    ->with('error', 'Lo sentimos, este vehículo acaba de ser comprado o reservado.');
            }

            if ($auto->user_id === $user->id) {
                return redirect()->route('autos.show', $auto->id)
                    ->with('error', 'No puedes comprar tu propia publicación.');
            }

            // Sincronizar o crear registro en tabla cliente para compatibilidad con el esquema
            $cliente = Cliente::firstOrCreate(
                ['email' => $user->email],
                [
                    'nombre'    => $user->name,
                    'apellido'  => '',
                    'dni'       => $request->dni ?? '',
                    'telefono'  => $request->telefono ? (int) preg_replace('/\D/', '', $request->telefono) : null,
                    'direccion' => $request->direccion ?? '',
                ]
            );

            // Cambiar estado del auto a vendido
            $auto->estado = 'vendido';
            $auto->save();

            // Registrar la venta
            $venta = Ventas::create([
                'user_id'      => $user->id,
                'id_cliente'   => $cliente->id,
                'id_auto'      => $auto->id,
                'id_vendedor'  => null,
                'fecha_venta'  => now()->toDateString(),
                'precio_final' => $auto->precio,
                'metodo_pago'  => $request->metodo_pago,
            ]);

            return redirect()->route('ventas.compras')
                ->with('success', '¡Felicitaciones por tu compra! Has adquirido el ' . $auto->marca->nombre . ' ' . $auto->modelo . ' con éxito.');
        });
    }

    /**
     * Muestra las compras realizadas por el usuario logueado.
     */
    public function myCompras()
    {
        $compras = Ventas::with(['auto.marca', 'auto.user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('ventas.my_purchases', compact('compras'));
    }

    /**
     * Muestra los autos vendidos que pertenecían al usuario logueado.
     */
    public function myVentas()
    {
        $userId = Auth::id();

        $ventas = Ventas::with(['auto.marca', 'comprador'])
            ->whereHas('auto', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest()
            ->paginate(10);

        return view('ventas.my_sales', compact('ventas'));
    }
}
