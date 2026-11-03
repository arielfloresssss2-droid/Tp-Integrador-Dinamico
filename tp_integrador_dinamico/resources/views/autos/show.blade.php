@extends('layouts.app')

@section('title', ($auto->marca->nombre ?? 'Auto') . ' ' . $auto->modelo . ' - Pick-Up Chacabuco')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('autos.index') }}" class="text-decoration-none text-muted">Catálogo</a></li>
            <li class="breadcrumb-item text-muted">{{ $auto->marca->nombre ?? 'Marca' }}</li>
            <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ $auto->modelo }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Columna Izquierda: Imagen y Detalles -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="position-relative bg-dark" style="max-height: 480px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ $auto->imagen_url }}" alt="{{ $auto->modelo }}" class="img-fluid w-100" style="object-fit: cover; min-height: 350px; max-height: 480px;">
                    
                    <span class="badge position-absolute top-0 start-0 m-3 px-3 py-2 fs-6 {{ $auto->estado === 'disponible' ? 'bg-success' : ($auto->estado === 'vendido' ? 'bg-danger' : 'bg-warning text-dark') }}">
                        {{ strtoupper($auto->estado) }}
                    </span>

                    <span class="badge position-absolute top-0 end-0 m-3 px-3 py-2 fs-6 {{ $auto->tipo === 'nuevo' ? 'bg-info text-dark' : 'bg-secondary' }}">
                        {{ $auto->tipo === 'nuevo' ? '0 KM / NUEVO' : 'USADO' }}
                    </span>
                </div>

                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div>
                            <span class="badge bg-light text-dark border px-3 py-1 mb-2">{{ $auto->marca->nombre ?? 'Marca' }}</span>
                            <h2 class="fw-bold text-dark mb-1">{{ $auto->modelo }}</h2>
                            <p class="text-muted small mb-0">Publicado el {{ $auto->created_at ? $auto->created_at->format('d/m/Y') : 'recientemente' }}</p>
                        </div>
                        <div class="text-lg-end">
                            <span class="small text-muted d-block">Precio al contado</span>
                            <span class="display-6 fw-bold text-danger">{{ $auto->precio_formateado }}</span>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-card-checklist text-danger me-1"></i> Ficha Técnica
                    </h5>

                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <span class="small text-muted d-block">Año de Fabricación</span>
                                <strong class="fs-6 text-dark">{{ $auto->anio }}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <span class="small text-muted d-block">Kilometraje</span>
                                <strong class="fs-6 text-dark">{{ $auto->kilometraje_formateado }}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <span class="small text-muted d-block">Combustible</span>
                                <strong class="fs-6 text-dark">{{ ucfirst($auto->combustible) }}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <span class="small text-muted d-block">Condición</span>
                                <strong class="fs-6 text-dark">{{ $auto->tipo === 'nuevo' ? '0 Km (Nuevo)' : 'Usado' }}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <span class="small text-muted d-block">Marca</span>
                                <strong class="fs-6 text-dark">{{ $auto->marca->nombre ?? 'N/D' }}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <span class="small text-muted d-block">Estado</span>
                                <strong class="fs-6 text-{{ $auto->estado === 'disponible' ? 'success' : 'danger' }}">
                                    {{ ucfirst($auto->estado) }}
                                </strong>
                            </div>
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark mb-2">
                        <i class="bi bi-file-text text-danger me-1"></i> Descripción del Vendedor
                    </h5>
                    <div class="bg-light p-3 rounded-3 text-secondary" style="white-space: pre-line;">
                        {{ $auto->descripcion ?: 'El vendedor no proporcionó una descripción adicional para este vehículo.' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Tarjeta de Vendedor / Acciones de Compra -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-lg-top" style="top: 90px;">
                <div class="card-body p-4">
                    <!-- Datos del Vendedor -->
                    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                        <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-circle">
                            <i class="bi bi-person-badge fs-2"></i>
                        </div>
                        <div>
                            <span class="small text-muted d-block">Publicado por</span>
                            <h5 class="fw-bold mb-0 text-dark">{{ $auto->user->name ?? 'Vendedor Concesionaria' }}</h5>
                            <span class="small text-muted">{{ $auto->user->email ?? 'contacto@pickupchacabuco.com' }}</span>
                        </div>
                    </div>

                    <!-- Estado y Acción Principal -->
                    @if ($auto->estado === 'disponible')
                        @auth
                            @if (Auth::id() === $auto->user_id)
                                <div class="alert alert-secondary small mb-3">
                                    <i class="bi bi-info-circle me-1"></i> Esta es tu publicación. Puedes editar los detalles o cambiar su estado cuando desees.
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('autos.edit', $auto->id) }}" class="btn btn-outline-dark">
                                        <i class="bi bi-pencil me-1"></i> Editar Publicación
                                    </a>
                                    <form action="{{ route('autos.destroy', $auto->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta publicación?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger w-100">
                                            <i class="bi bi-trash me-1"></i> Eliminar Publicación
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="text-center mb-3">
                                    <span class="badge bg-success bg-opacity-10 text-success fs-6 px-3 py-2 w-100 mb-3">
                                        <i class="bi bi-check-circle me-1"></i> Listo para transferir
                                    </span>
                                </div>
                                <a href="{{ route('ventas.checkout', $auto->id) }}" class="btn btn-accent btn-lg w-100 py-3 fw-bold shadow-sm">
                                    <i class="bi bi-cart-check me-2"></i> Comprar este Vehículo
                                </a>
                                <p class="small text-muted text-center mt-2 mb-0">
                                    <i class="bi bi-shield-check text-success me-1"></i> Compra segura garantizada por Pick-Up Chacabuco
                                </p>
                            @endif
                        @else
                            <div class="alert alert-info small mb-3">
                                <i class="bi bi-lock me-1"></i> Inicia sesión para realizar la compra o contactar directamente al vendedor.
                            </div>
                            <a href="{{ route('login') }}" class="btn btn-dark-custom w-100 py-2 mb-2">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión para Comprar
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-secondary w-100 py-2">
                                Crear una Cuenta
                            </a>
                        @endauth
                    @elseif ($auto->estado === 'vendido')
                        <div class="alert alert-danger text-center p-3 mb-0">
                            <i class="bi bi-x-circle fs-3 d-block mb-1"></i>
                            <h5 class="fw-bold mb-1">Vehículo Vendido</h5>
                            <p class="small mb-0">Esta unidad ya fue adquirida y no se encuentra disponible.</p>
                        </div>
                    @else
                        <div class="alert alert-warning text-center p-3 mb-0">
                            <i class="bi bi-clock-history fs-3 d-block mb-1"></i>
                            <h5 class="fw-bold mb-1">Vehículo Reservado</h5>
                            <p class="small mb-0">Esta unidad se encuentra actualmente en proceso de reserva.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Vehículos Relacionados -->
    @if ($relacionados->count() > 0)
        <div class="mt-5 pt-4 border-top">
            <h4 class="fw-bold text-dark mb-4">Otros vehículos que podrían interesarte</h4>
            <div class="row g-4">
                @foreach ($relacionados as $rel)
                    <div class="col-md-4">
                        <div class="card card-vehicle h-100">
                            <div class="vehicle-img-wrapper" style="height: 180px;">
                                <img src="{{ $rel->imagen_url }}" alt="{{ $rel->modelo }}">
                            </div>
                            <div class="card-body p-3">
                                <span class="badge bg-light text-dark border small">{{ $rel->marca->nombre ?? '' }}</span>
                                <h6 class="fw-bold mt-1 mb-1">{{ $rel->modelo }}</h6>
                                <div class="text-danger fw-bold">{{ $rel->precio_formateado }}</div>
                                <a href="{{ route('autos.show', $rel->id) }}" class="btn btn-sm btn-outline-dark w-100 mt-2">
                                    Ver vehículo
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
