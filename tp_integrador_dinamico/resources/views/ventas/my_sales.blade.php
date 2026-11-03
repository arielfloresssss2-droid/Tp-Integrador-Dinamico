@extends('layouts.app')

@section('title', 'Mis Ventas - Pick-Up Chacabuco')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">
                <i class="bi bi-cash-stack text-danger me-2"></i>Mis Ventas Concretadas
            </h2>
            <p class="text-muted small mb-0">Historial de autos publicados por ti que ya fueron comprados por otros usuarios</p>
        </div>
        <a href="{{ route('autos.my') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-collection me-1"></i> Ver todas mis publicaciones
        </a>
    </div>

    @if ($ventas->count() > 0)
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 90px;">Foto</th>
                            <th scope="col">Vehículo Vendido</th>
                            <th scope="col">Comprador</th>
                            <th scope="col">Fecha de Venta</th>
                            <th scope="col">Método de Pago</th>
                            <th scope="col">Total Cobrado</th>
                            <th scope="col" class="text-end pe-4">Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ventas as $venta)
                            <tr>
                                <td>
                                    <img src="{{ $venta->auto->imagen_url ?? asset('images/auto-placeholder.svg') }}" alt="{{ $venta->auto->modelo ?? 'Auto' }}" class="rounded-3" style="width: 70px; height: 50px; object-fit: cover;">
                                </td>
                                <td>
                                    @if ($venta->auto)
                                        <span class="badge bg-light text-dark border small">{{ $venta->auto->marca->nombre ?? 'N/D' }}</span>
                                        <div class="fw-bold text-dark">{{ $venta->auto->modelo }}</div>
                                        <span class="small text-muted">{{ $venta->auto->anio }}</span>
                                    @else
                                        <span class="text-muted fst-italic">Vehículo eliminado</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $venta->comprador->name ?? 'Usuario comprador' }}</div>
                                    <span class="small text-muted">{{ $venta->comprador->email ?? '' }}</span>
                                </td>
                                <td>
                                    <i class="bi bi-calendar3 me-1 text-muted"></i>
                                    {{ $venta->fecha_venta ? $venta->fecha_venta->format('d/m/Y') : ($venta->created_at ? $venta->created_at->format('d/m/Y') : 'N/D') }}
                                </td>
                                <td>
                                    <span class="badge bg-secondary text-uppercase px-2 py-1">
                                        {{ $venta->metodo_pago }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success fs-6">{{ $venta->precio_final_formateado }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    @if ($venta->auto)
                                        <a href="{{ route('autos.show', $venta->auto->id) }}" class="btn btn-sm btn-outline-dark">
                                            <i class="bi bi-eye me-1"></i> Ver Auto
                                        </a>
                                    @endif
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
                    <i class="bi bi-cash-coin fs-1"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">Aún no registras ventas concretadas</h4>
                <p class="text-muted mb-4">Cuando un usuario compre una de tus publicaciones, aparecerá registrada en esta sección.</p>
                <a href="{{ route('autos.create') }}" class="btn btn-accent btn-lg px-4">
                    <i class="bi bi-plus-circle me-1"></i> Publicar un Vehículo
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
