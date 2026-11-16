@extends('layouts.app')

@section('title', 'Comprobante de Venta N° ' . str_pad($venta->id, 5, '0', STR_PAD_LEFT))

@section('styles')
<style>
    @media print {
        body { background: #fff !important; }
        nav, footer, .no-print { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        .container { max-width: 100% !important; padding: 0 !important; }
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Breadcrumb y Acciones Superiores -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4 no-print">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}" class="text-decoration-none text-muted">Ventas</a></li>
                <li class="breadcrumb-item active text-dark fw-semibold">Venta #{{ str_pad($venta->id, 5, '0', STR_PAD_LEFT) }}</li>
            </ol>
        </nav>
        <div class="d-flex gap-2">
            <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Listado de Ventas
            </a>
            <a href="{{ route('ventas.edit', $venta->id) }}" class="btn btn-outline-dark btn-sm">
                <i class="bi bi-pencil me-1"></i> Modificar Venta
            </a>
            <button onclick="window.print()" class="btn btn-accent btn-sm">
                <i class="bi bi-printer me-1"></i> Imprimir Comprobante
            </button>
        </div>
    </div>

    <!-- Comprobante / Factura Oficial -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-dark text-white p-4">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-truck-flatbed text-danger fs-3"></i>
                        <h4 class="fw-bold mb-0 text-white">PICK-UP CHACABUCO 2026</h4>
                    </div>
                    <p class="small text-light text-opacity-75 mb-0">Concesionaria Oficial de Camionetas y Autos Seleccionados</p>
                    <p class="small text-muted mb-0">G2 &bull; Alvarez - Flores Ariel &bull; Chacabuco, Prov. de Buenos Aires</p>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-danger text-uppercase px-3 py-2 mb-2">Comprobante de Venta</span>
                    <h3 class="fw-bold mb-0 text-white font-monospace">N° {{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</h3>
                    <span class="small text-light text-opacity-75 d-block">
                        Fecha: {{ $venta->fecha_venta ? $venta->fecha_venta->format('d/m/Y') : date('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body p-4 p-md-5">
            <!-- Bloque de Partes: Cliente y Vendedor -->
            <div class="row g-4 mb-4 pb-4 border-bottom">
                <!-- Cliente -->
                <div class="col-md-6 border-end-md">
                    <h6 class="text-danger fw-bold text-uppercase small mb-3">
                        <i class="bi bi-person-fill me-1"></i> Datos del Comprador
                    </h6>
                    @if ($venta->cliente)
                        <h5 class="fw-bold text-dark mb-1">{{ $venta->cliente->nombre_completo }}</h5>
                        <div class="text-muted small mb-1"><strong>DNI / CUIT:</strong> {{ $venta->cliente->dni ?: 'Consumidor Final' }}</div>
                        <div class="text-muted small mb-1"><strong>Teléfono:</strong> {{ $venta->cliente->telefono ?: 'No especificado' }}</div>
                        <div class="text-muted small mb-1"><strong>Email:</strong> {{ $venta->cliente->email ?: 'Sin email registrado' }}</div>
                        <div class="text-muted small"><strong>Dirección:</strong> {{ $venta->cliente->direccion ?: 'Chacabuco y zona' }}</div>
                    @elseif ($venta->comprador)
                        <h5 class="fw-bold text-dark mb-1">{{ $venta->comprador->name }}</h5>
                        <div class="text-muted small mb-1"><strong>Email:</strong> {{ $venta->comprador->email }}</div>
                    @else
                        <h5 class="fw-bold text-dark mb-1">Consumidor Final</h5>
                        <span class="text-muted small">Sin datos particulares</span>
                    @endif
                </div>

                <!-- Vendedor -->
                <div class="col-md-6 ps-md-4">
                    <h6 class="text-danger fw-bold text-uppercase small mb-3">
                        <i class="bi bi-person-badge-fill me-1"></i> Vendedor Asignado
                    </h6>
                    @if ($venta->vendedor)
                        <h5 class="fw-bold text-dark mb-1">{{ $venta->vendedor->nombre_completo }}</h5>
                        <div class="text-muted small mb-1"><strong>Legajo:</strong> {{ $venta->vendedor->legajo }}</div>
                        <div class="text-muted small mb-1"><strong>Porcentaje Comisión:</strong> {{ number_format($venta->vendedor->comision, 1, ',', '.') }}%</div>
                        <div class="text-muted small"><strong>Comisión Liquidada:</strong> $ {{ number_format($venta->comision_monto, 0, ',', '.') }}</div>
                    @else
                        <h5 class="fw-bold text-dark mb-1">Venta Directa de Concesionaria</h5>
                        <span class="text-muted small">Sin intermediación de vendedor externo</span>
                    @endif
                </div>
            </div>

            <!-- Detalle del Vehículo -->
            <h6 class="text-danger fw-bold text-uppercase small mb-3">
                <i class="bi bi-car-front-fill me-1"></i> Vehículo Objeto de la Operación
            </h6>

            @if ($venta->auto)
                <div class="card bg-light border-0 rounded-4 p-3 mb-4">
                    <div class="row align-items-center g-3">
                        <div class="col-md-3 text-center">
                            <img src="{{ $venta->auto->imagen_url }}" alt="{{ $venta->auto->modelo }}" class="img-fluid rounded-3 shadow-sm" style="max-height: 120px; object-fit: cover;">
                        </div>
                        <div class="col-md-9">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-dark text-white">{{ $venta->auto->marca->nombre ?? 'Auto' }}</span>
                                <span class="badge bg-secondary text-uppercase">{{ $venta->auto->tipo === 'nuevo' ? '0 Km' : 'Usado' }}</span>
                                <span class="badge bg-success">VENDIDO</span>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">{{ $venta->auto->modelo }}</h4>
                            <div class="row small text-muted g-2">
                                <div class="col-6 col-sm-3"><strong>Año:</strong> {{ $venta->auto->anio }}</div>
                                <div class="col-6 col-sm-3"><strong>Km:</strong> {{ $venta->auto->kilometraje_formateado }}</div>
                                <div class="col-6 col-sm-3"><strong>Combustible:</strong> {{ ucfirst($venta->auto->combustible) }}</div>
                                <div class="col-6 col-sm-3"><strong>Estado:</strong> Transferido</div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-secondary mb-4">El vehículo fue dado de baja del catálogo luego de la venta.</div>
            @endif

            <!-- Liquidación Económica -->
            <div class="row justify-content-end mb-4">
                <div class="col-md-6 col-lg-5">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <tr>
                                <td class="text-muted">Valor de Lista del Vehículo:</td>
                                <td class="text-end fw-semibold text-dark">
                                    {{ $venta->auto ? $venta->auto->precio_formateado : $venta->precio_final_formateado }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Método de Pago Acordado:</td>
                                <td class="text-end fw-bold text-uppercase text-secondary">
                                    {{ $venta->metodo_pago }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Gastos Administrativos:</td>
                                <td class="text-end text-success fw-semibold">Bonificado ($ 0)</td>
                            </tr>
                            <tr class="border-top border-dark">
                                <td class="fs-5 fw-bold text-dark">Total Abonado:</td>
                                <td class="fs-4 fw-bold text-danger text-end">{{ $venta->precio_final_formateado }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Cláusula y Firmas -->
            <div class="pt-4 border-top">
                <p class="small text-muted mb-5">
                    El presente comprobante certifica la formalización de la compra-venta del vehículo descripto ut supra, habiéndose pactado las condiciones de entrega, estado de conservación y transferencia de dominio según las normas comerciales vigentes en la República Argentina.
                </p>

                <div class="row text-center mt-5 pt-4">
                    <div class="col-6">
                        <div class="border-top border-dark mx-auto" style="width: 200px;"></div>
                        <span class="small text-muted fw-semibold d-block mt-1">Firma del Comprador</span>
                    </div>
                    <div class="col-6">
                        <div class="border-top border-dark mx-auto" style="width: 200px;"></div>
                        <span class="small text-muted fw-semibold d-block mt-1">Firma Concesionaria / Vendedor</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
