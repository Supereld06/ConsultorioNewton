@extends('layouts.app')

@section('content')

    <div class="container">

        {{-- ENCABEZADO --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3>
                    <i class="bi bi-pencil-square"></i>
                    Editar Insumo
                </h3>

                <p class="text-muted mb-0">
                    {{ $insumo->codigo }} - {{ $insumo->nombre }}
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

                <form action="{{ route('insumos.update', $insumo->id) }}" method="POST">

                    @csrf

                    @method('PUT')


                    <div class="row g-3">


                        {{-- CÓDIGO --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Código <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="codigo" value="{{ old('codigo', $insumo->codigo) }}"
                                class="form-control @error('codigo') is-invalid @enderror" required>

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

                            <input type="text" name="nombre" value="{{ old('nombre', $insumo->nombre) }}"
                                class="form-control @error('nombre') is-invalid @enderror" required>

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

                                <option value="MEDICAMENTO" {{ old('tipo', $insumo->tipo) == 'MEDICAMENTO' ? 'selected' : '' }}>

                                    💊 Medicamento

                                </option>

                                <option value="INSUMO_MEDICO" {{ old('tipo', $insumo->tipo) == 'INSUMO_MEDICO' ? 'selected' : '' }}>

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

                                @php
                                    $unidades = [
                                        'UNIDAD' => 'Unidad',
                                        'CAJA' => 'Caja',
                                        'PAQUETE' => 'Paquete',
                                        'FRASCO' => 'Frasco',
                                        'AMPOLLA' => 'Ampolla',
                                        'LITRO' => 'Litro',
                                        'METRO' => 'Metro',
                                    ];
                                @endphp

                                @foreach($unidades as $valor => $nombre)

                                    <option value="{{ $valor }}" {{ old('unidad_medida', $insumo->unidad_medida) == $valor ? 'selected' : '' }}>

                                        {{ $nombre }}

                                    </option>

                                @endforeach

                            </select>

                            @error('unidad_medida')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- STOCK ACTUAL --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Stock actual
                            </label>

                            <input type="text" value="{{ number_format($insumo->stock, 2) }}" class="form-control" readonly>

                            <small class="text-muted">
                                El stock se modifica mediante Ingreso, Salida o Ajuste.
                            </small>

                        </div>


                        {{-- STOCK MÍNIMO --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Stock mínimo
                            </label>

                            <input type="number" name="stock_minimo"
                                value="{{ old('stock_minimo', $insumo->stock_minimo) }}"
                                class="form-control @error('stock_minimo') is-invalid @enderror" min="0" step="0.01"
                                required>

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

                            <input type="number" name="precio_compra"
                                value="{{ old('precio_compra', $insumo->precio_compra) }}"
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

                            <input type="number" name="precio_venta"
                                value="{{ old('precio_venta', $insumo->precio_venta) }}"
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
                                class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion', $insumo->descripcion) }}</textarea>

                            @error('descripcion')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- ESTADO --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Estado
                            </label>

                            <select class="form-select" disabled>

                                @if($insumo->estado)

                                    <option>Activo</option>

                                @else

                                    <option>Inactivo</option>

                                @endif

                            </select>

                        </div>


                        {{-- INFORMACIÓN STOCK --}}
                        <div class="col-12">

                            <div class="alert alert-warning mb-0">

                                <i class="bi bi-exclamation-triangle"></i>

                                <strong>Nota:</strong>

                                No modificaremos directamente el stock desde este formulario.
                                Los cambios de inventario se registrarán mediante los módulos
                                de <strong>Ingreso</strong> y <strong>Salida</strong>.

                            </div>

                        </div>


                        {{-- BOTONES --}}
                        <div class="col-12 mt-4">

                            <button type="submit" class="btn btn-success">

                                <i class="bi bi-check-circle"></i>
                                Guardar Cambios

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