@extends('layouts.app')

@section('title', 'Finalizar Compra - ' . $auto->modelo)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('autos.index') }}" class="text-decoration-none text-muted">Catálogo</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('autos.show', $auto->id) }}" class="text-decoration-none text-muted">{{ $auto->modelo }}</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold">Confirmar Compra</li>
                </ol>
            </nav>

            <h2 class="fw-bold text-dark mb-4">
                <i class="bi bi-cart-check text-danger me-2"></i>Finalizar Compra
            </h2>

            @if ($errors->any())
                <div class="alert alert-danger shadow-sm rounded-3 mb-4">
                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Hubo un problema con los datos:</div>
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('ventas.comprar', $auto->id) }}" method="POST">
                @csrf
                <div class="row g-4">
                    <!-- Columna Izquierda: Datos del Comprador y Forma de Pago -->
                    <div class="col-lg-7">
                        <!-- Datos del Comprador -->
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="bi bi-person text-danger me-1"></i> Datos del Comprador
                                </h5>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-secondary">Nombre y Apellido</label>
                                        <input type="text" class="form-control bg-light" value="{{ Auth::user()->name }}" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-secondary">Correo Electrónico</label>
                                        <input type="email" class="form-control bg-light" value="{{ Auth::user()->email }}" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="dni" class="form-label small fw-semibold text-secondary">DNI / CUIT <span class="text-danger">*</span></label>
                                        <input type="text" name="dni" id="dni" class="form-control @error('dni') is-invalid @enderror" placeholder="Ej: 38450123" value="{{ old('dni') }}" pattern="[0-9]+" title="Solo números sin puntos ni guiones." maxlength="20" required>
                                        @error('dni')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="telefono" class="form-label small fw-semibold text-secondary">Teléfono de Contacto <span class="text-danger">*</span></label>
                                        <input type="tel" name="telefono" id="telefono" class="form-control @error('telefono') is-invalid @enderror" placeholder="Ej: 2352-456789" value="{{ old('telefono') }}" maxlength="30" required>
                                        @error('telefono')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="direccion" class="form-label small fw-semibold text-secondary">Dirección / Localidad <span class="text-danger">*</span></label>
                                        <input type="text" name="direccion" id="direccion" class="form-control @error('direccion') is-invalid @enderror" placeholder="Ej: Av. Alsina 250, Chacabuco" value="{{ old('direccion') }}" maxlength="100" required>
                                        @error('direccion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Forma de Pago -->
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="bi bi-credit-card text-danger me-1"></i> Forma de Pago
                                </h5>

                                <div class="form-check p-3 border rounded-3 mb-2 payment-option">
                                    <input class="form-check-input ms-0 me-3" type="radio" name="metodo_pago" id="pago_transferencia" value="transferencia" checked required>
                                    <label class="form-check-label w-100" for="pago_transferencia">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong class="text-dark d-block">Transferencia Bancaria</strong>
                                                <span class="small text-muted">Acreditación inmediata vía CBU o Alias bancario</span>
                                            </div>
                                            <i class="bi bi-bank fs-4 text-primary"></i>
                                        </div>
                                    </label>
                                </div>

                                <div class="form-check p-3 border rounded-3 mb-2 payment-option">
                                    <input class="form-check-input ms-0 me-3" type="radio" name="metodo_pago" id="pago_efectivo" value="efectivo" {{ old('metodo_pago') == 'efectivo' ? 'checked' : '' }} required>
                                    <label class="form-check-label w-100" for="pago_efectivo">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong class="text-dark d-block">Efectivo / Pago al Retirar</strong>
                                                <span class="small text-muted">Abonas al momento de revisar la unidad y firmar la transferencia</span>
                                            </div>
                                            <i class="bi bi-cash-stack fs-4 text-success"></i>
                                        </div>
                                    </label>
                                </div>

                                <div class="form-check p-3 border rounded-3 payment-option">
                                    <input class="form-check-input ms-0 me-3" type="radio" name="metodo_pago" id="pago_tarjeta" value="tarjeta" {{ old('metodo_pago') == 'tarjeta' ? 'checked' : '' }} required>
                                    <label class="form-check-label w-100" for="pago_tarjeta">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong class="text-dark d-block">Tarjeta / Financiación</strong>
                                                <span class="small text-muted">Planes de cuotas fijas o crédito prendario</span>
                                            </div>
                                            <i class="bi bi-credit-card-2-front fs-4 text-danger"></i>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Resumen del Pedido -->
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm rounded-4 sticky-lg-top" style="top: 90px;">
                            <div class="card-body p-4">
                                <h5 class="fw-bold text-dark mb-3">
                                    <i class="bi bi-receipt text-danger me-1"></i> Resumen de la Operación
                                </h5>

                                <div class="d-flex gap-3 align-items-center mb-3 pb-3 border-bottom">
                                    <img src="{{ $auto->imagen_url }}" alt="{{ $auto->modelo }}" class="rounded-3" style="width: 90px; height: 65px; object-fit: cover;">
                                    <div>
                                        <span class="badge bg-light text-dark border small">{{ $auto->marca->nombre ?? 'Marca' }}</span>
                                        <h6 class="fw-bold mb-0 text-dark">{{ $auto->modelo }}</h6>
                                        <span class="small text-muted">{{ $auto->anio }} &bull; {{ $auto->kilometraje_formateado }}</span>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Valor del vehículo:</span>
                                    <span class="fw-bold text-dark">{{ $auto->precio_formateado }}</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Gastos de reserva:</span>
                                    <span class="text-success small fw-semibold">Bonificado $ 0</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-3 border-top mb-4">
                                    <span class="fw-bold text-dark fs-5">Total a Pagar:</span>
                                    <span class="display-6 fw-bold text-danger">{{ $auto->precio_formateado }}</span>
                                </div>

                                <div class="alert alert-light border small text-muted mb-4">
                                    <i class="bi bi-shield-check text-success me-1"></i> Al confirmar, el vehículo quedará registrado a tu nombre como vendido y se generará tu comprobante de compra.
                                </div>

                                <button type="submit" class="btn btn-accent btn-lg w-100 py-3 fw-bold shadow-sm mb-2">
                                    <i class="bi bi-bag-check me-2"></i> Confirmar y Comprar
                                </button>

                                <a href="{{ route('autos.show', $auto->id) }}" class="btn btn-outline-secondary w-100">
                                    Cancelar y Volver
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
