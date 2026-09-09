@extends('layouts.app')

@section('content')

    <div class="container-fluid py-4">

        {{-- =====================================================
        ENCABEZADO
        ====================================================== --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="mb-1">
                    ➕ Nuevo Combo
                </h3>

                <p class="text-muted mb-0">
                    Crear un nuevo combo de insumos
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


        <form action="{{ route('insumos.agrupados.store') }}" method="POST">

            @csrf


            {{-- =================================================
            INFORMACIÓN DEL COMBO
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-dark text-white">

                    <i class="bi bi-boxes"></i>
                    Información del Combo

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        {{-- NOMBRE --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Nombre del Combo *
                            </label>

                            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}"
                                placeholder="Ej.: Kit de Curación" required>

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
                                    value="{{ old('precio', 0) }}" required>

                            </div>

                            <small class="text-muted">
                                Precio final del combo.
                            </small>

                        </div>


                        {{-- CÓDIGO --}}

                        <div class="col-md-3">

                            <label class="form-label">
                                Código
                            </label>

                            <input type="text" class="form-control" value="Se generará automáticamente" readonly>

                        </div>


                        {{-- DESCRIPCIÓN --}}

                        <div class="col-md-12">

                            <label class="form-label">
                                Descripción
                            </label>

                            <textarea name="descripcion" class="form-control" rows="2"
                                placeholder="Descripción del combo...">{{ old('descripcion') }}</textarea>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
            ELEMENTOS DEL COMBO
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

                        {{-- FILA INICIAL --}}

                        <div class="detalle-row border rounded p-3 mb-3">

                            <div class="row g-2 align-items-end">

                                {{-- INSUMO EXISTENTE --}}

                                <div class="col-md-5">

                                    <label class="form-label">
                                        Insumo / Medicamento
                                    </label>

                                    <select name="detalles[0][insumo_id]" class="form-select select-insumo">

                                        <option value="">
                                            -- Seleccionar insumo --
                                        </option>

                                        @foreach($insumos as $insumo)

                                            <option value="{{ $insumo->id }}">

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

                                    <input type="text" name="detalles[0][nombre_otro]" class="form-control input-otro"
                                        placeholder="Escribir otro elemento">

                                </div>


                                {{-- CANTIDAD --}}

                                <div class="col-md-2">

                                    <label class="form-label">
                                        Cantidad *
                                    </label>

                                    <input type="number" name="detalles[0][cantidad]" class="form-control" step="0.01"
                                        min="0.01" value="1" required>

                                </div>


                                {{-- ELIMINAR --}}

                                <div class="col-md-1">

                                    <button type="button" class="btn btn-danger btn-eliminar w-100" title="Eliminar">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </div>

                            </div>

                            <div class="form-text mt-2">

                                Seleccione un insumo existente
                                <strong>o</strong>
                                escriba manualmente un elemento en "Otro".

                            </div>

                        </div>

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

                        <i class="bi bi-save"></i>
                        Registrar Combo

                    </button>

                </div>

            </div>

        </form>

    </div>


    {{-- =====================================================
    JAVASCRIPT
    ====================================================== --}}
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            let contador = 1;

            const container = document.getElementById('detallesContainer');
            const btnAgregar = document.getElementById('btnAgregar');


            /*
            |--------------------------------------------------------------------------
            | INICIALIZAR SELECT2
            |--------------------------------------------------------------------------
            */

            function inicializarSelectInsumo(elemento) {

                $(elemento).select2({

                    placeholder: '-- Seleccionar insumo --',

                    allowClear: true,

                    width: '100%',

                    language: {

                        noResults: function () {
                            return 'No se encontró ningún insumo';
                        },

                        searching: function () {
                            return 'Buscando...';
                        }

                    }

                });

            }


            /*
            |--------------------------------------------------------------------------
            | INICIALIZAR SELECT EXISTENTE
            |--------------------------------------------------------------------------
            */

            inicializarSelectInsumo(
                document.querySelector('.select-insumo')
            );


            /*
            |--------------------------------------------------------------------------
            | AGREGAR ELEMENTO
            |--------------------------------------------------------------------------
            */

            btnAgregar.addEventListener('click', function () {

                const fila = document.createElement('div');

                fila.classList.add(
                    'detalle-row',
                    'border',
                    'rounded',
                    'p-3',
                    'mb-3'
                );


                fila.innerHTML = `

                <div class="row g-2 align-items-end">

                    {{-- INSUMO EXISTENTE --}}

                    <div class="col-md-5">

                        <label class="form-label">
                            Insumo / Medicamento
                        </label>

                        <select
                            name="detalles[${contador}][insumo_id]"
                            class="form-select select-insumo"
                        >

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


                    {{-- OTRO --}}

                    <div class="col-md-4">

                        <label class="form-label">
                            Otro
                        </label>

                        <input
                            type="text"
                            name="detalles[${contador}][nombre_otro]"
                            class="form-control input-otro"
                            placeholder="Escribir otro elemento"
                        >

                    </div>


                    {{-- CANTIDAD --}}

                    <div class="col-md-2">

                        <label class="form-label">
                            Cantidad *
                        </label>

                        <input
                            type="number"
                            name="detalles[${contador}][cantidad]"
                            class="form-control"
                            step="0.01"
                            min="0.01"
                            value="1"
                            required
                        >

                    </div>


                    {{-- ELIMINAR --}}

                    <div class="col-md-1">

                        <button
                            type="button"
                            class="btn btn-danger btn-eliminar w-100"
                            title="Eliminar"
                        >

                            <i class="bi bi-trash"></i>

                        </button>

                    </div>

                </div>


                <div class="form-text mt-2">

                    Seleccione un insumo existente
                    <strong>o</strong>
                    escriba manualmente un elemento en "Otro".

                </div>

            `;


                container.appendChild(fila);


                /*
                |--------------------------------------------------------------------------
                | INICIALIZAR SELECT2 EN LA NUEVA FILA
                |--------------------------------------------------------------------------
                */

                inicializarSelectInsumo(
                    fila.querySelector('.select-insumo')
                );


                contador++;

            });


            /*
            |--------------------------------------------------------------------------
            | ELIMINAR ELEMENTO
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


                const fila = boton.closest('.detalle-row');

                const select = fila.querySelector('.select-insumo');


                /*
                |--------------------------------------------------------------------------
                | DESTRUIR SELECT2 ANTES DE ELIMINAR
                |--------------------------------------------------------------------------
                */

                if ($(select).hasClass('select2-hidden-accessible')) {

                    $(select).select2('destroy');

                }


                fila.remove();

            });


            /*
            |--------------------------------------------------------------------------
            | EVITAR INSUMO + OTRO AL MISMO TIEMPO
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


            /*
            |--------------------------------------------------------------------------
            | CUANDO ESCRIBE EN "OTRO"
            |--------------------------------------------------------------------------
            */

            container.addEventListener('input', function (event) {

                if (!event.target.classList.contains('input-otro')) {
                    return;
                }


                const fila = event.target.closest('.detalle-row');

                const select = fila.querySelector('.select-insumo');


                if (event.target.value.trim() !== '') {

                    /*
                    | Limpiar el insumo seleccionado
                    */

                    $(select).val(null).trigger('change');


                    /*
                    | Deshabilitar Select2
                    */

                    $(select).prop('disabled', true);


                } else {

                    /*
                    | Habilitar Select2 nuevamente
                    */

                    $(select).prop('disabled', false);

                }

            });

        });

    </script>


@endsection