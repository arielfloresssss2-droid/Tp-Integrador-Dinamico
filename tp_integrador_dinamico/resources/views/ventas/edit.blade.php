@extends('layouts.app')

@section('title', 'Modificar Venta N° ' . $venta->id)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}" class="text-decoration-none text-muted">Ventas</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('ventas.show', $venta->id) }}" class="text-decoration-none text-muted">Venta #{{ $venta->id }}</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold">Editar</li>
                </ol>
            </nav>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <span class="badge bg-warning text-dark text-uppercase px-2 py-1 mb-1">G2 &bull; Modificación de Venta</span>
                    <h2 class="fw-bold text-dark mb-0">
                        <i class="bi bi-pencil-square text-danger me-2"></i>Modificar Venta N° {{ $venta->id }}
                    </h2>
                    <p class="text-muted small mb-0">Edición de condiciones, vendedor asignado, cliente y precio pactado</p>
                </div>
                <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver a la Factura
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger shadow-sm rounded-3 mb-4">
                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Errores de validación:</div>
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 p-md-5">
                    <!-- Resumen del Vehículo Vendido (Solo lectura) -->
                    @if ($venta->auto)
                        <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-3 mb-4">
                            <img src="{{ $venta->auto->imagen_url }}" alt="{{ $venta->auto->modelo }}" class="rounded-2" style="width: 70px; height: 50px; object-fit: cover;">
                            <div>
                                <span class="badge bg-dark text-white small">{{ $venta->auto->marca->nombre ?? '' }}</span>
                                <h6 class="fw-bold text-dark mb-0">{{ $venta->auto->modelo }}</h6>
                                <span class="small text-muted">{{ $venta->auto->anio }} &bull; {{ $venta->auto->kilometraje_formateado }}</span>
                            </div>
                            <div class="ms-auto text-end">
                                <span class="badge bg-success">Vehículo Vendido</span>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('ventas.update', $venta->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-4">
                            <!-- Vendedor Asignado -->
                            <div class="col-md-6">
                                <label for="id_vendedor" class="form-label fw-semibold small text-secondary">
                                    Vendedor Asignado
                                </label>
                                <select name="id_vendedor" id="id_vendedor" class="form-select @error('id_vendedor') is-invalid @enderror">
                                    <option value="">-- Sin vendedor asignado --</option>
                                    @foreach ($vendedores as $vend)
                                        <option value="{{ $vend->id }}" {{ old('id_vendedor', $venta->id_vendedor) == $vend->id ? 'selected' : '' }}>
                                            {{ $vend->nombre_completo }} ({{ $vend->comision }}%)
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_vendedor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Cliente Comprador -->
                            <div class="col-md-6">
                                <label for="id_cliente" class="form-label fw-semibold small text-secondary">
                                    Cliente Comprador
                                </label>
                                <select name="id_cliente" id="id_cliente" class="form-select @error('id_cliente') is-invalid @enderror">
                                    <option value="">-- Consumidor Final / Sin cliente asociado --</option>
                                    @foreach ($clientes as $cli)
                                        <option value="{{ $cli->id }}" {{ old('id_cliente', $venta->id_cliente) == $cli->id ? 'selected' : '' }}>
                                            {{ $cli->nombre_completo }} (DNI: {{ $cli->dni ?: 'S/D' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_cliente')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fecha de Venta -->
                            <div class="col-md-4">
                                <label for="fecha_venta" class="form-label fw-semibold small text-secondary">
                                    Fecha de la Venta <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="fecha_venta" id="fecha_venta" class="form-control @error('fecha_venta') is-invalid @enderror" value="{{ old('fecha_venta', $venta->fecha_venta ? $venta->fecha_venta->format('Y-m-d') : '') }}" required>
                                @error('fecha_venta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Método de Pago -->
                            <div class="col-md-4">
                                <label for="metodo_pago" class="form-label fw-semibold small text-secondary">
                                    Método de Pago <span class="text-danger">*</span>
                                </label>
                                <select name="metodo_pago" id="metodo_pago" class="form-select @error('metodo_pago') is-invalid @enderror" required>
                                    <option value="transferencia" {{ old('metodo_pago', $venta->metodo_pago) === 'transferencia' ? 'selected' : '' }}>Transferencia Bancaria</option>
                                    <option value="efectivo" {{ old('metodo_pago', $venta->metodo_pago) === 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                                    <option value="tarjeta" {{ old('metodo_pago', $venta->metodo_pago) === 'tarjeta' ? 'selected' : '' }}>Tarjeta / Financiación</option>
                                </select>
                                @error('metodo_pago')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Precio Final -->
                            <div class="col-md-4">
                                <label for="precio_final" class="form-label fw-semibold small text-secondary">
                                    Precio Final ($ ARS) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="any" min="0" name="precio_final" id="precio_final" class="form-control @error('precio_final') is-invalid @enderror" value="{{ old('precio_final', $venta->precio_final) }}" required>
                                </div>
                                @error('precio_final')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-outline-secondary px-4 py-2">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-accent px-4 py-2 fw-bold">
                                <i class="bi bi-check-lg me-1"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
