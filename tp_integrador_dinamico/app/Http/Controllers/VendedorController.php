<?php

namespace App\Http\Controllers;

use App\Models\Vendedor;
use Illuminate\Http\Request;

class VendedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendedor::withCount('ventas')->with('ventas');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('apellido', 'like', "%{$search}%")
                  ->orWhere('legajo', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado')) {
            if ($request->estado === 'activo') {
                $query->where('activo', true);
            } elseif ($request->estado === 'inactivo') {
                $query->where('activo', false);
            }
        }

        switch ($request->get('sort')) {
            case 'antiguos':
                $query->orderBy('fecha_ingreso', 'asc');
                break;
            case 'recientes':
                $query->orderBy('fecha_ingreso', 'desc');
                break;
            case 'ventas_desc':
                $query->orderBy('ventas_count', 'desc');
                break;
            default:
                $query->orderBy('apellido', 'asc')->orderBy('nombre', 'asc');
                break;
        }

        $vendedores = $query->paginate(10)->withQueryString();

        $totalVendedores = Vendedor::count();
        $totalActivos    = Vendedor::where('activo', true)->count();
        $totalInactivos  = Vendedor::where('activo', false)->count();

        return view('vendedores.index', compact('vendedores', 'totalVendedores', 'totalActivos', 'totalInactivos'));
    }

    public function create()
    {
        return view('vendedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'        => 'required|string|max:50',
            'apellido'      => 'required|string|max:50',
            'legajo'        => 'required|string|max:20|unique:vendedor,legajo',
            'comision'      => 'required|numeric|min:0|max:100',
            'fecha_ingreso' => 'required|date',
            'activo'        => 'nullable|boolean',
        ], [
            'nombre.required'        => 'El nombre es obligatorio.',
            'apellido.required'      => 'El apellido es obligatorio.',
            'legajo.required'        => 'El número de legajo es obligatorio.',
            'legajo.unique'          => 'Ya existe un vendedor registrado con este legajo.',
            'comision.required'      => 'El porcentaje de comisión es obligatorio.',
            'comision.numeric'       => 'La comisión debe ser un valor numérico.',
            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
        ]);

        $data = $request->only([
            'nombre',
            'apellido',
            'legajo',
            'comision',
            'fecha_ingreso',
        ]);

        $data['activo'] = $request->has('activo') ? true : false;

        $vendedor = Vendedor::create($data);

        return redirect()->route('vendedores.index')
            ->with('success', "El vendedor {$vendedor->nombre_completo} (Legajo: {$vendedor->legajo}) fue dado de alta exitosamente.");
    }

    public function show($id)
    {
        $vendedor = Vendedor::with(['ventas.auto.marca', 'ventas.cliente', 'ventas.comprador'])->findOrFail($id);
        $ventas = $vendedor->ventas()->latest('fecha_venta')->paginate(8);

        return view('vendedores.show', compact('vendedor', 'ventas'));
    }

    public function edit($id)
    {
        $vendedor = Vendedor::findOrFail($id);
        return view('vendedores.edit', compact('vendedor'));
    }

    public function update(Request $request, $id)
    {
        $vendedor = Vendedor::findOrFail($id);

        $request->validate([
            'nombre'        => 'required|string|max:50',
            'apellido'      => 'required|string|max:50',
            'legajo'        => 'required|string|max:20|unique:vendedor,legajo,' . $vendedor->id,
            'comision'      => 'required|numeric|min:0|max:100',
            'fecha_ingreso' => 'required|date',
            'activo'        => 'nullable|boolean',
        ], [
            'nombre.required'        => 'El nombre es obligatorio.',
            'apellido.required'      => 'El apellido es obligatorio.',
            'legajo.required'        => 'El número de legajo es obligatorio.',
            'legajo.unique'          => 'Ya existe otro vendedor con este número de legajo.',
            'comision.required'      => 'El porcentaje de comisión es obligatorio.',
            'fecha_ingreso.required' => 'La fecha de ingreso es obligatoria.',
        ]);

        $data = $request->only([
            'nombre',
            'apellido',
            'legajo',
            'comision',
            'fecha_ingreso',
        ]);

        $data['activo'] = $request->has('activo') ? true : false;

        $vendedor->update($data);

        return redirect()->route('vendedores.index')
            ->with('success', "Los datos del vendedor {$vendedor->nombre_completo} han sido actualizados.");
    }

    public function toggleActivo($id)
    {
        $vendedor = Vendedor::findOrFail($id);
        $vendedor->activo = !$vendedor->activo;
        $vendedor->save();

        $estadoStr = $vendedor->activo ? 'activo' : 'inactivo';
        return redirect()->back()
            ->with('info', "El vendedor {$vendedor->nombre_completo} ahora está {$estadoStr}.");
    }

    public function destroy($id)
    {
        $vendedor = Vendedor::withCount('ventas')->findOrFail($id);

        if ($vendedor->ventas_count > 0) {
            $vendedor->activo = false;
            $vendedor->save();

            return redirect()->route('vendedores.index')
                ->with('warning', "El vendedor {$vendedor->nombre_completo} no puede ser eliminado porque posee {$vendedor->ventas_count} venta(s) asociada(s). Se ha marcado como inactivo.");
        }

        $nombre = $vendedor->nombre_completo;
        $vendedor->delete();

        return redirect()->route('vendedores.index')
            ->with('success', "El vendedor {$nombre} fue eliminado correctamente.");
    }
}
