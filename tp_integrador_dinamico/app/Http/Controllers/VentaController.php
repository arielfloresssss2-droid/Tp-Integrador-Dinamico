<?php

namespace App\Http\Controllers;

use App\Models\Auto;
use App\Models\Cliente;
use App\Models\Vendedor;
use App\Models\Ventas;
use App\Http\Requests\VentaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $query = Ventas::with(['auto.marca', 'vendedor', 'cliente', 'comprador']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('auto', function ($a) use ($search) {
                    $a->where('modelo', 'like', "%{$search}%");
                })->orWhereHas('cliente', function ($c) use ($search) {
                    $c->where('nombre', 'like', "%{$search}%")
                      ->orWhere('apellido', 'like', "%{$search}%")
                      ->orWhere('dni', 'like', "%{$search}%");
                })->orWhereHas('vendedor', function ($v) use ($search) {
                    $v->where('nombre', 'like', "%{$search}%")
                      ->orWhere('apellido', 'like', "%{$search}%")
                      ->orWhere('legajo', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('vendedor_id')) {
            $query->where('id_vendedor', $request->vendedor_id);
        }

        if ($request->filled('metodo_pago')) {
            $query->where('metodo_pago', $request->metodo_pago);
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_venta', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_venta', '<=', $request->fecha_hasta);
        }

        switch ($request->get('sort')) {
            case 'fecha_asc':
                $query->orderBy('fecha_venta', 'asc')->orderBy('id', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('precio_final', 'desc');
                break;
            case 'precio_asc':
                $query->orderBy('precio_final', 'asc');
                break;
            case 'fecha_desc':
            default:
                $query->orderBy('fecha_venta', 'desc')->orderBy('id', 'desc');
                break;
        }

        $ventas = $query->paginate(10)->withQueryString();

        $totalVentasMonto    = Ventas::sum('precio_final');
        $cantidadOperaciones = Ventas::count();
        $promedioVenta       = $cantidadOperaciones > 0 ? ($totalVentasMonto / $cantidadOperaciones) : 0;
        $totalComisiones     = 0;
        foreach (Ventas::with('vendedor')->get() as $v) {
            $totalComisiones += $v->comision_monto;
        }

        $vendedores = Vendedor::orderBy('apellido')->get();

        return view('ventas.index', compact(
            'ventas',
            'totalVentasMonto',
            'cantidadOperaciones',
            'promedioVenta',
            'totalComisiones',
            'vendedores'
        ));
    }

    public function create()
    {
        $autosDisponibles = Auto::with('marca')
            ->where('estado', 'disponible')
            ->orderBy('modelo')
            ->get();

        $vendedores = Vendedor::where('activo', true)
            ->orderBy('apellido')
            ->get();

        $clientes = Cliente::orderBy('apellido')->orderBy('nombre')->get();

        return view('ventas.create', compact('autosDisponibles', 'vendedores', 'clientes'));
    }

    public function store(VentaRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $auto = Auto::lockForUpdate()->findOrFail($request->id_auto);

            if ($auto->estado !== 'disponible') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'El vehículo seleccionado ya no se encuentra disponible (estado: ' . $auto->estado . ').');
            }

            if ($request->cliente_tipo === 'nuevo') {
                $telefonoNumerico = $request->cliente_telefono ? (int) preg_replace('/\D/', '', $request->cliente_telefono) : null;
                $cliente = Cliente::create([
                    'nombre'    => $request->cliente_nombre,
                    'apellido'  => $request->cliente_apellido,
                    'dni'       => $request->cliente_dni,
                    'telefono'  => $telefonoNumerico,
                    'email'     => $request->cliente_email,
                    'direccion' => $request->cliente_direccion,
                ]);
                $clienteId = $cliente->id;
            } else {
                $clienteId = $request->id_cliente;
            }

            $auto->estado = 'vendido';
            $auto->save();

            $venta = Ventas::create([
                'id_auto'      => $auto->id,
                'id_vendedor'  => $request->id_vendedor,
                'id_cliente'   => $clienteId,
                'user_id'      => Auth::id(),
                'fecha_venta'  => $request->fecha_venta,
                'precio_final' => $request->precio_final,
                'metodo_pago'  => $request->metodo_pago,
            ]);

            return redirect()->route('ventas.show', $venta->id)
                ->with('success', "¡Venta N° {$venta->id} registrada exitosamente!");
        });
    }

    public function show($id)
    {
        $venta = Ventas::with(['auto.marca', 'vendedor', 'cliente', 'comprador'])->findOrFail($id);
        return view('ventas.show', compact('venta'));
    }

    public function edit($id)
    {
        $venta = Ventas::with(['auto.marca', 'vendedor', 'cliente'])->findOrFail($id);
        $vendedores = Vendedor::orderBy('apellido')->get();
        $clientes   = Cliente::orderBy('apellido')->orderBy('nombre')->get();

        return view('ventas.edit', compact('venta', 'vendedores', 'clientes'));
    }

    public function update(VentaRequest $request, $id)
    {
        $venta = Ventas::findOrFail($id);

        $venta->update([
            'id_vendedor'  => $request->id_vendedor,
            'id_cliente'   => $request->id_cliente,
            'fecha_venta'  => $request->fecha_venta,
            'precio_final' => $request->precio_final,
            'metodo_pago'  => $request->metodo_pago,
        ]);

        return redirect()->route('ventas.show', $venta->id)
            ->with('success', "La Venta N° {$venta->id} fue actualizada correctamente.");
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {
            $venta = Ventas::with('auto')->findOrFail($id);

            if ($venta->auto && $venta->auto->estado === 'vendido') {
                $venta->auto->estado = 'disponible';
                $venta->auto->save();
            }

            $ventaId = $venta->id;
            $venta->delete();

            return redirect()->route('ventas.index')
                ->with('info', "La Venta N° {$ventaId} ha sido eliminada y el vehículo asociado volvió a estar disponible.");
        });
    }

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

    public function comprar(Request $request, $id)
    {
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,transferencia,tarjeta',
            'dni'         => 'required|string|not_regex:/[a-zA-Z]/|max:20',
            'telefono'    => 'required|string|max:30',
            'direccion'   => 'required|string|max:100',
        ], [
            'metodo_pago.required' => 'Debe seleccionar un método de pago.',
            'metodo_pago.in'       => 'El método de pago no es válido.',
            'dni.required'         => 'El número de DNI es obligatorio.',
            'dni.not_regex'        => 'El DNI solo puede contener números.',
            'telefono.required'    => 'El teléfono de contacto es obligatorio.',
            'direccion.required'   => 'La dirección es obligatoria.',
        ]);

        $user = Auth::user();

        return DB::transaction(function () use ($id, $request, $user) {
            $auto = Auto::lockForUpdate()->findOrFail($id);

            if ($auto->estado !== 'disponible') {
                return redirect()->route('autos.show', $auto->id)
                    ->with('error', 'Lo sentimos, este vehículo acaba de ser adquirido o reservado.');
            }

            if ($auto->user_id === $user->id) {
                return redirect()->route('autos.show', $auto->id)
                    ->with('error', 'No puedes comprar tu propia publicación.');
            }

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

            $primerVendedor = Vendedor::where('activo', true)->first();

            $auto->estado = 'vendido';
            $auto->save();

            $venta = Ventas::create([
                'user_id'      => $user->id,
                'id_cliente'   => $cliente->id,
                'id_auto'      => $auto->id,
                'id_vendedor'  => $primerVendedor ? $primerVendedor->id : null,
                'fecha_venta'  => now()->toDateString(),
                'precio_final' => $auto->precio,
                'metodo_pago'  => $request->metodo_pago,
            ]);

            return redirect()->route('ventas.compras')
                ->with('success', '¡Felicitaciones por tu compra! Has adquirido el ' . ($auto->marca->nombre ?? '') . ' ' . $auto->modelo . ' con éxito.');
        });
    }

    public function myCompras()
    {
        $compras = Ventas::with(['auto.marca', 'auto.user', 'vendedor'])
            ->where('user_id', Auth::id())
            ->latest('fecha_venta')
            ->paginate(10);

        return view('ventas.my_purchases', compact('compras'));
    }

    public function myVentas()
    {
        $userId = Auth::id();

        $ventas = Ventas::with(['auto.marca', 'comprador', 'cliente'])
            ->whereHas('auto', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest('fecha_venta')
            ->paginate(10);

        return view('ventas.my_sales', compact('ventas'));
    }
}
