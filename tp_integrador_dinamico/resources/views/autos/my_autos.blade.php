@extends('layouts.app')

@section('title', 'Mis Publicaciones - Pick-Up Chacabuco')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="bi bi-collection text-danger me-2"></i>Mis Publicaciones
            </h2>
            <p class="text-muted small mb-0">Administra los vehículos que has publicado para la venta</p>
        </div>
        <a href="{{ route('autos.create') }}" class="btn btn-accent">
            <i class="bi bi-plus-circle me-1"></i> Publicar Nuevo Vehículo
        </a>
    </div>

    @if ($autos->count() > 0)
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 90px;">Foto</th>
                            <th scope="col">Vehículo</th>
                            <th scope="col">Año / Km</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Estado</th>
                            <th scope="col" class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($autos as $auto)
                            <tr>
                                <td>
                                    <img src="{{ $auto->imagen_url }}" alt="{{ $auto->modelo }}" class="rounded-3" style="width: 70px; height: 50px; object-fit: cover;">
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border small">{{ $auto->marca->nombre ?? 'N/D' }}</span>
                                    <div class="fw-bold text-dark">{{ $auto->modelo }}</div>
                                    <span class="small text-muted">{{ ucfirst($auto->combustible) }} &bull; {{ $auto->tipo === 'nuevo' ? '0 Km' : 'Usado' }}</span>
                                </td>
                                <td>
                                    <div>{{ $auto->anio }}</div>
                                    <span class="small text-muted">{{ $auto->kilometraje_formateado }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-danger">{{ $auto->precio_formateado }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $auto->estado === 'disponible' ? 'bg-success' : ($auto->estado === 'vendido' ? 'bg-danger' : 'bg-warning text-dark') }} px-2 py-1">
                                        {{ ucfirst($auto->estado) }}
                                    </span>
                                    @if ($auto->estado === 'vendido' && $auto->ventas->count() > 0)
                                        <div class="small text-muted mt-1">
                                            Comprado por: <strong>{{ $auto->ventas->first()->comprador->name ?? 'Comprador' }}</strong>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('autos.show', $auto->id) }}" class="btn btn-outline-secondary" title="Ver en catálogo">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('autos.edit', $auto->id) }}" class="btn btn-outline-dark" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if ($auto->estado !== 'vendido')
                                            <form action="{{ route('autos.destroy', $auto->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar esta publicación?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-top d-flex justify-content-center">
                {{ $autos->links() }}
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <div class="card-body">
                <div class="d-inline-flex p-3 rounded-circle bg-danger bg-opacity-10 text-danger mb-3">
                    <i class="bi bi-truck-flatbed fs-1"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">No has publicado ningún vehículo todavía</h4>
                <p class="text-muted mb-4">¿Tienes una camioneta o auto que deseas vender? Publicarlo es rápido y fácil.</p>
                <a href="{{ route('autos.create') }}" class="btn btn-accent btn-lg px-4">
                    <i class="bi bi-plus-circle me-1"></i> Publicar mi primer auto
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
