@extends('layouts.app')

@section('title', 'Alta de Vendedor - G2: Alvarez - Flores Ariel')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('vendedores.index') }}" class="text-decoration-none text-muted">Vendedores</a></li>
                    <li class="breadcrumb-item active text-dark fw-semibold">Nuevo Vendedor</li>
                </ol>
            </nav>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <span class="badge bg-danger text-uppercase px-2 py-1 mb-1">G2 &bull; Alta</span>
                    <h2 class="fw-bold text-dark mb-0">
                        <i class="bi bi-person-plus text-danger me-2"></i>Registrar Nuevo Vendedor
                    </h2>
                    <p class="text-muted small mb-0">Ingresa los datos del empleado para incorporarlo al equipo comercial</p>
                </div>
                <a href="{{ route('vendedores.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver al Listado
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

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('vendedores.store') }}" method="POST">
                        @csrf

                        <div class="row g-3 mb-4">
                            <!-- Nombre -->
                            <div class="col-md-6">
                                <label for="nombre" class="form-label fw-semibold small text-secondary">
                                    Nombre <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                    <input type="text" name="nombre" id="nombre" class="form-control border-start-0 @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}" placeholder="Ej: Ariel" required autofocus>
                                </div>
                                @error('nombre')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Apellido -->
                            <div class="col-md-6">
                                <label for="apellido" class="form-label fw-semibold small text-secondary">
                                    Apellido <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                    <input type="text" name="apellido" id="apellido" class="form-control border-start-0 @error('apellido') is-invalid @enderror" value="{{ old('apellido') }}" placeholder="Ej: Flores" required>
                                </div>
                                @error('apellido')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Legajo -->
                            <div class="col-md-6">
                                <label for="legajo" class="form-label fw-semibold small text-secondary">
                                    N° de Legajo / Código <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-card-text text-muted"></i></span>
                                    <input type="text" name="legajo" id="legajo" class="form-control border-start-0 @error('legajo') is-invalid @enderror" value="{{ old('legajo') }}" placeholder="Ej: VEND-001" required>
                                </div>
                                <div class="form-text small">Identificador único del vendedor dentro de la concesionaria.</div>
                                @error('legajo')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Comisión -->
                            <div class="col-md-6">
                                <label for="comision" class="form-label fw-semibold small text-secondary">
                                    Comisión por Venta (%) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" step="0.1" min="0" max="100" name="comision" id="comision" class="form-control @error('comision') is-invalid @enderror" value="{{ old('comision', 3.0) }}" placeholder="Ej: 3.5" required>
                                    <span class="input-group-text bg-light">%</span>
                                </div>
                                <div class="form-text small">Porcentaje sobre el valor final de cada vehículo vendido.</div>
                                @error('comision')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fecha de Ingreso -->
                            <div class="col-md-6">
                                <label for="fecha_ingreso" class="form-label fw-semibold small text-secondary">
                                    Fecha de Ingreso <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar-event text-muted"></i></span>
                                    <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control border-start-0 @error('fecha_ingreso') is-invalid @enderror" value="{{ old('fecha_ingreso', date('Y-m-d')) }}" required>
                                </div>
                                @error('fecha_ingreso')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Estado Activo -->
                            <div class="col-md-6 d-flex align-items-center pt-3">
                                <div class="form-check form-switch ps-5">
                                    <input class="form-check-input fs-5" type="checkbox" name="activo" id="activo" value="1" {{ old('activo', '1') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold text-dark ms-2" for="activo">
                                        Vendedor Activo
                                        <div class="small text-muted fw-normal">Habilitado para concretar nuevas ventas en el sistema</div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('vendedores.index') }}" class="btn btn-outline-secondary px-4 py-2">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-accent px-4 py-2 fw-bold">
                                <i class="bi bi-check-lg me-1"></i> Guardar Vendedor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
