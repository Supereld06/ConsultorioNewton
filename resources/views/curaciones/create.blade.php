@extends('layouts.app')

@section('content')

    <div class="container-fluid py-4">

        {{-- =====================================================
        ENCABEZADO
        ====================================================== --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="mb-1">
                    ➕ Nueva Curación
                </h3>

                <p class="text-muted mb-0">
                    Registrar una nueva curación
                </p>

            </div>

            <a href="{{ route('curaciones.index') }}" class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>
                Volver

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


        <form action="{{ route('curaciones.store') }}" method="POST" id="formCuracion">

            @csrf


            {{-- =================================================
            CONSULTA
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-dark text-white">

                    <i class="bi bi-person-vcard"></i>

                    Consulta

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-12">

                            <label class="form-label">

                                Consulta *

                            </label>
                            <select name="consultation_id" id="consultation_id" class="form-select" required>

                                <option value="">-- Seleccionar consulta --</option>

                                @foreach($consultations as $consulta)

                                    @php
                                        $appointment = $consulta->appointment;
                                        $paciente = $appointment?->patient;
                                        $doctor = $appointment?->doctor;
                                    @endphp

                                    <option value="{{ $consulta->id }}" {{ old('consultation_id') == $consulta->id ? 'selected' : '' }}>

                                        Consulta #{{ $consulta->id }}

                                        @if($paciente)
                                            - Paciente: {{ $paciente->nombres }} {{ $paciente->apellidos }}
                                        @endif

                                        @if($doctor)
                                            - Dr./Dra. {{ $doctor->nombres }} {{ $doctor->apellidos }}
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            <small class="text-muted">

                                La consulta determina automáticamente
                                el paciente y el doctor.

                            </small>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
            DATOS DE LA CURACIÓN
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-primary text-white">

                    <i class="bi bi-shield-plus"></i>

                    Datos de la Curación

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-3">

                            <label class="form-label">
                                Fecha *
                            </label>

                            <input type="date" name="fecha" class="form-control" value="{{ old(
        'fecha',
        now()->format('Y-m-d')
    ) }}" required>

                        </div>


                        <div class="col-md-9">

                            <label class="form-label">
                                Descripción de la Curación
                            </label>

                            <textarea name="descripcion" class="form-control" rows="2"
                                placeholder="Detalle de la curación realizada...">{{ old('descripcion') }}</textarea>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
            INSUMOS
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                    <span>

                        <i class="bi bi-box-seam"></i>

                        Insumos utilizados

                    </span>


                    <button type="button" class="btn btn-light btn-sm" id="btnAgregarDetalle">

                        <i class="bi bi-plus-circle"></i>

                        Agregar

                    </button>

                </div>


                <div class="card-body">

                    <div id="detallesContainer">

                        <div class="detalle-row border rounded p-3 mb-3">

                            <div class="row g-2 align-items-end">

                                {{-- TIPO --}}

                                <div class="col-md-2">

                                    <label class="form-label">
                                        Tipo
                                    </label>

                                    <select class="form-select tipo-detalle" name="detalles[0][tipo]">

                                        <option value="insumo">
                                            Insumo
                                        </option>

                                        <option value="agrupado">
                                            Combo
                                        </option>

                                        <option value="otro">
                                            Otro
                                        </option>

                                    </select>

                                </div>


                                {{-- INSUMO --}}

                                <div class="col-md-4 campo-insumo">

                                    <label class="form-label">
                                        Insumo
                                    </label>

                                    <select name="detalles[0][insumo_id]" class="form-select select-insumo">

                                        <option value="">
                                            -- Seleccionar --
                                        </option>

                                        @foreach($insumos as $insumo)

                                            <option value="{{ $insumo->id }}" data-precio="{{ $insumo->precio_venta }}">

                                                {{ $insumo->codigo }}
                                                -
                                                {{ $insumo->nombre }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                {{-- COMBO --}}

                                <div class="col-md-4 campo-agrupado d-none">

                                    <label class="form-label">
                                        Insumo Agrupado
                                    </label>

                                    <select name="detalles[0][insumo_agrupado_id]" class="form-select select-agrupado">

                                        <option value="">
                                            -- Seleccionar combo --
                                        </option>

                                        @foreach($agrupados as $agrupado)

                                            <option value="{{ $agrupado->id }}" data-precio="{{ $agrupado->precio }}">

                                                {{ $agrupado->codigo }}
                                                -
                                                {{ $agrupado->nombre }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>


                                {{-- OTRO --}}

                                <div class="col-md-4 campo-otro d-none">

                                    <label class="form-label">
                                        Otro
                                    </label>

                                    <input type="text" name="detalles[0][nombre_otro]" class="form-control"
                                        placeholder="Nombre del elemento">

                                </div>


                                {{-- CANTIDAD --}}

                                <div class="col-md-2">

                                    <label class="form-label">
                                        Cantidad
                                    </label>

                                    <input type="number" name="detalles[0][cantidad]" class="form-control cantidad"
                                        step="0.01" min="0.01" value="1">

                                </div>


                                {{-- PRECIO --}}

                                <div class="col-md-2">

                                    <label class="form-label">
                                        Precio
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text">
                                            Bs
                                        </span>

                                        <input type="number" name="detalles[0][precio_unitario]" class="form-control precio"
                                            step="0.01" min="0" value="0">

                                    </div>

                                </div>


                                {{-- ELIMINAR --}}

                                <div class="col-md-1">

                                    <button type="button" class="btn btn-danger btnEliminar">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </div>

                            </div>

                            <div class="text-muted small mt-2">

                                Los insumos normales descuentan stock.
                                Los combos se descomponen automáticamente.
                                "Otros" no afectan el inventario.

                            </div>

                        </div>

                    </div>


                    {{-- TOTAL INSUMOS --}}

                    <div class="row justify-content-end mt-3">

                        <div class="col-md-4">

                            <div class="card border">

                                <div class="card-body">

                                    <div class="d-flex justify-content-between">

                                        <span>
                                            Total Insumos:
                                        </span>

                                        <strong id="totalInsumos">
                                            Bs 0.00
                                        </strong>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
            COSTO DE CURACIÓN
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-success text-white">

                    <i class="bi bi-cash-coin"></i>

                    Costos de la Curación

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-4">

                            <label class="form-label">
                                Total Insumos
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    Bs
                                </span>

                                <input type="text" id="totalInsumosTexto" class="form-control" value="0.00" readonly>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Costo de la Curación *
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    Bs
                                </span>

                                <input type="number" name="costo_curacion" id="costoCuracion" class="form-control"
                                    step="0.01" min="0" value="{{ old('costo_curacion', 0) }}" required>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Total
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    Bs
                                </span>

                                <input type="text" id="totalGeneral" class="form-control fw-bold" value="0.00" readonly>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
            DISTRIBUCIÓN
            ================================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-warning">

                    <i class="bi bi-pie-chart"></i>

                    Distribución del Costo de Curación

                </div>


                <div class="card-body">

                    <p class="text-muted">

                        Indique el porcentaje correspondiente al doctor
                        y a la enfermera. Newton recibirá automáticamente
                        el porcentaje restante.

                    </p>


                    <div class="row g-3">

                        {{-- DOCTOR --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Doctor
                            </label>

                            <div class="input-group">

                                <input type="number" name="porcentaje_doctor" id="doctorPorcentaje" class="form-control"
                                    min="0" max="100" step="0.01" value="{{ old(
        'porcentaje_doctor',
        60
    ) }}">

                                <span class="input-group-text">
                                    %
                                </span>

                            </div>

                            <small id="doctorMonto" class="text-muted">
                                Bs 0.00
                            </small>

                        </div>


                        {{-- ENFERMERA --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Enfermera
                            </label>

                            <div class="input-group">

                                <input type="number" name="porcentaje_enfermera" id="enfermeraPorcentaje"
                                    class="form-control" min="0" max="100" step="0.01" value="{{ old(
        'porcentaje_enfermera',
        10
    ) }}">

                                <span class="input-group-text">
                                    %
                                </span>

                            </div>

                            <small id="enfermeraMonto" class="text-muted">
                                Bs 0.00
                            </small>

                        </div>


                        {{-- NEWTON --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Newton / Consultorio
                            </label>

                            <div class="input-group">

                                <input type="text" id="newtonPorcentaje" class="form-control" value="30.00" readonly>

                                <span class="input-group-text">
                                    %
                                </span>

                            </div>

                            <small id="newtonMonto" class="text-muted">

                                Bs 0.00

                            </small>

                        </div>

                    </div>


                    <div id="alertaDistribucion" class="alert alert-success mt-3 mb-0">

                        <i class="bi bi-check-circle"></i>

                        Distribución correcta: 100%

                    </div>

                </div>

            </div>


            {{-- =================================================
            BOTONES
            ================================================== --}}

            <div class="card shadow-sm">

                <div class="card-body d-flex justify-content-end gap-2">

                    <a href="{{ route('curaciones.index') }}" class="btn btn-secondary">

                        <i class="bi bi-x-circle"></i>

                        Cancelar

                    </a>

                    <button type="submit" class="btn btn-success">

                        <i class="bi bi-save"></i>

                        Registrar Curación

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

            const container =
                document.getElementById('detallesContainer');

            const btnAgregar =
                document.getElementById('btnAgregarDetalle');


            /*
            |--------------------------------------------------------------------------
            | CAMBIAR TIPO
            |--------------------------------------------------------------------------
            */

            function configurarFila(fila) {

                const tipo =
                    fila.querySelector('.tipo-detalle');

                const campoInsumo =
                    fila.querySelector('.campo-insumo');

                const campoAgrupado =
                    fila.querySelector('.campo-agrupado');

                const campoOtro =
                    fila.querySelector('.campo-otro');


                function actualizar() {

                    campoInsumo.classList.add('d-none');
                    campoAgrupado.classList.add('d-none');
                    campoOtro.classList.add('d-none');

                    if (tipo.value === 'insumo') {

                        campoInsumo.classList.remove('d-none');

                    }

                    if (tipo.value === 'agrupado') {

                        campoAgrupado.classList.remove('d-none');

                    }

                    if (tipo.value === 'otro') {

                        campoOtro.classList.remove('d-none');

                    }

                }


                tipo.addEventListener('change', actualizar);

                actualizar();

            }


            configurarFila(
                document.querySelector('.detalle-row')
            );


            /*
            |--------------------------------------------------------------------------
            | AGREGAR FILA
            |--------------------------------------------------------------------------
            */

            btnAgregar.addEventListener('click', function () {

                const fila = document.createElement('div');

                fila.className =
                    'detalle-row border rounded p-3 mb-3';


                fila.innerHTML = `

                        <div class="row g-2 align-items-end">

                            <div class="col-md-2">

                                <label class="form-label">
                                    Tipo
                                </label>

                                <select class="form-select tipo-detalle"
                                        name="detalles[${contador}][tipo]">

                                    <option value="insumo">
                                        Insumo
                                    </option>

                                    <option value="agrupado">
                                        Combo
                                    </option>

                                    <option value="otro">
                                        Otro
                                    </option>

                                </select>

                            </div>


                            <div class="col-md-4 campo-insumo">

                                <label class="form-label">
                                    Insumo
                                </label>

                                <select name="detalles[${contador}][insumo_id]"
                                        class="form-select select-insumo">

                                    <option value="">
                                        -- Seleccionar --
                                    </option>

                                    @foreach($insumos as $insumo)

                                        <option value="{{ $insumo->id }}"
                                                data-precio="{{ $insumo->precio_venta }}">

                                            {{ $insumo->codigo }} -
                                            {{ $insumo->nombre }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="col-md-4 campo-agrupado d-none">

                                <label class="form-label">
                                    Insumo Agrupado
                                </label>

                                <select name="detalles[${contador}][insumo_agrupado_id]"
                                        class="form-select select-agrupado">

                                    <option value="">
                                        -- Seleccionar combo --
                                    </option>

                                    @foreach($agrupados as $agrupado)

                                        <option value="{{ $agrupado->id }}"
                                                data-precio="{{ $agrupado->precio }}">

                                            {{ $agrupado->codigo }} -
                                            {{ $agrupado->nombre }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            <div class="col-md-4 campo-otro d-none">

                                <label class="form-label">
                                    Otro
                                </label>

                                <input type="text"
                                       name="detalles[${contador}][nombre_otro]"
                                       class="form-control"
                                       placeholder="Nombre del elemento">

                            </div>


                            <div class="col-md-2">

                                <label class="form-label">
                                    Cantidad
                                </label>

                                <input type="number"
                                       name="detalles[${contador}][cantidad]"
                                       class="form-control cantidad"
                                       step="0.01"
                                       min="0.01"
                                       value="1">

                            </div>


                            <div class="col-md-2">

                                <label class="form-label">
                                    Precio
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        Bs
                                    </span>

                                    <input type="number"
                                           name="detalles[${contador}][precio_unitario]"
                                           class="form-control precio"
                                           step="0.01"
                                           min="0"
                                           value="0">

                                </div>

                            </div>


                            <div class="col-md-1">

                                <button type="button"
                                        class="btn btn-danger btnEliminar">

                                    <i class="bi bi-trash"></i>

                                </button>

                            </div>

                        </div>

                    `;


                container.appendChild(fila);

                configurarFila(fila);

                contador++;

            });


            /*
            |--------------------------------------------------------------------------
            | ELIMINAR
            |--------------------------------------------------------------------------
            */

            container.addEventListener('click', function (e) {

                const boton =
                    e.target.closest('.btnEliminar');

                if (!boton) {
                    return;
                }

                const filas =
                    container.querySelectorAll('.detalle-row');

                if (filas.length <= 1) {

                    alert(
                        'Debe existir al menos un elemento.'
                    );

                    return;
                }

                boton.closest('.detalle-row').remove();

                calcularTotales();

            });


            /*
            |--------------------------------------------------------------------------
            | PRECIO AUTOMÁTICO
            |--------------------------------------------------------------------------
            */

            container.addEventListener('change', function (e) {

                if (
                    e.target.classList.contains('select-insumo') ||
                    e.target.classList.contains('select-agrupado')
                ) {

                    const opcion =
                        e.target.options[e.target.selectedIndex];

                    const precio =
                        opcion.dataset.precio || 0;

                    const fila =
                        e.target.closest('.detalle-row');

                    const inputPrecio =
                        fila.querySelector('.precio');

                    inputPrecio.value = parseFloat(precio)
                        .toFixed(2);

                    calcularTotales();
                }

            });


            /*
            |--------------------------------------------------------------------------
            | CALCULAR TOTALES
            |--------------------------------------------------------------------------
            */

            function calcularTotales() {

                let total = 0;

                document.querySelectorAll('.detalle-row')
                    .forEach(function (fila) {

                        const cantidad =
                            parseFloat(
                                fila.querySelector('.cantidad')?.value
                            ) || 0;

                        const precio =
                            parseFloat(
                                fila.querySelector('.precio')?.value
                            ) || 0;

                        total += cantidad * precio;

                    });


                document.getElementById('totalInsumos')
                    .innerText =
                    'Bs ' + total.toFixed(2);

                document.getElementById('totalInsumosTexto')
                    .value =
                    total.toFixed(2);


                const costo =
                    parseFloat(
                        document.getElementById('costoCuracion').value
                    ) || 0;


                const totalGeneral =
                    total + costo;


                document.getElementById('totalGeneral')
                    .value =
                    totalGeneral.toFixed(2);


                calcularDistribucion(totalGeneral);

            }


            /*
            |--------------------------------------------------------------------------
            | DISTRIBUCIÓN
            |--------------------------------------------------------------------------
            */

            function calcularDistribucion(total) {

                const doctor =
                    parseFloat(
                        document.getElementById('doctorPorcentaje').value
                    ) || 0;

                const enfermera =
                    parseFloat(
                        document.getElementById('enfermeraPorcentaje').value
                    ) || 0;


                const newton =
                    100 - doctor - enfermera;


                document.getElementById('newtonPorcentaje')
                    .value =
                    newton.toFixed(2);


                document.getElementById('doctorMonto')
                    .innerText =
                    'Bs ' +
                    (total * doctor / 100).toFixed(2);


                document.getElementById('enfermeraMonto')
                    .innerText =
                    'Bs ' +
                    (total * enfermera / 100).toFixed(2);


                document.getElementById('newtonMonto')
                    .innerText =
                    'Bs ' +
                    (total * newton / 100).toFixed(2);


                const alerta =
                    document.getElementById('alertaDistribucion');


                if (doctor + enfermera > 100) {

                    alerta.className =
                        'alert alert-danger mt-3 mb-0';

                    alerta.innerHTML = `

                            <i class="bi bi-exclamation-triangle"></i>

                            La distribución supera el 100%.

                        `;

                } else {

                    alerta.className =
                        'alert alert-success mt-3 mb-0';

                    alerta.innerHTML = `

                            <i class="bi bi-check-circle"></i>

                            Distribución correcta:
                            100%

                        `;

                }

            }


            /*
            |--------------------------------------------------------------------------
            | EVENTOS DE CÁLCULO
            |--------------------------------------------------------------------------
            */

            container.addEventListener('input', function () {

                calcularTotales();

            });


            document.getElementById('costoCuracion')
                .addEventListener('input', function () {

                    calcularTotales();

                });


            document.getElementById('doctorPorcentaje')
                .addEventListener('input', function () {

                    calcularTotales();

                });


            document.getElementById('enfermeraPorcentaje')
                .addEventListener('input', function () {

                    calcularTotales();

                });


            calcularTotales();

        });

    </script>

@endsection