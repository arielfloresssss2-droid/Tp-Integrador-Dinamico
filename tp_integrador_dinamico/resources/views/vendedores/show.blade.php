@extends('layouts.app')

@section('title', 'Perfil de Vendedor: ' . $vendedor->nombre_completo)

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('vendedores.index') }}" class="text-decoration-none text-muted">Vendedores</a></li>
            <li class="breadcrumb-item active text-dark fw-semibold">{{ $vendedor->nombre_completo }}</li>
        </ol>
    </nav>

    <!-- Header con Acciones -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger text-uppercase px-2 py-1">G2 &bull; Ficha de Vendedor</span>
                <span class="badge bg-light text-dark border font-monospace">Legajo: {{ $vendedor->legajo }}</span>
            </div>
            <h2 class="fw-bold text-dark mb-0 mt-1">{{ $vendedor->nombre_completo }}</h2>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('vendedores.edit', $vendedor->id) }}" class="btn btn-outline-dark">
                <i class="bi bi-pencil me-1"></i> Editar Datos
            </a>
            <a href="{{ route('ventas.create') }}?vendedor_id={{ $vendedor->id }}" class="btn btn-accent">
                <i class="bi bi-plus-circle me-1"></i> Registrar Venta con este Vendedor
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Columna Izquierda: Tarjeta de Datos del Vendedor -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 text-center">
                    <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center text-white fw-bold mb-3 shadow-sm {{ $vendedor->activo ? 'bg-danger' : 'bg-secondary' }}" style="width: 80px; height: 80px; font-size: 2rem;">
                        {{ strtoupper(substr($vendedor->nombre, 0, 1) . substr($vendedor->apellido, 0, 1)) }}
                    </div>

                    <h4 class="fw-bold text-dark mb-1">{{ $vendedor->nombre_completo }}</h4>
                    <span class="badge bg-light text-dark border px-3 py-1 font-monospace mb-3">
                        Legajo: {{ $vendedor->legajo }}
                    </span>

                    <div>
                        @if ($vendedor->activo)
                            <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-3 py-2 fs-6">
                                <i class="bi bi-check-circle-fill me-1"></i> Empleado Activo
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border px-3 py-2 fs-6">
                                <i class="bi bi-x-circle me-1"></i> Inactivo / De Baja
                            </span>
                        @endif
                    </div>

                    <hr class="my-4">

                    <div class="text-start">
                        <div class="mb-3">
                            <span class="small text-muted d-block">Comisión por operación:</span>
                            <strong class="fs-5 text-danger">{{ number_format($vendedor->comision, 1, ',', '.') }}%</strong>
                        </div>
                        <div class="mb-3">
                            <span class="small text-muted d-block">Fecha de ingreso a la concesionaria:</span>
                            <strong class="text-dark">
                                <i class="bi bi-calendar-check me-1 text-muted"></i>
                                {{ $vendedor->fecha_ingreso ? $vendedor->fecha_ingreso->format('d/m/Y') : 'N/D' }}
                            </strong>
                        </div>
                        <div>
                            <span class="small text-muted d-block">Antigüedad:</span>
                            <span class="text-dark">
                                {{ $vendedor->fecha_ingreso ? $vendedor->fecha_ingreso->diffForHumans(null, true) : 'N/D' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen Financiero del Vendedor -->
            <div class="card border-0 shadow-sm rounded-4 bg-dark text-white p-4">
                <h5 class="fw-bold text-white mb-3">
                    <i class="bi bi-cash-coin text-danger me-2"></i>Rendimiento
                </h5>
                <div class="mb-3">
                    <span class="small text-white-50 d-block">Total Facturado en Ventas:</span>
                    <h3 class="fw-bold text-white mb-0">$ {{ number_format($vendedor->total_ventas, 0, ',', '.') }}</h3>
                </div>
                <div class="border-top border-secondary border-opacity-50 pt-3">
                    <span class="small text-white-50 d-block">Comisiones Totales Acumuladas:</span>
                    <h4 class="fw-bold text-danger mb-0">$ {{ number_format($vendedor->total_comisiones, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Listado de Ventas Concretadas -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="bi bi-receipt text-danger me-2"></i>Historial de Ventas Concretadas
                    </h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 fw-bold">
                        {{ $vendedor->ventas->count() }} operaciones
                    </span>
                </div>

                @if ($ventas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-3">ID / Fecha</th>
                                    <th scope="col">Vehículo Vendido</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Precio Venta</th>
                                    <th scope="col">Comisión</th>
                                    <th scope="col" class="text-end pe-3">Detalle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ventas as $venta)
                                    <tr>
                                        <td class="ps-3">
                                            <span class="badge bg-light text-dark border">#{{ $venta->id }}</span>
                                            <div class="small text-muted mt-1">{{ $venta->fecha_venta ? $venta->fecha_venta->format('d/m/Y') : 'N/D' }}</div>
                                        </td>
                                        <td>
                                            @if ($venta->auto)
                                                <div class="fw-bold text-dark">{{ $venta->auto->modelo }}</div>
                                                <span class="small text-muted">{{ $venta->auto->marca->nombre ?? '' }} &bull; {{ $venta->auto->anio }}</span>
                                            @else
                                                <span class="text-muted fst-italic">Auto eliminado</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($venta->cliente)
                                                <div class="text-dark fw-semibold">{{ $venta->cliente->nombre_completo }}</div>
                                                <small class="text-muted">DNI: {{ $venta->cliente->dni ?: 'S/D' }}</small>
                                            @elseif ($venta->comprador)
                                                <div class="text-dark fw-semibold">{{ $venta->comprador->name }}</div>
                                                <small class="text-muted">{{ $venta->comprador->email }}</small>
                                            @else
                                                <span class="text-muted small">Consumidor final</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $venta->precio_final_formateado }}</span>
                                            <div class="small text-muted text-uppercase">{{ $venta->metodo_pago }}</div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-danger">$ {{ number_format($venta->comision_monto, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-sm btn-outline-dark" title="Ver comprobante de venta">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 border-top d-flex justify-content-center">
                        {{ $ventas->links() }}
                    </div>
                @else
                    <div class="card-body text-center py-5">
                        <i class="bi bi-cart-x fs-1 text-muted d-block mb-2"></i>
                        <h6 class="fw-bold text-dark mb-1">Sin ventas registradas todavía</h6>
                        <p class="text-muted small mb-3">Este vendedor aún no ha concretado operaciones en la concesionaria.</p>
                        <a href="{{ route('ventas.create') }}?vendedor_id={{ $vendedor->id }}" class="btn btn-sm btn-accent">
                            <i class="bi bi-plus-circle me-1"></i> Asignar primera venta
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
