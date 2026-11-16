@extends('layouts.app')

@section('title', 'Gestión de Vendedores - Pick-Up Chacabuco')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger text-uppercase px-2 py-1">G2 &bull; CRUD Vendedor</span>
                <span class="text-muted small">Módulo de Concesionaria</span>
            </div>
            <h2 class="fw-bold text-dark mb-0 mt-1">
                <i class="bi bi-people-fill text-danger me-2"></i>Vendedores del Concesionario
            </h2>
            <p class="text-muted small mb-0">Alta, baja, modificación y seguimiento del equipo de ventas</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('ventas.index') }}" class="btn btn-outline-dark">
                <i class="bi bi-receipt me-1"></i> Ir a Ventas
            </a>
            <a href="{{ route('vendedores.create') }}" class="btn btn-accent">
                <i class="bi bi-person-plus-fill me-1"></i> Nuevo Vendedor
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3 fs-3">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Total de Vendedores</div>
                        <h3 class="fw-bold mb-0 text-dark">{{ $totalVendedores }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-3 fs-3">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Vendedores Activos</div>
                        <h3 class="fw-bold mb-0 text-success">{{ $totalActivos }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-secondary bg-opacity-10 text-secondary p-3 rounded-3 fs-3">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Inactivos / Bajas</div>
                        <h3 class="fw-bold mb-0 text-secondary">{{ $totalInactivos }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('vendedores.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar por nombre, apellido o legajo..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Solo Activos</option>
                        <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Solo Inactivos</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="sort" class="form-select">
                        <option value="">Ordenar: Apellido (A-Z)</option>
                        <option value="ventas_desc" {{ request('sort') === 'ventas_desc' ? 'selected' : '' }}>Más ventas cerradas</option>
                        <option value="recientes" {{ request('sort') === 'recientes' ? 'selected' : '' }}>Ingreso más reciente</option>
                        <option value="antiguos" {{ request('sort') === 'antiguos' ? 'selected' : '' }}>Mayor antigüedad</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-dark-custom w-100" title="Filtrar">
                        <i class="bi bi-funnel-fill"></i>
                    </button>
                    @if (request()->hasAny(['search', 'estado', 'sort']))
                        <a href="{{ route('vendedores.index') }}" class="btn btn-outline-secondary" title="Limpiar filtros">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if ($vendedores->count() > 0)
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-4">Vendedor</th>
                            <th scope="col">Legajo</th>
                            <th scope="col">Comisión</th>
                            <th scope="col">Fecha Ingreso</th>
                            <th scope="col">Ventas</th>
                            <th scope="col">Estado</th>
                            <th scope="col" class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendedores as $vendedor)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold {{ $vendedor->activo ? 'bg-danger' : 'bg-secondary' }}" style="width: 42px; height: 42px; font-size: 0.95rem;">
                                            {{ strtoupper(substr($vendedor->nombre, 0, 1) . substr($vendedor->apellido, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-6">{{ $vendedor->nombre_completo }}</div>
                                            <small class="text-muted">ID: #{{ $vendedor->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2 py-1 font-monospace">
                                        {{ $vendedor->legajo }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold text-danger">{{ number_format($vendedor->comision, 1, ',', '.') }}%</span>
                                </td>
                                <td>
                                    <span class="small text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $vendedor->fecha_ingreso ? $vendedor->fecha_ingreso->format('d/m/Y') : 'N/D' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 fw-bold">
                                        {{ $vendedor->ventas_count }} operac.
                                    </span>
                                </td>
                                <td>
                                    @if ($vendedor->activo)
                                        <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-2 py-1">
                                            <i class="bi bi-check-circle-fill me-1"></i> Activo
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                                            <i class="bi bi-x-circle me-1"></i> Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('vendedores.show', $vendedor->id) }}" class="btn btn-outline-secondary" title="Ver perfil y ventas">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('vendedores.edit', $vendedor->id) }}" class="btn btn-outline-dark" title="Editar vendedor">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('vendedores.toggle', $vendedor->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-{{ $vendedor->activo ? 'warning' : 'success' }}" title="{{ $vendedor->activo ? 'Desactivar' : 'Activar' }}">
                                                <i class="bi bi-power"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('vendedores.destroy', $vendedor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar al vendedor {{ $vendedor->nombre_completo }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar vendedor">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-top d-flex justify-content-center">
                {{ $vendedores->links() }}
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <div class="card-body">
                <div class="d-inline-flex p-3 rounded-circle bg-light text-muted mb-3">
                    <i class="bi bi-person-x fs-1"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1">No se encontraron vendedores</h4>
                <p class="text-muted mb-4">No hay registros que coincidan con la búsqueda o todavía no has cargado vendedores.</p>
                <a href="{{ route('vendedores.create') }}" class="btn btn-accent px-4 py-2">
                    <i class="bi bi-person-plus-fill me-1"></i> Registrar Primer Vendedor
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
