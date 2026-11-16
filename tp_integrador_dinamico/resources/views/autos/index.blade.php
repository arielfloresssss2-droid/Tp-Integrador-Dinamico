@extends('layouts.app')

@section('title', 'Catálogo de Pick-Ups y Autos')

@section('content')
<!-- Hero Section -->
<section class="bg-dark text-white py-5 position-relative overflow-hidden mb-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <span class="badge bg-danger text-uppercase px-3 py-2 mb-3 fw-bold">Concesionaria & Comunidad</span>
                <h1 class="display-5 fw-bold mb-3">Encontrá o publicá tu próxima camioneta o auto</h1>
                <p class="lead text-light text-opacity-75 mb-4">
                    Comprá y vendé vehículos nuevos y usados con total confianza en Chacabuco y la zona. Publicá gratis en minutos con foto y datos completos.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('autos.create') }}" class="btn btn-accent btn-lg px-4">
                        <i class="bi bi-plus-circle me-2"></i> Publicar mi Auto
                    </a>
                    <a href="{{ route('ventas.index') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-receipt me-2"></i> Gestión de Ventas
                    </a>
                    <a href="{{ route('vendedores.index') }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-people me-2"></i> Vendedores
                    </a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <img src="{{ asset('images/auto-placeholder.svg') }}" alt="Pick-Up" class="img-fluid rounded-4 shadow-lg border border-secondary border-opacity-25" style="max-height: 280px;">
            </div>
        </div>
    </div>
</section>

