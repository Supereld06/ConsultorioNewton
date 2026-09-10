@extends('layouts.app')

@section('content')

    <style>
        .curacion-form .card {
            margin-bottom: 10px;
        }

        .curacion-form .card-header {
            padding: 8px 12px;
            font-weight: 600;
        }

        .curacion-form .card-body {
            padding: 10px;
        }

        .curacion-form .form-label {
            margin-bottom: 3px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .curacion-form .form-control,
        .curacion-form .form-select {
            font-size: 0.9rem;
        }

        .detalle-row {
            padding: 8px !important;
            margin-bottom: 8px !important;
            background: #fafafa;
        }

        .detalle-row .form-label {
            font-size: 0.78rem;
        }

        .total-box {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 8px 12px;
        }

        .distribucion-monto {
            font-size: 0.8rem;
        }

        .acciones-fijas {
            position: sticky;
            bottom: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.97);
            border-top: 1px solid #dee2e6;
            padding: 10px 0;
            margin-top: 10px;
        }
    </style>


    <div class="container-fluid py-2 curacion-form">

        {{-- ==========================================================
        ENCABEZADO
        =========================================================== --}}

        <div class="d-flex justify-content-between align-items-center mb-2">

            <div>
                <h4 class="mb-0">
                    ➕ Nueva Curación
                </h4>

                <small class="text-muted">
                    Registrar una nueva curación
                </small>
            </div>

            <a href="{{ route('curaciones.index') }}" class="btn btn-secondary btn-sm">

                <i class="bi bi-arrow-left"></i>
                Volver
            </a>

        </div>


        {{-- ==========================================================
        ERRORES
        =========================================================== --}}

        @if($errors->any())

            <div class="alert alert-danger py-2 mb-2">

                <strong>
                    Corrija los siguientes errores:
                </strong>

                <ul class="mb-0 mt-1">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form action="{{ route('curaciones.store') }}" method="POST" id="formCuracion">

            @csrf


            {{-- ======================================================
            CONSULTA
            ======================================================= --}}

            <div class="card shadow-sm">

                <div class="card-header bg-dark text-white">

                    <i class="bi bi-person-vcard"></i>
                    Consulta

                </div>

                <div class="card-body">

                    <div class="row g-2">

                        <div class="col-md-12">

                            <label class="form-label">
                                Consulta *
                            </label>

                            <select name="consultation_id" id="consultation_id" class="form-select form-select-sm" required>

                                <option value="">
                                    -- Seleccionar consulta --
                                </option>

                                @foreach($consultations as $consulta)

                                    @php

                                        $appointment =
                                            $consulta->appointment;

                                        $paciente =
                                            $appointment?->patient;

                                        $doctor =
                                            $appointment?->doctor;

                                    @endphp

                                    <option value="{{ $consulta->id }}" {{ old('consultation_id') == $consulta->id ? 'selected' : '' }}>

                                        Consulta #{{ $consulta->id }}

                                        @if($paciente)

                                            -
                                            Paciente:
                                            {{ $paciente->nombres }}
                                            {{ $paciente->apellidos }}

                                        @endif

                                        @if($doctor)

                                            -
                                            Dr./Dra.
                                            {{ $doctor->nombres }}
                                            {{ $doctor->apellidos }}

                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            <small class="text-muted">
                                La consulta determina el paciente y doctor.
                            </small>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ======================================================
            DATOS + COSTOS
            ======================================================= --}}

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">

                    <i class="bi bi-shield-plus"></i>
                    Datos y costos de la curación

                </div>


                <div class="card-body">

                    <div class="row g-2 align-items-end">

                        {{-- FECHA --}}

                        <div class="col-md-2">

                            <label class="form-label">
                                Fecha *
                            </label>

                            <input type="date" name="fecha" class="form-control form-control-sm"
                                value="{{ old('fecha', now()->format('Y-m-d')) }}" required>

                        </div>


                        {{-- DESCRIPCIÓN --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Descripción
                            </label>

                            <input type="text" name="descripcion" class="form-control form-control-sm"
                                value="{{ old('descripcion') }}" placeholder="Detalle de la curación...">

                        </div>


                        {{-- TOTAL INSUMOS --}}

                        <div class="col-md-2">

                            <label class="form-label">
                                Total insumos
                            </label>

                            <div class="input-group input-group-sm">

                                <span class="input-group-text">
                                    Bs
                                </span>

                                <input type="text" id="totalInsumosTexto" class="form-control" value="0.00" readonly>

                            </div>

                        </div>


                        {{-- COSTO CURACIÓN --}}

                        <div class="col-md-2">

                            <label class="form-label">
                                Costo curación *
                            </label>

                            <div class="input-group input-group-sm">

                                <span class="input-group-text">
                                    Bs
                                </span>

                                <input type="number" name="costo_curacion" id="costoCuracion" class="form-control"
                                    step="0.01" min="0" value="{{ old('costo_curacion', 0) }}" required>

                            </div>

                        </div>


                        {{-- TOTAL GENERAL --}}

                        <div class="col-md-2">

                            <label class="form-label">
                                Total
                            </label>

                            <div class="input-group input-group-sm">

                                <span class="input-group-text">
                                    Bs
                                </span>

                                <input type="text" id="totalGeneral" class="form-control fw-bold" value="0.00" readonly>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ======================================================
            INSUMOS
            ======================================================= --}}

            <div class="card shadow-sm">

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

                        {{-- ==================================================
                        PRIMERA FILA
                        =================================================== --}}

                        <div class="detalle-row border rounded">

                            <div class="row g-2 align-items-end">


                                {{-- TIPO --}}

                                <div class="col-md-2">

                                    <label class="form-label">
                                        Tipo
                                    </label>

                                    <select class="form-select form-select-sm tipo-detalle" name="detalles[0][tipo]">

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

                                <div class="col-md-3 campo-insumo">

                                    <label class="form-label">
                                        Insumo
                                    </label>

                                    <select name="detalles[0][insumo_id]" class="form-select form-select-sm select-insumo">

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

                                <div class="col-md-3 campo-agrupado d-none">

                                    <label class="form-label">
                                        Combo
                                    </label>

                                    <select name="detalles[0][insumo_agrupado_id]"
                                        class="form-select form-select-sm select-agrupado">

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

                                <div class="col-md-3 campo-otro d-none">

                                    <label class="form-label">
                                        Otro
                                    </label>

                                    <input type="text" name="detalles[0][nombre_otro]" class="form-control form-control-sm"
                                        placeholder="Nombre del elemento">

                                </div>


                                {{-- CANTIDAD --}}

                                <div class="col-md-1">

                                    <label class="form-label">
                                        Cant.
                                    </label>

                                    <input type="number" name="detalles[0][cantidad]"
                                        class="form-control form-control-sm cantidad" step="0.01" min="0.01" value="1">

                                </div>


                                {{-- PRECIO --}}

                                <div class="col-md-2">

                                    <label class="form-label">
                                        Precio
                                    </label>

                                    <div class="input-group input-group-sm">

                                        <span class="input-group-text">
                                            Bs
                                        </span>

                                        <input type="number" name="detalles[0][precio_unitario]" class="form-control precio"
                                            step="0.01" min="0" value="0">

                                    </div>

                                </div>


                                {{-- ELIMINAR --}}

                                <div class="col-md-1">

                                    <button type="button" class="btn btn-danger btn-sm btnEliminar">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="d-flex justify-content-end mt-2">

                        <div class="total-box">

                            <strong>
                                Total insumos:
                            </strong>

                            <span id="totalInsumos" class="fw-bold ms-2">
                                Bs 0.00
                            </span>

                        </div>

                    </div>


                    <small class="text-muted d-block mt-1">

                        <i class="bi bi-info-circle"></i>

                        Insumos normales descuentan stock.
                        Los combos se descomponen automáticamente.
                        "Otros" no afectan inventario.

                    </small>

                </div>

            </div>


            {{-- ======================================================
            DISTRIBUCIÓN
            ======================================================= --}}

            <div class="card shadow-sm">

                <div class="card-header bg-warning">

                    <i class="bi bi-pie-chart"></i>

                    Distribución del costo de curación

                </div>


                <div class="card-body">

                    <div class="row g-2 align-items-end">


                        {{-- DOCTOR --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Doctor
                            </label>

                            <div class="input-group input-group-sm">

                                <input type="number" name="porcentaje_doctor" id="doctorPorcentaje" class="form-control"
                                    min="0" max="100" step="0.01" value="{{ old('porcentaje_doctor', 60) }}" required>

                                <span class="input-group-text">
                                    %
                                </span>

                            </div>

                            <small id="doctorMonto" class="text-muted distribucion-monto">
                                Bs 0.00
                            </small>

                        </div>


                        {{-- OTROS --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Otros
                            </label>

                            <div class="input-group input-group-sm">

                                <input type="number" name="porcentaje_otros" id="otrosPorcentaje" class="form-control"
                                    min="0" max="100" step="0.01" value="{{ old('porcentaje_otros', 10) }}" required>

                                <span class="input-group-text">
                                    %
                                </span>

                            </div>

                            <small id="otrosMonto" class="text-muted distribucion-monto">
                                Bs 0.00
                            </small>

                        </div>


                        {{-- EMPRESA --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Empresa
                            </label>

                            <div class="input-group input-group-sm">

                                <input type="number" name="porcentaje_empresa" id="empresaPorcentaje" class="form-control"
                                    min="0" max="100" step="0.01" value="{{ old('porcentaje_empresa', 30) }}" required>

                                <span class="input-group-text">
                                    %
                                </span>

                            </div>

                            <small id="empresaMonto" class="text-muted distribucion-monto">
                                Bs 0.00
                            </small>

                        </div>

                    </div>


                    <div id="alertaDistribucion" class="alert alert-success py-2 mt-2 mb-0">

                        <i class="bi bi-check-circle"></i>

                        Distribución correcta: 100%

                    </div>


                    <small class="text-muted d-block mt-1">

                        <i class="bi bi-info-circle"></i>

                        La distribución se aplica únicamente al
                        <strong>costo de la curación</strong>,
                        no al valor de los insumos.

                    </small>

                </div>

            </div>


            {{-- ======================================================
            BOTONES FIJOS
            ======================================================= --}}

            <div class="acciones-fijas">

                <div class="d-flex justify-content-end gap-2">

                    <a href="{{ route('curaciones.index') }}" class="btn btn-secondary">

                        <i class="bi bi-x-circle"></i>

                        Cancelar

                    </a>


                    <button type="submit" class="btn btn-success" id="btnRegistrar">

                        <i class="bi bi-save"></i>

                        Registrar Curación

                    </button>

                </div>

            </div>

        </form>

    </div>


    {{-- ==========================================================
    JAVASCRIPT
    =========================================================== --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            let contador = 1;

            const container =
                document.getElementById('detallesContainer');

            const btnAgregar =
                document.getElementById('btnAgregarDetalle');

            const form =
                document.getElementById('formCuracion');


            /*
            |--------------------------------------------------------------------------
            | CONFIGURAR FILA
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


                function actualizarCampos() {

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


                tipo.addEventListener(
                    'change',
                    actualizarCampos
                );


                actualizarCampos();
            }


            configurarFila(
                document.querySelector('.detalle-row')
            );


            /*
            |--------------------------------------------------------------------------
            | AGREGAR FILA
            |--------------------------------------------------------------------------
            */

            btnAgregar.addEventListener(
                'click',
                function () {

                    const fila =
                        document.createElement('div');

                    fila.className =
                        'detalle-row border rounded';


                    fila.innerHTML = `

                    <div class="row g-2 align-items-end">

                        <div class="col-md-2">

                            <label class="form-label">
                                Tipo
                            </label>

                            <select
                                class="form-select form-select-sm tipo-detalle"
                                name="detalles[${contador}][tipo]"
                            >

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


                        <div class="col-md-3 campo-insumo">

                            <label class="form-label">
                                Insumo
                            </label>

                            <select
                                name="detalles[${contador}][insumo_id]"
                                class="form-select form-select-sm select-insumo"
                            >

                                <option value="">
                                    -- Seleccionar --
                                </option>

                                @foreach($insumos as $insumo)

                                    <option
                                        value="{{ $insumo->id }}"
                                        data-precio="{{ $insumo->precio_venta }}"
                                    >

                                        {{ $insumo->codigo }}
                                        -
                                        {{ $insumo->nombre }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-3 campo-agrupado d-none">

                            <label class="form-label">
                                Combo
                            </label>

                            <select
                                name="detalles[${contador}][insumo_agrupado_id]"
                                class="form-select form-select-sm select-agrupado"
                            >

                                <option value="">
                                    -- Seleccionar combo --
                                </option>

                                @foreach($agrupados as $agrupado)

                                    <option
                                        value="{{ $agrupado->id }}"
                                        data-precio="{{ $agrupado->precio }}"
                                    >

                                        {{ $agrupado->codigo }}
                                        -
                                        {{ $agrupado->nombre }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        <div class="col-md-3 campo-otro d-none">

                            <label class="form-label">
                                Otro
                            </label>

                            <input
                                type="text"
                                name="detalles[${contador}][nombre_otro]"
                                class="form-control form-control-sm"
                                placeholder="Nombre del elemento"
                            >

                        </div>


                        <div class="col-md-1">

                            <label class="form-label">
                                Cant.
                            </label>

                            <input
                                type="number"
                                name="detalles[${contador}][cantidad]"
                                class="form-control form-control-sm cantidad"
                                step="0.01"
                                min="0.01"
                                value="1"
                            >

                        </div>


                        <div class="col-md-2">

                            <label class="form-label">
                                Precio
                            </label>

                            <div class="input-group input-group-sm">

                                <span class="input-group-text">
                                    Bs
                                </span>

                                <input
                                    type="number"
                                    name="detalles[${contador}][precio_unitario]"
                                    class="form-control precio"
                                    step="0.01"
                                    min="0"
                                    value="0"
                                >

                            </div>

                        </div>


                        <div class="col-md-1">

                            <button
                                type="button"
                                class="btn btn-danger btn-sm btnEliminar"
                            >

                                <i class="bi bi-trash"></i>

                            </button>

                        </div>

                    </div>

                `;


                    container.appendChild(fila);

                    configurarFila(fila);

                    contador++;

                }
            );


            /*
            |--------------------------------------------------------------------------
            | ELIMINAR
            |--------------------------------------------------------------------------
            */

            container.addEventListener(
                'click',
                function (e) {

                    const boton =
                        e.target.closest('.btnEliminar');

                    if (!boton) {
                        return;
                    }


                    const filas =
                        container.querySelectorAll(
                            '.detalle-row'
                        );


                    if (filas.length <= 1) {

                        alert(
                            'Debe existir al menos un elemento.'
                        );

                        return;
                    }


                    boton
                        .closest('.detalle-row')
                        .remove();


                    calcularTotales();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | PRECIO AUTOMÁTICO
            |--------------------------------------------------------------------------
            */

            container.addEventListener(
                'change',
                function (e) {

                    if (
                        e.target.classList.contains(
                            'select-insumo'
                        )
                        ||
                        e.target.classList.contains(
                            'select-agrupado'
                        )
                    ) {

                        const opcion =
                            e.target.options[
                            e.target.selectedIndex
                            ];


                        const precio =
                            opcion.dataset.precio || 0;


                        const fila =
                            e.target.closest(
                                '.detalle-row'
                            );


                        const inputPrecio =
                            fila.querySelector(
                                '.precio'
                            );


                        inputPrecio.value =
                            parseFloat(precio)
                                .toFixed(2);


                        calcularTotales();

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | CALCULAR TOTALES
            |--------------------------------------------------------------------------
            */

            function calcularTotales() {

                let totalInsumos = 0;


                document
                    .querySelectorAll('.detalle-row')
                    .forEach(function (fila) {

                        const cantidad =
                            parseFloat(
                                fila.querySelector(
                                    '.cantidad'
                                )?.value
                            ) || 0;


                        const precio =
                            parseFloat(
                                fila.querySelector(
                                    '.precio'
                                )?.value
                            ) || 0;


                        totalInsumos +=
                            cantidad * precio;

                    });


                totalInsumos =
                    parseFloat(
                        totalInsumos.toFixed(2)
                    );


                /*
                | TOTAL INSUMOS
                */

                document.getElementById(
                    'totalInsumos'
                ).innerText =
                    'Bs ' +
                    totalInsumos.toFixed(2);


                document.getElementById(
                    'totalInsumosTexto'
                ).value =
                    totalInsumos.toFixed(2);


                /*
                | COSTO CURACIÓN
                */

                const costoCuracion =
                    parseFloat(
                        document.getElementById(
                            'costoCuracion'
                        ).value
                    ) || 0;


                /*
                | TOTAL GENERAL
                */

                const totalGeneral =
                    totalInsumos
                    + costoCuracion;


                document.getElementById(
                    'totalGeneral'
                ).value =
                    totalGeneral.toFixed(2);


                /*
                | MUY IMPORTANTE:
                |
                | La distribución NO utiliza totalGeneral.
                |
                | Utiliza únicamente costoCuracion.
                */

                calcularDistribucion(
                    costoCuracion
                );

            }


            /*
            |--------------------------------------------------------------------------
            | CALCULAR DISTRIBUCIÓN
            |--------------------------------------------------------------------------
            */

            function calcularDistribucion(
                costoCuracion
            ) {

                const doctor =
                    parseFloat(
                        document.getElementById(
                            'doctorPorcentaje'
                        ).value
                    ) || 0;


                const otros =
                    parseFloat(
                        document.getElementById(
                            'otrosPorcentaje'
                        ).value
                    ) || 0;


                const empresa =
                    parseFloat(
                        document.getElementById(
                            'empresaPorcentaje'
                        ).value
                    ) || 0;


                const suma =
                    doctor
                    + otros
                    + empresa;


                /*
                |--------------------------------------------------------------------------
                | MONTOS
                |--------------------------------------------------------------------------
                */

                const montoDoctor =
                    costoCuracion
                    * doctor
                    / 100;


                const montoOtros =
                    costoCuracion
                    * otros
                    / 100;


                const montoEmpresa =
                    costoCuracion
                    * empresa
                    / 100;


                document.getElementById(
                    'doctorMonto'
                ).innerText =
                    'Bs ' +
                    montoDoctor.toFixed(2);


                document.getElementById(
                    'otrosMonto'
                ).innerText =
                    'Bs ' +
                    montoOtros.toFixed(2);


                document.getElementById(
                    'empresaMonto'
                ).innerText =
                    'Bs ' +
                    montoEmpresa.toFixed(2);


                /*
                |--------------------------------------------------------------------------
                | ALERTA
                |--------------------------------------------------------------------------
                */

                const alerta =
                    document.getElementById(
                        'alertaDistribucion'
                    );


                if (Math.abs(suma - 100) < 0.001) {

                    alerta.className =
                        'alert alert-success py-2 mt-2 mb-0';


                    alerta.innerHTML = `

                    <i class="bi bi-check-circle"></i>

                    Distribución correcta: 100%

                `;

                }

                else if (suma > 100) {

                    alerta.className =
                        'alert alert-danger py-2 mt-2 mb-0';


                    alerta.innerHTML = `

                    <i class="bi bi-exclamation-triangle"></i>

                    La distribución supera el 100%.
                    Actualmente: ${suma.toFixed(2)}%

                `;

                }

                else {

                    alerta.className =
                        'alert alert-warning py-2 mt-2 mb-0';


                    alerta.innerHTML = `

                    <i class="bi bi-exclamation-triangle"></i>

                    Falta distribuir:
                    ${(100 - suma).toFixed(2)}%

                `;

                }

            }


            /*
            |--------------------------------------------------------------------------
            | EVENTOS
            |--------------------------------------------------------------------------
            */

            container.addEventListener(
                'input',
                function () {

                    calcularTotales();

                }
            );


            document.getElementById(
                'costoCuracion'
            ).addEventListener(
                'input',
                function () {

                    calcularTotales();

                }
            );


            document.getElementById(
                'doctorPorcentaje'
            ).addEventListener(
                'input',
                function () {

                    calcularTotales();

                }
            );


            document.getElementById(
                'otrosPorcentaje'
            ).addEventListener(
                'input',
                function () {

                    calcularTotales();

                }
            );


            document.getElementById(
                'empresaPorcentaje'
            ).addEventListener(
                'input',
                function () {

                    calcularTotales();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | VALIDAR ANTES DE GUARDAR
            |--------------------------------------------------------------------------
            */

            form.addEventListener(
                'submit',
                function (e) {

                    const doctor =
                        parseFloat(
                            document.getElementById(
                                'doctorPorcentaje'
                            ).value
                        ) || 0;


                    const otros =
                        parseFloat(
                            document.getElementById(
                                'otrosPorcentaje'
                            ).value
                        ) || 0;


                    const empresa =
                        parseFloat(
                            document.getElementById(
                                'empresaPorcentaje'
                            ).value
                        ) || 0;


                    const suma =
                        doctor
                        + otros
                        + empresa;


                    if (
                        Math.abs(suma - 100) > 0.001
                    ) {

                        e.preventDefault();


                        alert(
                            'La distribución debe sumar exactamente 100%. Actualmente suma '
                            + suma.toFixed(2)
                            + '%.'
                        );


                        return false;
                    }


                    const boton =
                        document.getElementById(
                            'btnRegistrar'
                        );


                    boton.disabled = true;


                    boton.innerHTML = `

                    <span
                        class="spinner-border spinner-border-sm me-1"
                    ></span>

                    Registrando...

                `;

                }
            );


            /*
            |--------------------------------------------------------------------------
            | INICIALIZAR
            |--------------------------------------------------------------------------
            */

            calcularTotales();

        });

    </script>

@endsection