@extends('layouts.app')

@section('content')

    <div class="container-fluid py-4">

        {{-- =====================================================
        ENCABEZADO
        ====================================================== --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="mb-1">
                    ✏️ Editar Combo
                </h3>

                <p class="text-muted mb-0">
                    {{ $agrupado->codigo }}
                </p>

            </div>

            <a href="{{ route('insumos.agrupados.index') }}" class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>
                Volver al listado

            </a>

        </div>


        {{-- =====================================================
        ERRORES
        ====================================================== --}}

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    Corrija los siguientes errores:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form action="{{ route('insumos.agrupados.update', $agrupado->id) }}" method="POST">

            @csrf

            @method('PUT')


            {{-- =================================================
            INFORMACIÓN
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-dark text-white">

                    <i class="bi bi-boxes"></i>
                    Información del Combo

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        {{-- CÓDIGO --}}

                        <div class="col-md-3">

                            <label class="form-label">
                                Código
                            </label>

                            <input type="text" class="form-control" value="{{ $agrupado->codigo }}" readonly>

                        </div>


                        {{-- NOMBRE --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Nombre del Combo *
                            </label>

                            <input type="text" name="nombre" class="form-control"
                                value="{{ old('nombre', $agrupado->nombre) }}" required>

                        </div>


                        {{-- PRECIO --}}

                        <div class="col-md-3">

                            <label class="form-label">
                                Precio del Combo *
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    Bs
                                </span>

                                <input type="number" name="precio" class="form-control" step="0.01" min="0"
                                    value="{{ old('precio', $agrupado->precio) }}" required>

                            </div>

                        </div>


                        {{-- DESCRIPCIÓN --}}

                        <div class="col-md-12">

                            <label class="form-label">
                                Descripción
                            </label>

                            <textarea name="descripcion" class="form-control"
                                rows="2">{{ old('descripcion', $agrupado->descripcion) }}</textarea>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
            ELEMENTOS
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                    <span>

                        <i class="bi bi-list-check"></i>
                        Elementos del Combo

                    </span>

                    <button type="button" class="btn btn-light btn-sm" id="btnAgregar">

                        <i class="bi bi-plus-circle"></i>
                        Agregar elemento

                    </button>

                </div>


                <div class="card-body">

                    <div id="detallesContainer">

                        @foreach($agrupado->detalles as $indice => $detalle)

                            <div class="detalle-row border rounded p-3 mb-3">

                                <div class="row g-2 align-items-end">

                                    {{-- INSUMO --}}

                                    <div class="col-md-5">

                                        <label class="form-label">
                                            Insumo / Medicamento
                                        </label>

                                        <select name="detalles[{{ $indice }}][insumo_id]" class="form-select select-insumo">

                                            <option value="">
                                                -- Seleccionar insumo --
                                            </option>

                                            @foreach($insumos as $insumo)

                                                <option value="{{ $insumo->id }}" {{ $detalle->insumo_id == $insumo->id ? 'selected' : '' }}>

                                                    {{ $insumo->codigo }}
                                                    -
                                                    {{ $insumo->nombre }}

                                                </option>

                                            @endforeach

                                        </select>

                                    </div>


                                    {{-- OTRO --}}

                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Otro
                                        </label>

                                        <input type="text" name="detalles[{{ $indice }}][nombre_otro]"
                                            class="form-control input-otro" value="{{ $detalle->nombre_otro }}"
                                            placeholder="Escribir otro elemento">

                                    </div>


                                    {{-- CANTIDAD --}}

                                    <div class="col-md-2">

                                        <label class="form-label">
                                            Cantidad *
                                        </label>

                                        <input type="number" name="detalles[{{ $indice }}][cantidad]" class="form-control"
                                            step="0.01" min="0.01" value="{{ $detalle->cantidad }}" required>

                                    </div>


                                    {{-- ELIMINAR --}}

                                    <div class="col-md-1">

                                        <button type="button" class="btn btn-danger btn-eliminar w-100">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </div>

                                </div>

                                <div class="form-text mt-2">

                                    Seleccione un insumo existente
                                    <strong>o</strong>
                                    escriba un elemento manual en "Otro".

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>


            {{-- =================================================
            BOTONES
            ================================================== --}}

            <div class="card shadow-sm">

                <div class="card-body d-flex justify-content-end gap-2">

                    <a href="{{ route('insumos.agrupados.index') }}" class="btn btn-secondary">

                        <i class="bi bi-x-circle"></i>
                        Cancelar

                    </a>

                    <button type="submit" class="btn btn-success">

                        <i class="bi bi-check-circle"></i>
                        Guardar Cambios

                    </button>

                </div>

            </div>

        </form>

    </div>


    <script>

        document.addEventListener('DOMContentLoaded', function () {

            let contador = {{ $agrupado->detalles->count() }};

            const container = document.getElementById('detallesContainer');
            const btnAgregar = document.getElementById('btnAgregar');


            /*
            |--------------------------------------------------------------------------
            | AGREGAR
            |--------------------------------------------------------------------------
            */

            btnAgregar.addEventListener('click', function () {

                const fila = document.createElement('div');

                fila.className =
                    'detalle-row border rounded p-3 mb-3';

                fila.innerHTML = `

                <div class="row g-2 align-items-end">

                    <div class="col-md-5">

                        <label class="form-label">
                            Insumo / Medicamento
                        </label>

                        <select name="detalles[${contador}][insumo_id]"
                                class="form-select select-insumo">

                            <option value="">
                                -- Seleccionar insumo --
                            </option>

                            @foreach($insumos as $insumo)

                                <option value="{{ $insumo->id }}">
                                    {{ $insumo->codigo }} -
                                    {{ $insumo->nombre }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-4">

                        <label class="form-label">
                            Otro
                        </label>

                        <input type="text"
                               name="detalles[${contador}][nombre_otro]"
                               class="form-control input-otro"
                               placeholder="Escribir otro elemento">

                    </div>


                    <div class="col-md-2">

                        <label class="form-label">
                            Cantidad *
                        </label>

                        <input type="number"
                               name="detalles[${contador}][cantidad]"
                               class="form-control"
                               step="0.01"
                               min="0.01"
                               value="1"
                               required>

                    </div>


                    <div class="col-md-1">

                        <button type="button"
                                class="btn btn-danger btn-eliminar w-100">

                            <i class="bi bi-trash"></i>

                        </button>

                    </div>

                </div>
            `;

                container.appendChild(fila);

                contador++;

            });


            /*
            |--------------------------------------------------------------------------
            | ELIMINAR
            |--------------------------------------------------------------------------
            */

            container.addEventListener('click', function (event) {

                const boton = event.target.closest('.btn-eliminar');

                if (!boton) {
                    return;
                }

                const filas = container.querySelectorAll('.detalle-row');

                if (filas.length <= 1) {

                    alert('El combo debe tener al menos un elemento.');

                    return;
                }

                boton.closest('.detalle-row').remove();

            });


            /*
            |--------------------------------------------------------------------------
            | INSUMO / OTRO
            |--------------------------------------------------------------------------
            */

            container.addEventListener('change', function (event) {

                if (!event.target.classList.contains('select-insumo')) {
                    return;
                }

                const fila = event.target.closest('.detalle-row');

                const otro = fila.querySelector('.input-otro');

                if (event.target.value !== '') {

                    otro.value = '';
                    otro.disabled = true;

                } else {

                    otro.disabled = false;

                }

            });


            container.addEventListener('input', function (event) {

                if (!event.target.classList.contains('input-otro')) {
                    return;
                }

                const fila = event.target.closest('.detalle-row');

                const select = fila.querySelector('.select-insumo');

                if (event.target.value.trim() !== '') {

                    select.value = '';
                    select.disabled = true;

                } else {

                    select.disabled = false;

                }

            });


            /*
            |--------------------------------------------------------------------------
            | ESTADO INICIAL DE LAS FILAS
            |--------------------------------------------------------------------------
            */

            document.querySelectorAll('.detalle-row').forEach(function (fila) {

                const select = fila.querySelector('.select-insumo');
                const otro = fila.querySelector('.input-otro');

                if (select.value !== '') {

                    otro.disabled = true;

                } else if (otro.value.trim() !== '') {

                    select.disabled = true;

                }

            });

        });

    </script>

@endsection