@extends('layouts.app')

@section('title', 'Publicar un Auto - Pick-Up Chacabuco')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">
                        <i class="bi bi-plus-circle text-danger me-2"></i>Publicar Vehículo
                    </h2>
                    <p class="text-muted small mb-0">Completa los datos de tu camioneta o auto para que esté disponible en el catálogo</p>
                </div>
                <a href="{{ route('autos.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver al Catálogo
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger shadow-sm rounded-3 mb-4">
                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Por favor revisa los siguientes errores:</div>
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('autos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

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
                                    <option value="">-- Selecciona una marca --</option>
                                    @foreach ($marcas as $marca)
                                        <option value="{{ $marca->id }}" {{ old('id_marca') == $marca->id ? 'selected' : '' }}>
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
                                <input type="text" name="modelo" id="modelo" class="form-control @error('modelo') is-invalid @enderror" value="{{ old('modelo') }}" placeholder="Ej: Hilux SRX 2.8 TDI 4x4 AT" required>
                                @error('modelo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Año -->
                            <div class="col-md-4">
                                <label for="anio" class="form-label fw-semibold small text-secondary">
                                    Año de Fabricación <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="anio" id="anio" class="form-control @error('anio') is-invalid @enderror" value="{{ old('anio', date('Y')) }}" min="1950" max="{{ date('Y') + 1 }}" required>
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
                                    <input type="number" step="any" name="precio" id="precio" class="form-control @error('precio') is-invalid @enderror" value="{{ old('precio') }}" placeholder="Ej: 35000000" required>
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
                                    <input type="number" name="kilometraje" id="kilometraje" class="form-control @error('kilometraje') is-invalid @enderror" value="{{ old('kilometraje', 0) }}" min="0" required>
                                    <span class="input-group-text">km</span>
                                </div>
                                @error('kilometraje')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Sección 2: Especificaciones -->
                        <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                            <i class="bi bi-gear text-danger me-1"></i> Especificaciones Mecánicas
                        </h5>

                        <div class="row g-3 mb-4">
                            <!-- Tipo / Condición -->
                            <div class="col-md-6">
                                <label for="tipo" class="form-label fw-semibold small text-secondary">
                                    Condición <span class="text-danger">*</span>
                                </label>
                                <select name="tipo" id="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                                    <option value="usado" {{ old('tipo') == 'usado' ? 'selected' : '' }}>Usado</option>
                                    <option value="nuevo" {{ old('tipo') == 'nuevo' ? 'selected' : '' }}>Nuevo (0 Km)</option>
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Combustible -->
                            <div class="col-md-6">
                                <label for="combustible" class="form-label fw-semibold small text-secondary">
                                    Tipo de Combustible <span class="text-danger">*</span>
                                </label>
                                <select name="combustible" id="combustible" class="form-select @error('combustible') is-invalid @enderror" required>
                                    <option value="nafta" {{ old('combustible') == 'nafta' ? 'selected' : '' }}>Nafta</option>
                                    <option value="diesel" {{ old('combustible') == 'diesel' ? 'selected' : '' }}>Diésel</option>
                                    <option value="hibrido" {{ old('combustible') == 'hibrido' ? 'selected' : '' }}>Híbrido</option>
                                    <option value="electrico" {{ old('combustible') == 'electrico' ? 'selected' : '' }}>Eléctrico</option>
                                </select>
                                @error('combustible')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Sección 3: Fotografía -->
                        <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                            <i class="bi bi-camera text-danger me-1"></i> Fotografía del Vehículo
                        </h5>

                        <div class="mb-4">
                            <label for="imagen" class="form-label fw-semibold small text-secondary">
                                Selecciona una foto nítida de tu vehículo
                            </label>
                            <input type="file" name="imagen" id="imagen" class="form-control @error('imagen') is-invalid @enderror" accept="image/*" onchange="previewImage(event)">
                            <div class="form-text small">Formatos permitidos: JPG, PNG, WEBP. Tamaño máximo: 5 MB.</div>
                            @error('imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <!-- Vista Previa de la Imagen -->
                            <div id="imagePreviewContainer" class="mt-3 text-center p-3 border rounded-3 bg-light d-none">
                                <span class="small text-muted d-block mb-2">Vista previa:</span>
                                <img id="imagePreview" src="#" alt="Vista previa" class="img-fluid rounded-3 shadow-sm" style="max-height: 250px;">
                            </div>
                        </div>

                        <!-- Sección 4: Descripción -->
                        <h5 class="fw-bold text-dark mb-3 border-bottom pb-2">
                            <i class="bi bi-card-text text-danger me-1"></i> Detalles y Equipamiento
                        </h5>

                        <div class="mb-4">
                            <label for="descripcion" class="form-label fw-semibold small text-secondary">
                                Descripción completa del auto
                            </label>
                            <textarea name="descripcion" id="descripcion" rows="4" class="form-control @error('descripcion') is-invalid @enderror" placeholder="Describe el estado de conservación, services realizados, equipamiento extra, tapizado, cubiertas, etc.">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('autos.index') }}" class="btn btn-outline-secondary px-4 py-2">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-accent px-4 py-2 fw-bold">
                                <i class="bi bi-cloud-arrow-up me-1"></i> Publicar Vehículo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(event) {
        const input = event.target;
        const container = document.getElementById('imagePreviewContainer');
        const preview = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('d-none');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            container.classList.add('d-none');
        }
    }
</script>
@endsection
