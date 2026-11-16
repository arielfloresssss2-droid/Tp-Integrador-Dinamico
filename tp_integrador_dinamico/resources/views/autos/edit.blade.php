@extends('layouts.app')

@section('title', 'Editar Publicación - ' . $auto->modelo)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">
                        <i class="bi bi-pencil-square text-danger me-2"></i>Editar Publicación
                    </h2>
                    <p class="text-muted small mb-0">Modifica los datos o estado de tu vehículo {{ $auto->modelo }}</p>
                </div>
                <a href="{{ route('autos.my') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver a mis publicaciones
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

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('autos.update', $auto->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Sección 1: Datos Principales -->
                        <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                            <i class="bi bi-info-circle text-danger me-1"></i> Información General
                        </h5>

                        <div class="row g-3 mb-4">
                            <!-- Marca -->
                            <div class="col-md-6">
                                <label for="id_marca" class="form-label fw-semibold small text-secondary">
                                    Marca <span class="text-danger">*</span>
                                </label>
                                <select name="id_marca" id="id_marca" class="form-select @error('id_marca') is-invalid @enderror" required>
                                    @foreach ($marcas as $marca)
                                        <option value="{{ $marca->id }}" {{ old('id_marca', $auto->id_marca) == $marca->id ? 'selected' : '' }}>
                                            {{ $marca->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_marca')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Modelo -->
                            <div class="col-md-6">
                                <label for="modelo" class="form-label fw-semibold small text-secondary">
                                    Modelo y Versión <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="modelo" id="modelo" class="form-control @error('modelo') is-invalid @enderror" value="{{ old('modelo', $auto->modelo) }}" maxlength="100" required>
                                @error('modelo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Año -->
                            <div class="col-md-4">
                                <label for="anio" class="form-label fw-semibold small text-secondary">
                                    Año <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="anio" id="anio" class="form-control @error('anio') is-invalid @enderror" value="{{ old('anio', $auto->anio) }}" min="1950" max="{{ date('Y') + 1 }}" required>
                                @error('anio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Precio -->
                            <div class="col-md-4">
                                <label for="precio" class="form-label fw-semibold small text-secondary">
                                    Precio ($ ARS) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="any" min="0" name="precio" id="precio" class="form-control @error('precio') is-invalid @enderror" value="{{ old('precio', $auto->precio) }}" required>
                                </div>
                                @error('precio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kilometraje -->
                            <div class="col-md-4">
                                <label for="kilometraje" class="form-label fw-semibold small text-secondary">
                                    Kilometraje <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" name="kilometraje" id="kilometraje" class="form-control @error('kilometraje') is-invalid @enderror" value="{{ old('kilometraje', $auto->kilometraje) }}" min="0" required>
                                    <span class="input-group-text">km</span>
                                </div>
                                @error('kilometraje')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Sección 2: Especificaciones y Estado -->
                        <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                            <i class="bi bi-gear text-danger me-1"></i> Estado y Mecánica
                        </h5>

                        <div class="row g-3 mb-4">
                            <!-- Estado -->
                            <div class="col-md-4">
                                <label for="estado" class="form-label fw-semibold small text-secondary">
                                    Estado de la publicación <span class="text-danger">*</span>
                                </label>
                                <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror" required>
                                    <option value="disponible" {{ old('estado', $auto->estado) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                                    <option value="reservado" {{ old('estado', $auto->estado) == 'reservado' ? 'selected' : '' }}>Reservado</option>
                                    <option value="vendido" {{ old('estado', $auto->estado) == 'vendido' ? 'selected' : '' }}>Vendido</option>
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Condición -->
                            <div class="col-md-4">
                                <label for="tipo" class="form-label fw-semibold small text-secondary">
                                    Condición <span class="text-danger">*</span>
                                </label>
                                <select name="tipo" id="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                                    <option value="usado" {{ old('tipo', $auto->tipo) == 'usado' ? 'selected' : '' }}>Usado</option>
                                    <option value="nuevo" {{ old('tipo', $auto->tipo) == 'nuevo' ? 'selected' : '' }}>Nuevo (0 Km)</option>
                                </select>
                            </div>

                            <!-- Combustible -->
                            <div class="col-md-4">
                                <label for="combustible" class="form-label fw-semibold small text-secondary">
                                    Combustible <span class="text-danger">*</span>
                                </label>
                                <select name="combustible" id="combustible" class="form-select @error('combustible') is-invalid @enderror" required>
                                    <option value="nafta" {{ old('combustible', $auto->combustible) == 'nafta' ? 'selected' : '' }}>Nafta</option>
                                    <option value="diesel" {{ old('combustible', $auto->combustible) == 'diesel' ? 'selected' : '' }}>Diésel</option>
                                    <option value="hibrido" {{ old('combustible', $auto->combustible) == 'hibrido' ? 'selected' : '' }}>Híbrido</option>
                                    <option value="electrico" {{ old('combustible', $auto->combustible) == 'electrico' ? 'selected' : '' }}>Eléctrico</option>
                                </select>
                            </div>
                        </div>

                        <!-- Sección 3: Imagen -->
                        <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                            <i class="bi bi-camera text-danger me-1"></i> Fotografía
                        </h5>

                        <div class="mb-4">
                            @if ($auto->imagen)
                                <div class="mb-3">
                                    <span class="small text-muted d-block mb-1">Foto actual:</span>
                                    <img src="{{ $auto->imagen_url }}" alt="{{ $auto->modelo }}" class="img-thumbnail" style="max-height: 180px;">
                                </div>
                            @endif

                            <label for="imagen" class="form-label fw-semibold small text-secondary">
                                Reemplazar imagen (opcional)
                            </label>
                            <input type="file" name="imagen" id="imagen" class="form-control @error('imagen') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/webp">
                            <div class="form-text small">Deja este campo vacío si deseas mantener la foto actual. Formatos: JPG, PNG, WEBP (máx. 5 MB).</div>
                            @error('imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sección 4: Descripción -->
                        <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                            <i class="bi bi-card-text text-danger me-1"></i> Descripción
                        </h5>

                        <div class="mb-4">
                            <textarea name="descripcion" id="descripcion" rows="4" class="form-control @error('descripcion') is-invalid @enderror" maxlength="2000">{{ old('descripcion', $auto->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('autos.my') }}" class="btn btn-outline-secondary px-4 py-2">Cancelar</a>
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
