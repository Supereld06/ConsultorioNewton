@extends('layouts.app')

@section('content')

    <div class="container">

        {{-- ENCABEZADO --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3>
                    <i class="bi bi-plus-circle"></i>
                    Registrar Insumo
                </h3>

                <p class="text-muted mb-0">
                    Registrar medicamento o insumo médico
                </p>

            </div>

            <a href="{{ route('insumos.index') }}" class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>
                Volver

            </a>

        </div>


        {{-- ERRORES --}}
        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    <i class="bi bi-exclamation-triangle"></i>
                    Corrige los siguientes errores:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <div class="card shadow">

            <div class="card-header bg-dark text-white">

                <i class="bi bi-box-seam"></i>
                Información del Insumo

            </div>


            <div class="card-body">

                <form action="{{ route('insumos.store') }}" method="POST">

                    @csrf


                    <div class="row g-3">


                        {{-- CÓDIGO --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Código <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="codigo" value="{{ old('codigo') }}"
                                class="form-control @error('codigo') is-invalid @enderror" placeholder="Ej. MED-001"
                                required>

                            @error('codigo')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- NOMBRE --}}
                        <div class="col-md-8">

                            <label class="form-label">
                                Nombre <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="nombre" value="{{ old('nombre') }}"
                                class="form-control @error('nombre') is-invalid @enderror"
                                placeholder="Ej. Paracetamol 500 mg" required>

                            @error('nombre')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- TIPO --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Tipo <span class="text-danger">*</span>
                            </label>

                            <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>

                                <option value="">
                                    Seleccione...
                                </option>

                                <option value="MEDICAMENTO" {{ old('tipo') == 'MEDICAMENTO' ? 'selected' : '' }}>

                                    💊 Medicamento

                                </option>

                                <option value="INSUMO_MEDICO" {{ old('tipo') == 'INSUMO_MEDICO' ? 'selected' : '' }}>

                                    🩹 Insumo médico

                                </option>

                            </select>

                            @error('tipo')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- UNIDAD --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Unidad de medida <span class="text-danger">*</span>
                            </label>

                            <select name="unidad_medida" class="form-select @error('unidad_medida') is-invalid @enderror"
                                required>

                                <option value="">
                                    Seleccione...
                                </option>

                                <option value="UNIDAD" {{ old('unidad_medida') == 'UNIDAD' ? 'selected' : '' }}>

                                    Unidad

                                </option>

                                <option value="CAJA" {{ old('unidad_medida') == 'CAJA' ? 'selected' : '' }}>

                                    Caja

                                </option>

                                <option value="PAQUETE" {{ old('unidad_medida') == 'PAQUETE' ? 'selected' : '' }}>

                                    Paquete

                                </option>

                                <option value="FRASCO" {{ old('unidad_medida') == 'FRASCO' ? 'selected' : '' }}>

                                    Frasco

                                </option>

                                <option value="AMPOLLA" {{ old('unidad_medida') == 'AMPOLLA' ? 'selected' : '' }}>

                                    Ampolla

                                </option>

                                <option value="LITRO" {{ old('unidad_medida') == 'LITRO' ? 'selected' : '' }}>

                                    Litro

                                </option>

                                <option value="METRO" {{ old('unidad_medida') == 'METRO' ? 'selected' : '' }}>

                                    Metro

                                </option>

                            </select>

                            @error('unidad_medida')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- STOCK MÍNIMO --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Stock mínimo
                            </label>

                            <input type="number" name="stock_minimo" value="{{ old('stock_minimo', 0) }}"
                                class="form-control @error('stock_minimo') is-invalid @enderror" min="0" step="0.01"
                                required>

                            <small class="text-muted">
                                El sistema alertará cuando el stock llegue a este valor.
                            </small>

                            @error('stock_minimo')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- PRECIO COMPRA --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Precio de compra (Bs.)
                            </label>

                            <input type="number" name="precio_compra" value="{{ old('precio_compra', 0) }}"
                                class="form-control @error('precio_compra') is-invalid @enderror" min="0" step="0.01"
                                required>

                            @error('precio_compra')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- PRECIO VENTA --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Precio de venta (Bs.)
                            </label>

                            <input type="number" name="precio_venta" value="{{ old('precio_venta', 0) }}"
                                class="form-control @error('precio_venta') is-invalid @enderror" min="0" step="0.01"
                                required>

                            @error('precio_venta')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- DESCRIPCIÓN --}}
                        <div class="col-12">

                            <label class="form-label">
                                Descripción
                            </label>

                            <textarea name="descripcion" rows="3"
                                class="form-control @error('descripcion') is-invalid @enderror"
                                placeholder="Descripción opcional del medicamento o insumo...">{{ old('descripcion') }}</textarea>

                            @error('descripcion')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- INFORMACIÓN --}}
                        <div class="col-12">

                            <div class="alert alert-info mb-0">

                                <i class="bi bi-info-circle"></i>

                                <strong>Importante:</strong>

                                El stock inicial será

                                <strong>0</strong>.

                                El stock se incrementará posteriormente mediante el módulo
                                <strong>Ingreso</strong>.

                            </div>

                        </div>


                        {{-- BOTONES --}}
                        <div class="col-12 mt-4">

                            <button type="submit" class="btn btn-success">

                                <i class="bi bi-check-circle"></i>
                                Registrar Insumo

                            </button>

                            <a href="{{ route('insumos.index') }}" class="btn btn-secondary">

                                <i class="bi bi-x-circle"></i>
                                Cancelar

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection