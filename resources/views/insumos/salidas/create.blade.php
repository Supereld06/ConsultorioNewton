@extends('layouts.app')

@section('content')

    <div class="container">

        
        {{-- ==========================================
        ENCABEZADO
        ========================================== --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="mb-1">
                    <i class="bi bi-box-arrow-up text-danger"></i>
                    Nueva Salida de Insumos
                </h3>

                <small class="text-muted">
                    Registrar salida de insumos del inventario
                </small>

            </div>

            <a href="{{ route('insumos.salidas.index') }}" class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>
                Volver

            </a>

        </div>


        {{-- ==========================================
        MENSAJES
        ========================================== --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="bi bi-check-circle-fill"></i>

                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="bi bi-exclamation-triangle-fill"></i>

                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    <i class="bi bi-exclamation-triangle"></i>
                    Hay errores en el formulario:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form action="{{ route('insumos.salidas.store') }}" method="POST" id="formSalida">

            @csrf


            {{-- ==========================================
            INFORMACIÓN GENERAL
            ========================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-dark text-white">

                    <i class="bi bi-info-circle"></i>
                    Información de la salida

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-4">

                            <label class="form-label">
                                Fecha
                            </label>

                            <input type="date" name="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}"
                                required>

                        </div>


                        <div class="col-md-8">

                            <label class="form-label">
                                Motivo
                            </label>

                            <input type="text" name="motivo" class="form-control" value="{{ old('motivo') }}"
                                placeholder="Ej.: Venta, entrega al paciente, consumo interno...">

                        </div>

                    </div>

                </div>

            </div>


            {{-- ==========================================
            AGREGAR INSUMO
            ========================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-dark text-white">

                    <i class="bi bi-plus-circle"></i>
                    Agregar insumos

                </div>


                <div class="card-body">

                    <div class="row g-3 align-items-end">

                        {{-- INSUMO --}}

                        <div class="col-md-5">

                            <label class="form-label">
                                Insumo
                            </label>

                            <select id="selectInsumo" class="form-select">

                                <option value="">
                                    Seleccione un insumo
                                </option>

                                @foreach($insumos as $insumo)

                                    <option value="{{ $insumo->id }}" data-nombre="{{ $insumo->nombre }}"
                                        data-codigo="{{ $insumo->codigo }}" data-stock="{{ $insumo->stock }}"
                                        data-precio="{{ $insumo->precio_venta }}">

                                        {{ $insumo->codigo }}
                                        -
                                        {{ $insumo->nombre }}

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- STOCK --}}

                        <div class="col-md-2">

                            <label class="form-label">
                                Stock disponible
                            </label>

                            <input type="text" id="stockDisponible" class="form-control text-center" value="0" readonly>

                        </div>


                        {{-- CANTIDAD --}}

                        <div class="col-md-2">

                            <label class="form-label">
                                Cantidad
                            </label>

                            <input type="number" id="cantidad" class="form-control" min="0.01" step="0.01" value="1">

                        </div>


                        {{-- PRECIO --}}

                        <div class="col-md-2">

                            <label class="form-label">
                                Precio venta
                            </label>

                            <input type="number" id="precioVenta" class="form-control" step="0.01" readonly>

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


            {{-- ==========================================
            TABLA DE INSUMOS
            ========================================== --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-dark text-white">

                    <i class="bi bi-cart3"></i>
                    Insumos seleccionados

                </div>


                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-dark">

                                <tr>

                                    <th>#</th>

                                    <th>Código</th>

                                    <th>Insumo</th>

                                    <th>Stock</th>

                                    <th>Cantidad</th>

                                    <th>Precio venta</th>

                                    <th>Subtotal</th>

                                    <th>Acción</th>

                                </tr>

                            </thead>


                            <tbody id="tablaInsumos">

                                <tr id="filaVacia">

                                    <td colspan="8" class="text-center text-muted py-4">

                                        <i class="bi bi-inbox fs-2"></i>

                                        <br>

                                        No hay insumos agregados a la salida.

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- ==========================================
            RESUMEN
            ========================================== --}}

            <div class="row justify-content-end">

                <div class="col-md-5">

                    <div class="card shadow-sm">

                        <div class="card-header bg-dark text-white">

                            <i class="bi bi-cash-coin"></i>
                            Resumen

                        </div>


                        <div class="card-body">

                            <div class="d-flex justify-content-between mb-3">

                                <span>
                                    Total:
                                </span>

                                <strong class="fs-5" id="totalSalida">

                                    Bs. 0.00

                                </strong>

                            </div>


                            <div class="mb-3">

                                <label class="form-label">

                                    Monto pagado

                                </label>

                                <input type="number" name="monto_pagado" id="montoPagado" class="form-control" value="0"
                                    min="0" step="0.01" required>

                            </div>


                            <div class="d-flex justify-content-between">

                                <span>
                                    Saldo pendiente:
                                </span>

                                <strong id="saldoPendiente" class="text-danger">

                                    Bs. 0.00

                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ==========================================
            OBSERVACIÓN
            ========================================== --}}

            <div class="card shadow-sm mt-4 mb-4">

                <div class="card-header">

                    <i class="bi bi-chat-left-text"></i>
                    Observación

                </div>

                <div class="card-body">

                    <textarea name="observacion" class="form-control" rows="3"
                        placeholder="Observación opcional...">{{ old('observacion') }}</textarea>

                </div>

            </div>


            {{-- ==========================================
            BOTÓN REGISTRAR
            ========================================== --}}

            <div class="d-flex justify-content-end gap-2 mb-5">

                <a href="{{ route('insumos.salidas.index') }}" class="btn btn-secondary">

                    <i class="bi bi-x-circle"></i>
                    Cancelar

                </a>


                <button type="submit" class="btn btn-danger" id="btnRegistrar">

                    <i class="bi bi-check-circle"></i>

                    Registrar Salida

                </button>

            </div>


        </form>
        ```

    </div>

    {{-- ==========================================
    JAVASCRIPT
    ========================================== --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const selectInsumo = document.getElementById('selectInsumo');
            const stockDisponible = document.getElementById('stockDisponible');
            const cantidad = document.getElementById('cantidad');
            const precioVenta = document.getElementById('precioVenta');
            const btnAgregar = document.getElementById('btnAgregar');
            const tabla = document.getElementById('tablaInsumos');
            const filaVacia = document.getElementById('filaVacia');
            const totalSalida = document.getElementById('totalSalida');
            const montoPagado = document.getElementById('montoPagado');
            const saldoPendiente = document.getElementById('saldoPendiente');

            let productos = [];


            /*
            |--------------------------------------------------------------------------
            | SELECCIONAR INSUMO
            |--------------------------------------------------------------------------
            */

            selectInsumo.addEventListener('change', function () {

                const option = this.options[this.selectedIndex];

                if (!option.value) {

                    stockDisponible.value = 0;
                    precioVenta.value = '';

                    return;
                }

                stockDisponible.value = option.dataset.stock;

                precioVenta.value = parseFloat(
                    option.dataset.precio
                ).toFixed(2);

                cantidad.value = 1;

            });


            /*
            |--------------------------------------------------------------------------
            | AGREGAR INSUMO
            |--------------------------------------------------------------------------
            */

            btnAgregar.addEventListener('click', function () {

                const option = selectInsumo.options[
                    selectInsumo.selectedIndex
                ];

                if (!option.value) {

                    alert('Debe seleccionar un insumo.');

                    return;
                }


                const id = option.value;

                const nombre = option.dataset.nombre;

                const codigo = option.dataset.codigo;

                const stock = parseFloat(
                    option.dataset.stock
                );

                const precio = parseFloat(
                    option.dataset.precio
                );

                const cantidadIngresada = parseFloat(
                    cantidad.value
                );


                if (cantidadIngresada <= 0) {

                    alert('La cantidad debe ser mayor a cero.');

                    return;
                }


                if (cantidadIngresada > stock) {

                    alert(
                        'Stock insuficiente. Stock disponible: '
                        + stock
                    );

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | EVITAR DUPLICADOS
                |--------------------------------------------------------------------------
                */

                const existe = productos.find(
                    item => item.id == id
                );

                if (existe) {

                    alert(
                        'El insumo ya fue agregado a la salida.'
                    );

                    return;
                }


                productos.push({

                    id: id,

                    nombre: nombre,

                    codigo: codigo,

                    stock: stock,

                    cantidad: cantidadIngresada,

                    precio: precio

                });


                renderTabla();

                actualizarTotal();


                selectInsumo.value = '';

                stockDisponible.value = 0;

                precioVenta.value = '';

                cantidad.value = 1;

            });


            /*
            |--------------------------------------------------------------------------
            | MOSTRAR TABLA
            |--------------------------------------------------------------------------
            */

            function renderTabla() {

                filaVacia.style.display = 'none';

                tabla.querySelectorAll(
                    'tr[data-id]'
                ).forEach(row => row.remove());


                productos.forEach(function (producto, index) {

                    const subtotal =
                        producto.cantidad *
                        producto.precio;


                    const fila = document.createElement('tr');

                    fila.setAttribute(
                        'data-id',
                        producto.id
                    );


                    fila.innerHTML = `

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
                        ${producto.stock}
                    </td>

                    <td>

                        ${producto.cantidad}

                        <input
                            type="hidden"
                            name="insumos[${index}][id]"
                            value="${producto.id}"
                        >

                        <input
                            type="hidden"
                            name="insumos[${index}][cantidad]"
                            value="${producto.cantidad}"
                        >

                    </td>

                    <td>
                        Bs. ${producto.precio.toFixed(2)}
                    </td>

                    <td>
                        <strong>
                            Bs. ${subtotal.toFixed(2)}
                        </strong>
                    </td>

                    <td>

                        <button
                            type="button"
                            class="btn btn-danger btn-sm btnEliminar"
                            data-id="${producto.id}">

                            <i class="bi bi-trash"></i>

                        </button>

                    </td>

                `;


                    tabla.appendChild(fila);

                });


                if (productos.length === 0) {

                    filaVacia.style.display = '';

                }

            }


            /*
            |--------------------------------------------------------------------------
            | ELIMINAR INSUMO
            |--------------------------------------------------------------------------
            */

            tabla.addEventListener('click', function (e) {

                const boton = e.target.closest(
                    '.btnEliminar'
                );

                if (!boton) {
                    return;
                }

                const id = boton.dataset.id;

                productos = productos.filter(
                    item => item.id != id
                );

                renderTabla();

                actualizarTotal();

            });


            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR TOTAL
            |--------------------------------------------------------------------------
            */

            function actualizarTotal() {

                let total = 0;

                productos.forEach(function (producto) {

                    total +=
                        producto.cantidad *
                        producto.precio;

                });


                totalSalida.textContent =
                    'Bs. ' + total.toFixed(2);


                actualizarSaldo();

            }


            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR SALDO
            |--------------------------------------------------------------------------
            */

            montoPagado.addEventListener(
                'input',
                actualizarSaldo
            );


            function actualizarSaldo() {

                let total = 0;

                productos.forEach(function (producto) {

                    total +=
                        producto.cantidad *
                        producto.precio;

                });


                let pagado = parseFloat(
                    montoPagado.value
                ) || 0;


                let saldo = total - pagado;


                if (saldo < 0) {

                    saldo = 0;

                }


                saldoPendiente.textContent =
                    'Bs. ' + saldo.toFixed(2);

            }


            /*
            |--------------------------------------------------------------------------
            | VALIDAR FORMULARIO
            |--------------------------------------------------------------------------
            */

            document.getElementById('formSalida')
                .addEventListener('submit', function (e) {

                    if (productos.length === 0) {

                        e.preventDefault();

                        alert(
                            'Debe agregar al menos un insumo.'
                        );

                        return;
                    }


                    let total = 0;

                    productos.forEach(function (producto) {

                        total +=
                            producto.cantidad *
                            producto.precio;

                    });


                    let pagado = parseFloat(
                        montoPagado.value
                    ) || 0;


                    if (pagado > total) {

                        e.preventDefault();

                        alert(
                            'El monto pagado no puede ser mayor al total.'
                        );

                        return;
                    }

                });

        });

    </script>

@endsection