@extends('layouts.app')

@section('title', 'Registrar Venta - G2: Alvarez - Flores Ariel')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}" class="text-decoration-none text-muted">Ventas</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold">Nueva Operación</li>
                </ol>
            </nav>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <span class="badge bg-danger text-uppercase px-2 py-1 mb-1">G2 &bull; Alta de Venta</span>
                    <h2 class="fw-bold text-dark mb-0">
                        <i class="bi bi-cart-plus text-danger me-2"></i>Registrar Nueva Venta
                    </h2>
                    <p class="text-muted small mb-0">Carga la operación comercial asignando el vehículo, el vendedor y el cliente</p>
                </div>
                <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver a Ventas
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger shadow-sm rounded-3 mb-4">
                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Por favor revisa los errores:</div>
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('ventas.store') }}" method="POST" id="formVenta">
                @csrf

                <!-- Sección 1: Vehículo y Vendedor -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                            <i class="bi bi-truck text-danger me-2"></i>1. Vehículo y Vendedor Asignado
                        </h5>

                        <div class="row g-3">
                            <!-- Vehículo -->
                            <div class="col-md-7">
                                <label for="id_auto" class="form-label fw-semibold small text-secondary">
                                    Vehículo a Vender (Disponibles) <span class="text-danger">*</span>
                                </label>
                                <select name="id_auto" id="id_auto" class="form-select @error('id_auto') is-invalid @enderror" required onchange="onAutoChange(this)">
                                    <option value="">-- Selecciona el vehículo disponible --</option>
                                    @foreach ($autosDisponibles as $auto)
                                        <option value="{{ $auto->id }}"
                                                data-precio="{{ $auto->precio }}"
                                                data-marca="{{ $auto->marca->nombre ?? '' }}"
                                                data-modelo="{{ $auto->modelo }}"
                                                data-anio="{{ $auto->anio }}"
                                                data-km="{{ $auto->kilometraje_formateado }}"
                                                data-img="{{ $auto->imagen_url }}"
                                                {{ old('id_auto', request('auto_id')) == $auto->id ? 'selected' : '' }}>
                                            {{ $auto->marca->nombre ?? 'Auto' }} {{ $auto->modelo }} ({{ $auto->anio }}) - {{ $auto->precio_formateado }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_auto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if ($autosDisponibles->count() === 0)
                                    <div class="alert alert-warning py-2 small mt-2 mb-0">
                                        <i class="bi bi-exclamation-triangle me-1"></i> No hay vehículos con estado "disponible". <a href="{{ route('autos.create') }}" class="fw-bold text-dark">Publicar un auto</a>
                                    </div>
                                @endif
                            </div>

                            <!-- Vendedor -->
                            <div class="col-md-5">
                                <label for="id_vendedor" class="form-label fw-semibold small text-secondary">
                                    Vendedor que cierra la operación <span class="text-danger">*</span>
                                </label>
                                <select name="id_vendedor" id="id_vendedor" class="form-select @error('id_vendedor') is-invalid @enderror">
                                    <option value="">-- Sin vendedor / Venta directa --</option>
                                    @foreach ($vendedores as $vend)
                                        <option value="{{ $vend->id }}"
                                                data-comision="{{ $vend->comision }}"
                                                {{ old('id_vendedor', request('vendedor_id')) == $vend->id ? 'selected' : '' }}>
                                            {{ $vend->nombre_completo }} (Legajo: {{ $vend->legajo }} - {{ $vend->comision }}%)
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_vendedor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Preview del Auto Seleccionado -->
                            <div class="col-12 d-none" id="autoPreviewBox">
                                <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-3">
                                    <img id="autoPreviewImg" src="#" alt="Auto" class="rounded-2" style="width: 80px; height: 55px; object-fit: cover;">
                                    <div>
                                        <div class="fw-bold text-dark fs-6" id="autoPreviewTitle"></div>
                                        <span class="small text-muted" id="autoPreviewDetails"></span>
                                    </div>
                                    <div class="ms-auto text-end">
                                        <span class="small text-muted d-block">Precio de lista:</span>
                                        <strong class="text-danger fs-5" id="autoPreviewPrice"></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección 2: Datos del Cliente -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                            <i class="bi bi-person text-danger me-2"></i>2. Datos del Cliente Comprador
                        </h5>

                        <!-- Selector tipo de cliente -->
                        <div class="mb-4">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cliente_tipo" id="clienteExistenteRadio" value="existente" {{ old('cliente_tipo', 'nuevo') === 'existente' ? 'checked' : '' }} onchange="toggleClienteTipo()">
                                <label class="form-check-label fw-semibold" for="clienteExistenteRadio">Cliente ya registrado</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="cliente_tipo" id="clienteNuevoRadio" value="nuevo" {{ old('cliente_tipo', 'nuevo') === 'nuevo' ? 'checked' : '' }} onchange="toggleClienteTipo()">
                                <label class="form-check-label fw-semibold" for="clienteNuevoRadio">Registrar nuevo cliente</label>
                            </div>
                        </div>

                        <!-- Opción 1: Cliente Existente -->
                        <div id="boxClienteExistente" class="{{ old('cliente_tipo', 'nuevo') === 'existente' ? '' : 'd-none' }} mb-3">
                            <label for="id_cliente" class="form-label fw-semibold small text-secondary">Seleccionar Cliente Existente</label>
                            <select name="id_cliente" id="id_cliente" class="form-select @error('id_cliente') is-invalid @enderror">
                                <option value="">-- Elige un cliente de la base de datos --</option>
                                @foreach ($clientes as $cli)
                                    <option value="{{ $cli->id }}" {{ old('id_cliente') == $cli->id ? 'selected' : '' }}>
                                        {{ $cli->nombre_completo }} (DNI: {{ $cli->dni ?: 'S/D' }} - {{ $cli->email ?: 'Sin email' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_cliente')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Opción 2: Formulario Nuevo Cliente -->
                        <div id="boxClienteNuevo" class="{{ old('cliente_tipo', 'nuevo') === 'nuevo' ? '' : 'd-none' }}">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="cliente_nombre" class="form-label fw-semibold small text-secondary">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" name="cliente_nombre" id="cliente_nombre" class="form-control @error('cliente_nombre') is-invalid @enderror" value="{{ old('cliente_nombre') }}" placeholder="Ej: Marcos">
                                    @error('cliente_nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="cliente_apellido" class="form-label fw-semibold small text-secondary">Apellido <span class="text-danger">*</span></label>
                                    <input type="text" name="cliente_apellido" id="cliente_apellido" class="form-control @error('cliente_apellido') is-invalid @enderror" value="{{ old('cliente_apellido') }}" placeholder="Ej: Benítez">
                                    @error('cliente_apellido')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="cliente_dni" class="form-label fw-semibold small text-secondary">DNI / CUIT</label>
                                    <input type="text" name="cliente_dni" id="cliente_dni" class="form-control" value="{{ old('cliente_dni') }}" placeholder="Ej: 34123456">
                                </div>
                                <div class="col-md-4">
                                    <label for="cliente_telefono" class="form-label fw-semibold small text-secondary">Teléfono</label>
                                    <input type="text" name="cliente_telefono" id="cliente_telefono" class="form-control" value="{{ old('cliente_telefono') }}" placeholder="Ej: 2352-441122">
                                </div>
                                <div class="col-md-4">
                                    <label for="cliente_email" class="form-label fw-semibold small text-secondary">Email</label>
                                    <input type="email" name="cliente_email" id="cliente_email" class="form-control" value="{{ old('cliente_email') }}" placeholder="cliente@correo.com">
                                </div>
                                <div class="col-12">
                                    <label for="cliente_direccion" class="form-label fw-semibold small text-secondary">Dirección / Localidad</label>
                                    <input type="text" name="cliente_direccion" id="cliente_direccion" class="form-control" value="{{ old('cliente_direccion') }}" placeholder="Ej: Pueyrredón 120, Chacabuco">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Condiciones Comerciales y Pago -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 p-md-5">
                        <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                            <i class="bi bi-cash-stack text-danger me-2"></i>3. Condiciones de la Operación y Pago
                        </h5>

                        <div class="row g-3">
                            <!-- Fecha de Venta -->
                            <div class="col-md-4">
                                <label for="fecha_venta" class="form-label fw-semibold small text-secondary">
                                    Fecha de la Venta <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="fecha_venta" id="fecha_venta" class="form-control @error('fecha_venta') is-invalid @enderror" value="{{ old('fecha_venta', date('Y-m-d')) }}" required>
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
                                    <option value="transferencia" {{ old('metodo_pago') === 'transferencia' ? 'selected' : '' }}>Transferencia Bancaria</option>
                                    <option value="efectivo" {{ old('metodo_pago') === 'efectivo' ? 'selected' : '' }}>Efectivo / Boleto Compra-Venta</option>
                                    <option value="tarjeta" {{ old('metodo_pago') === 'tarjeta' ? 'selected' : '' }}>Tarjeta / Financiación Prendaria</option>
                                </select>
                                @error('metodo_pago')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Precio Final -->
                            <div class="col-md-4">
                                <label for="precio_final" class="form-label fw-semibold small text-secondary">
                                    Precio Final Pactado ($ ARS) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="any" min="0" name="precio_final" id="precio_final" class="form-control @error('precio_final') is-invalid @enderror" value="{{ old('precio_final') }}" placeholder="0" required>
                                </div>
                                <div class="form-text small">Valor final con descuentos o adicionales incluidos.</div>
                                @error('precio_final')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-5">
                    <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary px-4 py-2">
                        Cancelar
                    </a>
                    <button type="submit" class="btn btn-accent btn-lg px-5 py-2 fw-bold">
                        <i class="bi bi-check2-circle me-1"></i> Concretar y Registrar Venta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function toggleClienteTipo() {
        const esExistente = document.getElementById('clienteExistenteRadio').checked;
        const boxExistente = document.getElementById('boxClienteExistente');
        const boxNuevo = document.getElementById('boxClienteNuevo');

        if (esExistente) {
            boxExistente.classList.remove('d-none');
            boxNuevo.classList.add('d-none');
        } else {
            boxExistente.classList.add('d-none');
            boxNuevo.classList.remove('d-none');
        }
    }

    function onAutoChange(select) {
        const option = select.options[select.selectedIndex];
        const previewBox = document.getElementById('autoPreviewBox');
        const precioInput = document.getElementById('precio_final');

        if (option && option.value) {
            const precio = option.getAttribute('data-precio');
            const marca = option.getAttribute('data-marca');
            const modelo = option.getAttribute('data-modelo');
            const anio = option.getAttribute('data-anio');
            const km = option.getAttribute('data-km');
            const img = option.getAttribute('data-img');

            if (!precioInput.value || precioInput.value == 0) {
                precioInput.value = precio;
            }

            document.getElementById('autoPreviewTitle').innerText = marca + ' ' + modelo;
            document.getElementById('autoPreviewDetails').innerText = 'Año: ' + anio + ' | ' + km;
            document.getElementById('autoPreviewPrice').innerText = '$ ' + Number(precio).toLocaleString('es-AR');
            document.getElementById('autoPreviewImg').src = img;

            previewBox.classList.remove('d-none');
        } else {
            previewBox.classList.add('d-none');
        }
    }

    // Ejecutar al cargar por si hay valor seleccionado
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('id_auto');
        if (select && select.value) {
            onAutoChange(select);
        }
    });
</script>
@endsection
