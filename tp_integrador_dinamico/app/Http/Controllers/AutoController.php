<?php

namespace App\Http\Controllers;

use App\Models\Auto;
use App\Models\Marca;
use App\Http\Requests\AutoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AutoController extends Controller
{
    /**
     * Muestra el catálogo de autos con filtros y buscador.
     */
    public function index(Request $request)
    {
        $query = Auto::with(['marca', 'user']);

        // Por defecto mostramos disponibles, pero si el usuario filtra por estado específico se respeta
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        } else {
            $query->where('estado', 'disponible');
        }

        // Búsqueda por texto (modelo o descripción)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('modelo', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%")
                  ->orWhereHas('marca', function ($m) use ($search) {
                      $m->where('nombre', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por marca
        if ($request->filled('marca')) {
            $query->where('id_marca', $request->marca);
        }

        // Filtro por tipo (nuevo / usado)
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // Filtro por combustible
        if ($request->filled('combustible')) {
            $query->where('combustible', $request->combustible);
        }

        // Filtro por precio mínimo y máximo
        if ($request->filled('precio_min')) {
            $query->where('precio', '>=', (float) $request->precio_min);
        }
        if ($request->filled('precio_max')) {
            $query->where('precio', '<=', (float) $request->precio_max);
        }

        // Filtro por año mínimo y máximo
        if ($request->filled('anio_min')) {
            $query->where('anio', '>=', (int) $request->anio_min);
        }
        if ($request->filled('anio_max')) {
            $query->where('anio', '<=', (int) $request->anio_max);
        }

        // Ordenamiento
        switch ($request->get('sort')) {
            case 'precio_asc':
                $query->orderBy('precio', 'asc');
                break;
            case 'precio_desc':
                $query->orderBy('precio', 'desc');
                break;
            case 'anio_desc':
                $query->orderBy('anio', 'desc');
                break;
            case 'km_asc':
                $query->orderBy('kilometraje', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $autos = $query->paginate(12)->withQueryString();
        $marcas = Marca::orderBy('nombre')->get();

        return view('autos.index', compact('autos', 'marcas'));
    }

    /**
     * Muestra la vista detallada de un auto específico.
     */
    public function show($id)
    {
        $auto = Auto::with(['marca', 'user', 'ventas.comprador'])->findOrFail($id);
        
        // Autos relacionados de la misma marca o similar
        $relacionados = Auto::with('marca')
            ->where('id', '!=', $auto->id)
            ->where('estado', 'disponible')
            ->where(function ($q) use ($auto) {
                $q->where('id_marca', $auto->id_marca)
                  ->orWhere('tipo', $auto->tipo);
            })
            ->take(3)
            ->get();

        return view('autos.show', compact('auto', 'relacionados'));
    }

    /**
     * Formulario para publicar un nuevo auto.
     */
    public function create()
    {
        $marcas = Marca::orderBy('nombre')->get();
        return view('autos.create', compact('marcas'));
    }

    /**
     * Almacena un auto publicado en la base de datos.
     */
    public function store(AutoRequest $request)
    {
        $data = $request->only([
            'id_marca',
            'modelo',
            'anio',
            'precio',
            'kilometraje',
            'tipo',
            'combustible',
            'descripcion',
        ]);

        $data['user_id'] = Auth::id();
        $data['estado'] = 'disponible';

        // Manejo de la subida de foto
        if ($request->hasFile('imagen')) {
            $destinationPath = public_path('uploads/autos');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            $file = $request->file('imagen');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $filename);
            $data['imagen'] = $filename;
        }

        $auto = Auto::create($data);

        return redirect()->route('autos.show', $auto->id)
            ->with('success', '¡Excelente! Tu vehículo fue publicado correctamente y ya se encuentra visible en el catálogo.');
    }

    /**
     * Muestra las publicaciones del usuario autenticado.
     */
    public function myAutos()
    {
        $autos = Auto::with(['marca', 'ventas.comprador'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('autos.my_autos', compact('autos'));
    }

    /**
     * Formulario para editar un auto publicado.
     */
    public function edit($id)
    {
        $auto = Auto::findOrFail($id);
        $marcas = Marca::orderBy('nombre')->get();

        return view('autos.edit', compact('auto', 'marcas'));
    }

    /**
     * Actualiza la información y/o foto de un auto publicado.
     */
    public function update(AutoRequest $request, $id)
    {
        $auto = Auto::findOrFail($id);

        $data = $request->only([
            'id_marca',
            'modelo',
            'anio',
            'precio',
            'kilometraje',
            'tipo',
            'combustible',
            'estado',
            'descripcion',
        ]);

        if ($request->hasFile('imagen')) {
            $destinationPath = public_path('uploads/autos');
            if (!File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            // Eliminar imagen anterior si existe y no es remota
            if ($auto->imagen && File::exists(public_path('uploads/autos/' . $auto->imagen))) {
                File::delete(public_path('uploads/autos/' . $auto->imagen));
            }

            $file = $request->file('imagen');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $filename);
            $data['imagen'] = $filename;
        }

        $auto->update($data);

        return redirect()->route('autos.show', $auto->id)
            ->with('success', 'Los datos del vehículo han sido actualizados.');
    }

    /**
     * Elimina una publicación.
     */
    public function destroy($id)
    {
        $auto = Auto::findOrFail($id);

        // Si ya está vendido o tiene ventas asociadas
        if ($auto->estado === 'vendido') {
            return back()->with('error', 'No puedes eliminar una publicación de un vehículo que ya fue vendido.');
        }

        if ($auto->imagen && File::exists(public_path('uploads/autos/' . $auto->imagen))) {
            File::delete(public_path('uploads/autos/' . $auto->imagen));
        }

        $auto->delete();

        return redirect()->route('autos.index')
            ->with('info', 'El vehículo ha sido eliminado del catálogo.');
    }
}
