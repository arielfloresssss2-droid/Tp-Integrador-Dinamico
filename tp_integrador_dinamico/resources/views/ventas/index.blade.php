@extends('layouts.app')

@section('title', 'Gestión de Ventas - Pick-Up Chacabuco')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger text-uppercase px-2 py-1">G2 &bull; CRUD Venta</span>
                <span class="text-muted small">Módulo de Concesionaria</span>
            </div>
            <h2 class="fw-bold text-dark mb-0 mt-1">
                <i class="bi bi-receipt text-danger me-2"></i>Libro de Ventas y Facturación
            </h2>
            <p class="text-muted small mb-0">Registro histórico de transacciones, liquidaciones y asignaciones a vendedores</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('vendedores.index') }}" class="btn btn-outline-dark">
                <i class="bi bi-people me-1"></i> Ir a Vendedores
            </a>
            <a href="{{ route('ventas.create') }}" class="btn btn-accent">
                <i class="bi bi-cart-plus-fill me-1"></i> Registrar Nueva Venta
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-3 fs-3">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Total Facturado</div>
                        <h4 class="fw-bold mb-0 text-success">$ {{ number_format($totalVentasMonto, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3 fs-3">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Operaciones Concretadas</div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $cantidadOperaciones }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-info bg-opacity-10 text-info p-3 rounded-3 fs-3">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Ticket Promedio</div>
                        <h4 class="fw-bold mb-0 text-dark">$ {{ number_format($promedioVenta, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-3 fs-3">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Comisiones a Vendedores</div>
                        <h4 class="fw-bold mb-0 text-danger">$ {{ number_format($totalComisiones, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="{{ route('ventas.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar vehículo, cliente..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="vendedor_id" class="form-select">
                        <option value="">Todos los Vendedores</option>
                        @foreach ($vendedores as $vend)
                            <option value="{{ $vend->id }}" {{ request('vendedor_id') == $vend->id ? 'selected' : '' }}>
                                {{ $vend->nombre_completo }} ({{ $vend->legajo }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="metodo_pago" class="form-select">
                        <option value="">Medio de Pago</option>
                        <option value="transferencia" {{ request('metodo_pago') === 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                        <option value="efectivo" {{ request('metodo_pago') === 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                        <option value="tarjeta" {{ request('metodo_pago') === 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="fecha_desc" {{ request('sort') == 'fecha_desc' || !request('sort') ? 'selected' : '' }}>Fecha: Más recientes</option>
                        <option value="fecha_asc" {{ request('sort') == 'fecha_asc' ? 'selected' : '' }}>Fecha: Más antiguas</option>
                        <option value="precio_desc" {{ request('sort') == 'precio_desc' ? 'selected' : '' }}>Precio: Mayor a menor</option>
                        <option value="precio_asc" {{ request('sort') == 'precio_asc' ? 'selected' : '' }}>Precio: Menor a mayor</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="input-group input-group-sm">
                        <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}" title="Desde">
                        <span class="input-group-text bg-light">a</span>
                        <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}" title="Hasta">
                    </div>
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-dark-custom w-100" title="Filtrar">
                        <i class="bi bi-funnel-fill"></i>
                    </button>
                    @if (request()->hasAny(['search', 'vendedor_id', 'metodo_pago', 'fecha_desde', 'fecha_hasta', 'sort']))
                        <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary" title="Limpiar filtros">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if ($ventas->count() > 0)
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-4">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => request('sort') === 'fecha_asc' ? 'fecha_desc' : 'fecha_asc']) }}" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1" title="Ordenar por fecha">
                                    N° Venta / Fecha
                                    <i class="bi {{ request('sort') === 'fecha_asc' ? 'bi-sort-up text-danger' : (request('sort') === 'fecha_desc' ? 'bi-sort-down text-danger' : 'bi-arrow-down-up text-muted small') }}"></i>
                                </a>
                            </th>
                            <th scope="col">Vehículo</th>
                            <th scope="col">Vendedor Asignado</th>
                            <th scope="col">Cliente / Comprador</th>
                            <th scope="col">Medio de Pago</th>
                            <th scope="col">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => request('sort') === 'precio_desc' ? 'precio_asc' : 'precio_desc']) }}" class="text-decoration-none text-dark d-inline-flex align-items-center gap-1" title="Ordenar por precio">
                                    Precio Final
                                    <i class="bi {{ request('sort') === 'precio_desc' ? 'bi-sort-numeric-down-alt text-danger' : (request('sort') === 'precio_asc' ? 'bi-sort-numeric-up-alt text-danger' : 'bi-arrow-down-up text-muted small') }}"></i>
                                </a>
                            </th>
                            <th scope="col" class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ventas as $venta)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark fs-6">#{{ str_pad($venta->id, 5, '0', STR_PAD_LEFT) }}</div>
                                    <span class="small text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $venta->fecha_venta ? $venta->fecha_venta->format('d/m/Y') : 'N/D' }}
                                    </span>
                                </td>
                                <td>
                                    @if ($venta->auto)
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $venta->auto->imagen_url }}" alt="{{ $venta->auto->modelo }}" class="rounded-2" style="width: 50px; height: 35px; object-fit: cover;">
                                            <div>
                                                <div class="fw-bold text-dark text-truncate" style="max-width: 180px;">{{ $venta->auto->modelo }}</div>
                                                <small class="text-muted">{{ $venta->auto->marca->nombre ?? 'N/D' }} &bull; {{ $venta->auto->anio }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">Vehículo eliminado</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($venta->vendedor)
                                        <div class="fw-semibold text-dark">{{ $venta->vendedor->nombre_completo }}</div>
                                        <span class="badge bg-light text-dark border font-monospace small">Legajo: {{ $venta->vendedor->legajo }}</span>
                                        <div class="small text-danger fw-semibold mt-1">Comisión: $ {{ number_format($venta->comision_monto, 0, ',', '.') }}</div>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border">Sin vendedor asignado</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($venta->cliente)
                                        <div class="fw-semibold text-dark">{{ $venta->cliente->nombre_completo }}</div>
                                        <small class="text-muted">DNI: {{ $venta->cliente->dni ?: 'S/D' }}</small>
                                        @if ($venta->cliente->telefono)
                                            <div class="small text-muted"><i class="bi bi-telephone me-1"></i>{{ $venta->cliente->telefono }}</div>
                                        @endif
                                    @elseif ($venta->comprador)
                                        <div class="fw-semibold text-dark">{{ $venta->comprador->name }}</div>
                                        <small class="text-muted">{{ $venta->comprador->email }}</small>
                                    @else
                                        <span class="text-muted small">Consumidor final</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($venta->metodo_pago === 'transferencia')
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 text-uppercase">
                                            <i class="bi bi-bank me-1"></i> Transferencia
                                        </span>
                                    @elseif ($venta->metodo_pago === 'efectivo')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 text-uppercase">
                                            <i class="bi bi-cash-stack me-1"></i> Efectivo
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-2 py-1 text-uppercase">
                                            <i class="bi bi-credit-card me-1"></i> Tarjeta
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-success fs-6">{{ $venta->precio_final_formateado }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-outline-secondary" title="Ver comprobante de venta">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-outline-dark" title="Editar venta">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('ventas.destroy', $venta->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas anular/eliminar la Venta N° {{ $venta->id }}? El vehículo asociado volverá a estar disponible.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar / Anular venta">
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
                {{ $ventas->links() }}
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4 text-center py-5">
            <div class="card-body">
                <div class="d-inline-flex p-3 rounded-circle bg-light text-muted mb-3">
                    <i class="bi bi-receipt-cutoff fs-1"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1">No se encontraron ventas registradas</h4>
                <p class="text-muted mb-4">No hay operaciones que coincidan con los filtros aplicados o aún no has registrado ventas.</p>
                <a href="{{ route('ventas.create') }}" class="btn btn-accent px-4 py-2">
                    <i class="bi bi-cart-plus-fill me-1"></i> Registrar Primera Venta
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