<!-- Catálogo y Filtros -->
<div class="container pb-5" id="catalogo">
    <div class="row">
        <!-- Sidebar de Filtros -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-lg-top" style="top: 90px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="bi bi-funnel me-1 text-danger"></i> Filtros
                        </h5>
                        @if (request()->hasAny(['search', 'marca', 'tipo', 'combustible', 'precio_min', 'precio_max', 'anio_min', 'anio_max', 'sort', 'estado']))
                            <a href="{{ route('autos.index') }}" class="small text-danger text-decoration-none fw-semibold">
                                Limpiar todo
                            </a>
                        @endif
                    </div>

                    <form action="{{ route('autos.index') }}" method="GET">
                        <!-- Buscador por texto -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Búsqueda rápida</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="Modelo, palabra..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <!-- Marca -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Marca</label>
                            <select name="marca" class="form-select">
                                <option value="">Todas las marcas</option>
                                @foreach ($marcas as $marca)
                                    <option value="{{ $marca->id }}" {{ request('marca') == $marca->id ? 'selected' : '' }}>
                                        {{ $marca->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Condición / Tipo -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Condición</label>
                            <select name="tipo" class="form-select">
                                <option value="">Todos (Nuevos y Usados)</option>
                                <option value="nuevo" {{ request('tipo') == 'nuevo' ? 'selected' : '' }}>0 Km (Nuevo)</option>
                                <option value="usado" {{ request('tipo') == 'usado' ? 'selected' : '' }}>Usado</option>
                            </select>
                        </div>

                        <!-- Combustible -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Combustible</label>
                            <select name="combustible" class="form-select">
                                <option value="">Todos los combustibles</option>
                                <option value="diesel" {{ request('combustible') == 'diesel' ? 'selected' : '' }}>Diésel</option>
                                <option value="nafta" {{ request('combustible') == 'nafta' ? 'selected' : '' }}>Nafta</option>
                                <option value="hibrido" {{ request('combustible') == 'hibrido' ? 'selected' : '' }}>Híbrido</option>
                                <option value="electrico" {{ request('combustible') == 'electrico' ? 'selected' : '' }}>Eléctrico</option>
                            </select>
                        </div>

                        <!-- Rango de Precios -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Precio ($)</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" name="precio_min" class="form-control form-control-sm" placeholder="Mínimo" value="{{ request('precio_min') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="precio_max" class="form-control form-control-sm" placeholder="Máximo" value="{{ request('precio_max') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Rango de Año -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Año</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" name="anio_min" class="form-control form-control-sm" placeholder="Desde" value="{{ request('anio_min') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="anio_max" class="form-control form-control-sm" placeholder="Hasta" value="{{ request('anio_max') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Disponibilidad</label>
                            <select name="estado" class="form-select form-select-sm">
                                <option value="disponible" {{ request('estado', 'disponible') == 'disponible' ? 'selected' : '' }}>Solo Disponibles</option>
                                <option value="vendido" {{ request('estado') == 'vendido' ? 'selected' : '' }}>Vendidos</option>
                                <option value="reservado" {{ request('estado') == 'reservado' ? 'selected' : '' }}>Reservados</option>
                            </select>
                        </div>

                        <!-- Ordenar Por -->
                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-secondary">Ordenar por</label>
                            <select name="sort" class="form-select form-select-sm">
                                <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Más recientes</option>
                                <option value="precio_asc" {{ request('sort') == 'precio_asc' ? 'selected' : '' }}>Menor precio</option>
                                <option value="precio_desc" {{ request('sort') == 'precio_desc' ? 'selected' : '' }}>Mayor precio</option>
                                <option value="anio_desc" {{ request('sort') == 'anio_desc' ? 'selected' : '' }}>Año más nuevo</option>
                                <option value="km_asc" {{ request('sort') == 'km_asc' ? 'selected' : '' }}>Menor kilometraje</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-dark-custom w-100 py-2">
                            <i class="bi bi-funnel-fill me-1"></i> Aplicar Filtros
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Listado de Autos -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-0">Vehículos en Venta</h3>
                    <p class="text-muted small mb-0">{{ $autos->total() }} vehículos encontrados</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('autos.create') }}" class="btn btn-accent btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Publicar Auto
                    </a>
                </div>
            </div>

            @if ($autos->count() > 0)
                <div class="row g-4">
                    @foreach ($autos as $auto)
                        <div class="col-md-6 col-xl-4">
                            <div class="card card-vehicle h-100">
                                <div class="vehicle-img-wrapper">
                                    <img src="{{ $auto->imagen_url }}" alt="{{ $auto->marca->nombre ?? 'Auto' }} {{ $auto->modelo }}">
                                    
                                    <!-- Badges -->
                                    <span class="badge badge-status {{ $auto->estado === 'disponible' ? 'bg-success' : ($auto->estado === 'vendido' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                        {{ $auto->estado }}
                                    </span>

                                    <span class="badge badge-type {{ $auto->tipo === 'nuevo' ? 'bg-info text-dark' : 'bg-secondary' }}">
                                        {{ $auto->tipo === 'nuevo' ? '0 Km' : 'Usado' }}
                                    </span>
                                </div>

                                <div class="card-body p-3 d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span class="badge bg-light text-dark border">{{ $auto->marca->nombre ?? 'Sin marca' }}</span>
                                        <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i>{{ $auto->anio }}</span>
                                    </div>

                                    <h5 class="card-title fw-bold text-dark mb-2 text-truncate" title="{{ $auto->modelo }}">
                                        {{ $auto->modelo }}
                                    </h5>

                                    <div class="d-flex gap-3 small text-muted mb-3">
                                        <span><i class="bi bi-speedometer2 me-1"></i>{{ $auto->kilometraje_formateado }}</span>
                                        <span><i class="bi bi-fuel-pump me-1"></i>{{ ucfirst($auto->combustible) }}</span>
                                    </div>

                                    <div class="mt-auto pt-2 border-top d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="small text-muted">Precio</div>
                                            <div class="fs-5 fw-bold text-danger">{{ $auto->precio_formateado }}</div>
                                        </div>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('autos.show', $auto->id) }}" class="btn btn-outline-dark btn-sm px-3">
                                                Ver Detalle
                                            </a>
                                            @if ($auto->estado === 'disponible' && Auth::id() !== $auto->user_id)
                                                <a href="{{ route('ventas.checkout', $auto->id) }}" class="btn btn-accent btn-sm" title="Comprar este vehículo">
                                                    <i class="bi bi-cart"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if ($auto->user)
                                    <div class="card-footer bg-light border-0 py-2 px-3 small text-muted d-flex align-items-center justify-content-between">
                                        <span><i class="bi bi-person me-1"></i>{{ $auto->user->name }}</span>
                                        <span class="text-muted">{{ $auto->created_at ? $auto->created_at->diffForHumans() : '' }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="mt-5 d-flex justify-content-center">
                    {{ $autos->links() }}
                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                    <div class="card-body">
                        <div class="d-inline-flex p-3 rounded-circle bg-light text-muted mb-3">
                            <i class="bi bi-car-front fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">No se encontraron vehículos</h4>
                        <p class="text-muted mb-4">No hay autos publicados que coincidan con los filtros seleccionados.</p>
                        <a href="{{ route('autos.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-arrow-clockwise me-1"></i> Restablecer filtros
                        </a>
                        <a href="{{ route('autos.create') }}" class="btn btn-accent">
                            <i class="bi bi-plus-circle me-1"></i> Sé el primero en publicar
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
