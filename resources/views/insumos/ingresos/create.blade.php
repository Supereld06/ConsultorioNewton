@extends('layouts.app')

@section('content')

    <div class="container">

        {{-- ================================================= --}}
        {{-- ENCABEZADO --}}
        {{-- ================================================= --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="mb-1">

                    <i class="bi bi-box-arrow-in-down text-success"></i>

                    Nuevo Ingreso de Insumos

                </h3>

                <small class="text-muted">

                    Registrar medicamentos e insumos médicos

                </small>

            </div>


            <a href="{{ route('insumos.ingresos.index') }}" class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>

                Volver

            </a>

        </div>


        {{-- ================================================= --}}
        {{-- MENSAJE DE ÉXITO --}}
        {{-- ================================================= --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show" role="alert">

                <i class="bi bi-check-circle-fill"></i>

                <strong>Correcto:</strong>

                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- ================================================= --}}
        {{-- MENSAJE DE ERROR --}}
        {{-- ================================================= --}}

        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show" role="alert">

                <i class="bi bi-exclamation-triangle-fill"></i>

                <strong>Error:</strong>

                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- ================================================= --}}
        {{-- ERRORES DE VALIDACIÓN --}}
        {{-- ================================================= --}}

        @if($errors->any())

            <div class="alert alert-danger alert-dismissible fade show" role="alert">

                <i class="bi bi-exclamation-triangle-fill"></i>

                <strong>
                    Hay errores en el formulario:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- ================================================= --}}
        {{-- FORMULARIO --}}
        {{-- ================================================= --}}

        <form action="{{ route('insumos.ingresos.store') }}" method="POST" id="formIngreso">

            @csrf


            {{-- ================================================= --}}
            {{-- DATOS GENERALES --}}
            {{-- ================================================= --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-dark text-white">

                    <i class="bi bi-file-earmark-text"></i>

                    Datos del ingreso

                </div>


                <div class="card-body">
                    <div class="row g-3">

                        {{-- FECHA --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Fecha
                                <span class="text-danger">*</span>
                            </label>

                            <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" class="form-control"
                                required>

                        </div>


                        {{-- PROVEEDOR --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Proveedor
                            </label>

                            <input type="text" name="proveedor" value="{{ old('proveedor') }}" class="form-control"
                                maxlength="255" placeholder="Nombre del proveedor">

                        </div>


                        {{-- CAJA --}}
                        <div class="col-md-4">

                            <label class="form-label">
                                Caja
                                <span class="text-danger">*</span>
                            </label>

                            <select name="caja_id" id="caja_id" class="form-select" required>

                                @foreach($cajas as $caja)

                                    <option value="{{ $caja->id }}" {{ old('caja_id', 3) == $caja->id ? 'selected' : '' }}>
                                        Caja {{ $caja->id }} -
                                        {{ $caja->nombre }}
                                        | Bs. {{ number_format($caja->saldo, 2) }}
                                    </option>

                                @endforeach

                            </select>

                            <small class="text-muted">
                                Por defecto se utilizará Caja 3 - Otros.
                            </small>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- AGREGAR INSUMO --}}
            {{-- ================================================= --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-dark text-white">

                    <i class="bi bi-plus-circle"></i>

                    Agregar insumo

                </div>


                <div class="card-body">

                    <div class="row g-3 align-items-end">


                        {{-- INSUMO --}}

                        <div class="col-md-5">

                            <label class="form-label">

                                Insumo

                                <span class="text-danger">*</span>

                            </label>


                            <select id="insumoSeleccionado" class="form-select">

                                <option value="">
                                    Seleccione un insumo...
                                </option>

                                @foreach($insumos as $insumo)

                                    <option value="{{ $insumo->id }}" data-codigo="{{ $insumo->codigo }}"
                                        data-nombre="{{ $insumo->nombre }}"
                                        data-precio-compra="{{ $insumo->precio_compra ?? 0 }}"
                                        data-precio-venta="{{ $insumo->precio_venta ?? 0 }}">

                                        {{ $insumo->codigo }}
                                        -
                                        {{ $insumo->nombre }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- CANTIDAD --}}

                        <div class="col-md-2">

                            <label class="form-label">

                                Cantidad

                            </label>

                            <input type="number" id="cantidadSeleccionada" class="form-control" value="1" min="0.01"
                                step="0.01">

                        </div>


                        {{-- PRECIO COMPRA --}}

                        <div class="col-md-2">

                            <label class="form-label">

                                Precio compra

                            </label>

                            <input type="number" id="precioSeleccionado" class="form-control" value="0" min="0" step="0.01">

                        </div>


                        {{-- PRECIO VENTA --}}

                        <div class="col-md-2">

                            <label class="form-label">

                                Precio venta

                            </label>

                            <input type="number" id="precioVentaSeleccionado" class="form-control" value="0" min="0"
                                step="0.01">

                        </div>


                        {{-- BOTÓN --}}

                        <div class="col-md-1">

                            <button type="button" id="btnAgregar" class="btn btn-success w-100" title="Agregar insumo">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- DETALLE DEL INGRESO --}}
            {{-- ================================================= --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-dark text-white">

                    <i class="bi bi-cart3"></i>

                    Detalle del ingreso

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-dark">

                                <tr>

                                    <th width="60">
                                        #
                                    </th>

                                    <th>
                                        Código
                                    </th>

                                    <th>
                                        Insumo
                                    </th>

                                    <th width="130">
                                        Cantidad
                                    </th>

                                    <th width="160">
                                        Precio compra
                                    </th>

                                    <th width="160">
                                        Precio venta
                                    </th>

                                    <th width="150">
                                        Subtotal
                                    </th>

                                    <th width="80">
                                        Acción
                                    </th>

                                </tr>

                            </thead>


                            <tbody id="detalleIngreso">

                                <tr id="filaVacia">

                                    <td colspan="8" class="text-center py-5">

                                        <i class="bi bi-cart-x fs-1 text-muted"></i>

                                        <p class="text-muted mb-0 mt-2">

                                            No hay insumos agregados.

                                        </p>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>


                {{-- TOTAL --}}

                <div class="card-footer">

                    <div class="row justify-content-end">

                        <div class="col-md-4">

                            <div class="d-flex justify-content-between">

                                <span>
                                    Total del ingreso:
                                </span>

                                <strong class="fs-4">

                                    Bs.

                                    <span id="totalIngreso">
                                        0.00
                                    </span>

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- INFORMACIÓN DEL PAGO --}}
            {{-- ================================================= --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-dark text-white">

                    <i class="bi bi-cash-coin"></i>

                    Información del pago

                </div>


                <div class="card-body">

                    <div class="row justify-content-end">

                        <div class="col-md-5">


                            {{-- MONTO PAGADO --}}

                            <div class="mb-3">

                                <label class="form-label">

                                    Monto pagado

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <div class="input-group">

                                    <span class="input-group-text">

                                        Bs.

                                    </span>


                                    <input type="number" name="monto_pagado" id="monto_pagado"
                                        value="{{ old('monto_pagado', 0) }}" class="form-control" min="0" step="0.01"
                                        required>

                                </div>

                                <small class="text-muted">

                                    Se colocará automáticamente el total
                                    del ingreso.

                                </small>

                            </div>


                            {{-- RESUMEN --}}

                            <div class="border rounded p-3 bg-light">

                                <div class="d-flex justify-content-between mb-2">

                                    <span>
                                        Total:
                                    </span>

                                    <strong>

                                        Bs.

                                        <span id="totalPago">
                                            0.00
                                        </span>

                                    </strong>

                                </div>


                                <div class="d-flex justify-content-between mb-2">

                                    <span>
                                        Pagado:
                                    </span>

                                    <strong class="text-success">

                                        Bs.

                                        <span id="pagoMostrado">
                                            0.00
                                        </span>

                                    </strong>

                                </div>


                                <hr>


                                <div class="d-flex justify-content-between">

                                    <strong>
                                        Saldo pendiente:
                                    </strong>

                                    <strong class="text-danger">

                                        Bs.

                                        <span id="saldoPendiente">
                                            0.00
                                        </span>

                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- OBSERVACIÓN --}}
            {{-- ================================================= --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header">

                    <i class="bi bi-chat-left-text"></i>

                    Observación

                </div>


                <div class="card-body">

                    <textarea name="observacion" class="form-control" rows="3"
                        placeholder="Observaciones del ingreso...">{{ old('observacion') }}</textarea>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- BOTONES --}}
            {{-- ================================================= --}}

            <div class="d-flex justify-content-end gap-2 mb-5">

                <a href="{{ route('insumos.ingresos.index') }}" class="btn btn-secondary">

                    <i class="bi bi-x-circle"></i>

                    Cancelar

                </a>


                <button type="submit" class="btn btn-success" id="btnRegistrar">

                    <i class="bi bi-check-circle"></i>

                    Registrar Ingreso

                </button>

            </div>


        </form>

    </div>


    {{-- ================================================= --}}
    {{-- TOM SELECT --}}
    {{-- ================================================= --}}

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.bootstrap5.min.css">


    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>


    {{-- ================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ================================================= --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {


            /* =========================================================
             * ARRAY TEMPORAL DE PRODUCTOS
             * ========================================================= */

            let productos = [];


            /* =========================================================
             * ELEMENTOS DEL DOM
             * ========================================================= */

            const selectInsumo =
                document.getElementById('insumoSeleccionado');


            const cantidadInput =
                document.getElementById('cantidadSeleccionada');


            const precioCompraInput =
                document.getElementById('precioSeleccionado');


            const precioVentaInput =
                document.getElementById('precioVentaSeleccionado');


            const btnAgregar =
                document.getElementById('btnAgregar');


            const detalle =
                document.getElementById('detalleIngreso');


            const montoPagado =
                document.getElementById('monto_pagado');


            const form =
                document.getElementById('formIngreso');


            /* =========================================================
             * INICIALIZAR TOM SELECT
             *
             * Permite buscar por:
             * - Código
             * - Nombre
             *
             * ========================================================= */

            const buscadorInsumo =
                new TomSelect('#insumoSeleccionado', {

                    placeholder:
                        'Escriba código o nombre del insumo...',

                    allowEmptyOption:
                        true,

                    create:
                        false,

                    maxOptions:
                        1000,

                    searchField:
                        ['text'],

                    closeAfterSelect:
                        true,

                    onChange:
                        function (value) {

                            if (!value) {

                                precioCompraInput.value =
                                    '0';

                                precioVentaInput.value =
                                    '0';

                                return;
                            }


                            const option =
                                document.querySelector(
                                    `#insumoSeleccionado option[value="${value}"]`
                                );


                            if (!option) {

                                return;
                            }


                            /* PRECIO DE COMPRA */

                            precioCompraInput.value =
                                option.dataset.precioCompra || 0;


                            /* PRECIO DE VENTA */

                            precioVentaInput.value =
                                option.dataset.precioVenta || 0;

                        }

                });


            /* =========================================================
             * AGREGAR INSUMO
             * ========================================================= */

            btnAgregar.addEventListener(
                'click',
                function () {


                    const insumoId =
                        selectInsumo.value;


                    const option =
                        selectInsumo.options[
                        selectInsumo.selectedIndex
                        ];


                    const cantidad =
                        parseFloat(
                            cantidadInput.value
                        );


                    const precioCompra =
                        parseFloat(
                            precioCompraInput.value
                        );


                    const precioVenta =
                        parseFloat(
                            precioVentaInput.value
                        );


                    /* =================================================
                     * VALIDAR INSUMO
                     * ================================================= */

                    if (!insumoId) {

                        alert(
                            'Debe seleccionar un insumo.'
                        );

                        return;
                    }


                    /* =================================================
                     * VALIDAR CANTIDAD
                     * ================================================= */

                    if (
                        isNaN(cantidad) ||
                        cantidad <= 0
                    ) {

                        alert(
                            'La cantidad debe ser mayor a cero.'
                        );

                        cantidadInput.focus();

                        return;
                    }


                    /* =================================================
                     * VALIDAR PRECIO COMPRA
                     * ================================================= */

                    if (
                        isNaN(precioCompra) ||
                        precioCompra < 0
                    ) {

                        alert(
                            'El precio de compra no es válido.'
                        );

                        precioCompraInput.focus();

                        return;
                    }


                    /* =================================================
                     * VALIDAR PRECIO VENTA
                     * ================================================= */

                    if (
                        isNaN(precioVenta) ||
                        precioVenta < 0
                    ) {

                        alert(
                            'El precio de venta no es válido.'
                        );

                        precioVentaInput.focus();

                        return;
                    }


                    /* =================================================
                     * VERIFICAR SI EL INSUMO YA EXISTE
                     * ================================================= */

                    const existente =
                        productos.find(
                            producto =>
                                producto.insumo_id == insumoId
                        );


                    if (existente) {


                        /* SUMAR CANTIDAD */

                        existente.cantidad +=
                            cantidad;


                        /* ACTUALIZAR PRECIOS */

                        existente.precio_compra =
                            precioCompra;


                        existente.precio_venta =
                            precioVenta;


                    } else {


                        /* =================================================
                         * AGREGAR NUEVO PRODUCTO
                         * ================================================= */

                        productos.push({

                            insumo_id:
                                insumoId,

                            codigo:
                                option.dataset.codigo,

                            nombre:
                                option.dataset.nombre,

                            cantidad:
                                cantidad,

                            precio_compra:
                                precioCompra,

                            precio_venta:
                                precioVenta

                        });

                    }


                    /* =================================================
                     * LIMPIAR CAMPOS
                     * ================================================= */

                    buscadorInsumo.clear();


                    cantidadInput.value =
                        '1';


                    precioCompraInput.value =
                        '0';


                    precioVentaInput.value =
                        '0';


                    /* =================================================
                     * RENDERIZAR
                     * ================================================= */

                    renderizar();

                }
            );


            /* =========================================================
             * RENDERIZAR TABLA
             * ========================================================= */

            function renderizar() {


                detalle.innerHTML =
                    '';


                let total =
                    0;


                /* =================================================
                 * SIN PRODUCTOS
                 * ================================================= */

                if (productos.length === 0) {

                    detalle.innerHTML = `

                                <tr>

                                    <td colspan="8"
                                        class="text-center py-5">

                                        <i class="bi bi-cart-x fs-1 text-muted"></i>

                                        <p class="text-muted mb-0 mt-2">

                                            No hay insumos agregados.

                                        </p>

                                    </td>

                                </tr>

                            `;

                }


                /* =================================================
                 * MOSTRAR PRODUCTOS
                 * ================================================= */

                productos.forEach(
                    (producto, index) => {


                        const subtotal =
                            producto.cantidad *
                            producto.precio_compra;


                        total +=
                            subtotal;


                        detalle.innerHTML += `

                                    <tr>

                                        <td>

                                            ${index + 1}

                                        </td>


                                        <td>

                                            <span class="badge bg-secondary">

                                                ${producto.codigo}

                                            </span>

                                        </td>


                                        <td>

                                            <strong>

                                                ${producto.nombre}

                                            </strong>

                                        </td>


                                        <td>

                                            <input type="number"
                                                   class="form-control cantidadProducto"
                                                   data-index="${index}"
                                                   value="${producto.cantidad}"
                                                   min="0.01"
                                                   step="0.01">

                                        </td>


                                        <td>

                                            <input type="number"
                                                   class="form-control precioProducto"
                                                   data-index="${index}"
                                                   value="${producto.precio_compra}"
                                                   min="0"
                                                   step="0.01">

                                        </td>


                                        <td>

                                            <input type="number"
                                                   class="form-control precioVentaProducto"
                                                   data-index="${index}"
                                                   value="${producto.precio_venta}"
                                                   min="0"
                                                   step="0.01">

                                        </td>


                                        <td>

                                            <strong>

                                                Bs.
                                                ${subtotal.toFixed(2)}

                                            </strong>

                                        </td>


                                        <td>

                                            <button type="button"
                                                    class="btn btn-danger btn-sm btnEliminar"
                                                    data-index="${index}"
                                                    title="Eliminar">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </td>

                                    </tr>

                                `;

                    }
                );


                /* =================================================
                 * MOSTRAR TOTAL
                 * ================================================= */

                document.getElementById(
                    'totalIngreso'
                ).textContent =
                    total.toFixed(2);


                document.getElementById(
                    'totalPago'
                ).textContent =
                    total.toFixed(2);


                /* =================================================
                 * COLOCAR TOTAL AUTOMÁTICAMENTE EN MONTO PAGADO
                 * ================================================= */

                establecerPagoAutomatico();


                /* =================================================
                 * EVENTO CANTIDAD
                 * ================================================= */

                document
                    .querySelectorAll('.cantidadProducto')
                    .forEach(input => {


                        input.addEventListener(
                            'change',
                            function () {


                                const index =
                                    parseInt(
                                        this.dataset.index
                                    );


                                let valor =
                                    parseFloat(
                                        this.value
                                    );


                                if (
                                    isNaN(valor) ||
                                    valor <= 0
                                ) {

                                    valor =
                                        0.01;

                                }


                                productos[index].cantidad =
                                    valor;


                                renderizar();

                            }
                        );

                    });


                /* =================================================
                 * EVENTO PRECIO COMPRA
                 * ================================================= */

                document
                    .querySelectorAll('.precioProducto')
                    .forEach(input => {


                        input.addEventListener(
                            'change',
                            function () {


                                const index =
                                    parseInt(
                                        this.dataset.index
                                    );


                                let valor =
                                    parseFloat(
                                        this.value
                                    );


                                if (
                                    isNaN(valor) ||
                                    valor < 0
                                ) {

                                    valor =
                                        0;

                                }


                                productos[index].precio_compra =
                                    valor;


                                renderizar();

                            }
                        );

                    });


                /* =================================================
                 * EVENTO PRECIO VENTA
                 * ================================================= */

                document
                    .querySelectorAll('.precioVentaProducto')
                    .forEach(input => {


                        input.addEventListener(
                            'change',
                            function () {


                                const index =
                                    parseInt(
                                        this.dataset.index
                                    );


                                let valor =
                                    parseFloat(
                                        this.value
                                    );


                                if (
                                    isNaN(valor) ||
                                    valor < 0
                                ) {

                                    valor =
                                        0;

                                }


                                productos[index].precio_venta =
                                    valor;


                                renderizar();

                            }
                        );

                    });


                /* =================================================
                 * EVENTO ELIMINAR
                 * ================================================= */

                document
                    .querySelectorAll('.btnEliminar')
                    .forEach(button => {


                        button.addEventListener(
                            'click',
                            function () {


                                const index =
                                    parseInt(
                                        this.dataset.index
                                    );


                                productos.splice(
                                    index,
                                    1
                                );


                                renderizar();

                            }
                        );

                    });


                /* =================================================
                 * ACTUALIZAR INPUTS OCULTOS
                 * ================================================= */

                actualizarCamposHidden();

            }


            /* =========================================================
             * ACTUALIZAR INPUTS HIDDEN
             * ========================================================= */

            function actualizarCamposHidden() {


                /* ELIMINAR LOS ANTERIORES */

                document
                    .querySelectorAll('.productoHidden')
                    .forEach(
                        input =>
                            input.remove()
                    );


                /* CREAR LOS NUEVOS */

                productos.forEach(
                    (producto, index) => {


                        crearHidden(
                            `productos[${index}][insumo_id]`,
                            producto.insumo_id
                        );


                        crearHidden(
                            `productos[${index}][cantidad]`,
                            producto.cantidad
                        );


                        crearHidden(
                            `productos[${index}][precio_compra]`,
                            producto.precio_compra
                        );


                        crearHidden(
                            `productos[${index}][precio_venta]`,
                            producto.precio_venta
                        );

                    }
                );

            }


            /* =========================================================
             * CREAR INPUT HIDDEN
             * ========================================================= */

            function crearHidden(
                name,
                value
            ) {


                const input =
                    document.createElement(
                        'input'
                    );


                input.type =
                    'hidden';


                input.name =
                    name;


                input.value =
                    value;


                input.classList.add(
                    'productoHidden'
                );


                form.appendChild(
                    input
                );

            }


            /* =========================================================
             * EVENTO MONTO PAGADO
             *
             * Permite que el usuario modifique manualmente
             * el monto después de que se coloque automáticamente.
             * ========================================================= */

            montoPagado.addEventListener(
                'input',
                actualizarPago
            );


            /* =========================================================
             * ACTUALIZAR INFORMACIÓN DEL PAGO
             * ========================================================= */

            function actualizarPago() {


                const total =
                    calcularTotal();


                const pagado =
                    parseFloat(
                        montoPagado.value
                    ) || 0;


                /* TOTAL */

                document.getElementById(
                    'totalPago'
                ).textContent =
                    total.toFixed(2);


                /* PAGADO */

                document.getElementById(
                    'pagoMostrado'
                ).textContent =
                    pagado.toFixed(2);


                /* SALDO */

                const pendiente =
                    Math.max(
                        total - pagado,
                        0
                    );


                document.getElementById(
                    'saldoPendiente'
                ).textContent =
                    pendiente.toFixed(2);

            }


            /* =========================================================
             * ESTABLECER PAGO AUTOMÁTICAMENTE
             *
             * Cada vez que cambia el detalle del ingreso,
             * el monto pagado toma el valor del total.
             * ========================================================= */

            function establecerPagoAutomatico() {


                const total =
                    calcularTotal();


                montoPagado.value =
                    total.toFixed(2);


                actualizarPago();

            }


            /* =========================================================
             * CALCULAR TOTAL
             * ========================================================= */

            function calcularTotal() {


                return productos.reduce(

                    (
                        total,
                        producto
                    ) => {


                        return total +

                            (
                                producto.cantidad *
                                producto.precio_compra
                            );

                    },

                    0

                );

            }


            /* =========================================================
             * VALIDAR FORMULARIO
             * ========================================================= */

            form.addEventListener(
                'submit',
                function (event) {


                    const total =
                        calcularTotal();


                    const pagado =
                        parseFloat(
                            montoPagado.value
                        ) || 0;


                    /* =================================================
                     * NO HAY PRODUCTOS
                     * ================================================= */

                    if (
                        productos.length === 0
                    ) {

                        event.preventDefault();


                        alert(
                            'Debe agregar al menos un insumo.'
                        );


                        return;

                    }


                    /* =================================================
                     * MONTO PAGADO MAYOR AL TOTAL
                     * ================================================= */

                    if (
                        pagado > total
                    ) {

                        event.preventDefault();


                        alert(
                            'El monto pagado no puede ser mayor al total del ingreso.'
                        );


                        montoPagado.focus();


                        return;

                    }


                    /* =================================================
                     * ACTUALIZAR HIDDEN
                     * ================================================= */

                    actualizarCamposHidden();


                    /* =================================================
                     * EVITAR DOBLE REGISTRO
                     * ================================================= */

                    const boton =
                        document.getElementById(
                            'btnRegistrar'
                        );


                    boton.disabled =
                        true;


                    boton.innerHTML = `

                                <span class="spinner-border spinner-border-sm"
                                      role="status"
                                      aria-hidden="true">
                                </span>

                                Registrando...

                            `;

                }
            );


            /* =========================================================
             * INICIALIZAR
             * ========================================================= */

            renderizar();


        });

    </script>

@endsection