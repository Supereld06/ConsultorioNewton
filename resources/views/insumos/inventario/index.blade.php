@extends('layouts.app')

@section('content')

    <div class="container">

        {{-- ENCABEZADO --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="mb-1">
                    📦 Inventario de Insumos
                </h3>

                <small class="text-muted">
                    Control actual de existencias
                </small>
            </div>

            <div>

                {{-- PDF --}}
                <a href="{{ route('insumos.inventario.pdf', ['search' => $search]) }}" target="_blank"
                    class="btn btn-danger">

                    <i class="bi bi-file-earmark-pdf-fill"></i>
                    PDF

                </a>

            </div>

        </div>


        {{-- MENSAJES --}}
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


        {{-- ESTADÍSTICAS --}}
        <div class="row mb-4">

            {{-- TOTAL PRODUCTOS --}}
            <div class="col-md-3 mb-3">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <h6 class="text-muted">
                                    Total de insumos
                                </h6>

                                <h3 class="mb-0">
                                    {{ $totalProductos }}
                                </h3>

                            </div>

                            <div class="fs-1">
                                📦
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- STOCK BAJO --}}
            <div class="col-md-3 mb-3">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <h6 class="text-muted">
                                    Stock bajo
                                </h6>

                                <h3 class="mb-0 text-warning">
                                    {{ $productosStockBajo }}
                                </h3>

                            </div>

                            <div class="fs-1">
                                ⚠️
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- AGOTADOS --}}
            <div class="col-md-3 mb-3">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <h6 class="text-muted">
                                    Agotados
                                </h6>

                                <h3 class="mb-0 text-danger">
                                    {{ $productosAgotados }}
                                </h3>

                            </div>

                            <div class="fs-1">
                                🚨
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- VALOR INVENTARIO --}}
            <div class="col-md-3 mb-3">

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <h6 class="text-muted">
                                    Valor inventario
                                </h6>

                                <h4 class="mb-0 text-success">

                                    Bs.
                                    {{ number_format($valorInventario ?? 0, 2) }}

                                </h4>

                            </div>

                            <div class="fs-1">
                                💰
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- BUSCADOR --}}
        <form method="GET" action="{{ route('insumos.inventario') }}" class="mb-3">

            <div class="input-group">

                <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control"
                    placeholder="Buscar por código, nombre o tipo...">

                <button class="btn btn-primary">

                    <i class="bi bi-search"></i>
                    Buscar

                </button>

                @if($search)

                    <a href="{{ route('insumos.inventario') }}" class="btn btn-secondary">

                        <i class="bi bi-x-circle"></i>
                        Limpiar

                    </a>

                @endif

            </div>

        </form>


        {{-- TABLA --}}
        <div class="card shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>

                                <th>Código</th>

                                <th>Insumo</th>

                                <th>Tipo</th>

                                <th>Unidad</th>

                                <th class="text-center">
                                    Stock
                                </th>

                                <th class="text-end">
                                    Precio compra
                                </th>

                                <th class="text-end">
                                    Precio venta
                                </th>

                                <th class="text-end">
                                    Valor
                                </th>

                                <th class="text-center">
                                    Estado
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($insumos as $insumo)

                                @php

                                    if ($insumo->stock <= 0) {

                                        $estadoStock = 'Agotado';

                                        $badgeStock = 'danger';

                                    } elseif ($insumo->stock <= $insumo->stock_minimo) {

                                        $estadoStock = 'Stock bajo';

                                        $badgeStock = 'warning';

                                    } else {

                                        $estadoStock = 'Disponible';

                                        $badgeStock = 'success';

                                    }

                                    $valor = $insumo->stock * $insumo->precio_compra;

                                @endphp


                                <tr>

                                    {{-- ID --}}
                                    <td>
                                        {{ $insumo->id }}
                                    </td>


                                    {{-- CÓDIGO --}}
                                    <td>

                                        <strong>
                                            {{ $insumo->codigo }}
                                        </strong>

                                    </td>


                                    {{-- NOMBRE --}}
                                    <td>

                                        <strong>
                                            {{ $insumo->nombre }}
                                        </strong>

                                        @if($insumo->descripcion)

                                            <br>

                                            <small class="text-muted">

                                                {{ Str::limit($insumo->descripcion, 50) }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- TIPO --}}
                                    <td>

                                        <span class="badge bg-info">

                                            {{ $insumo->tipo }}

                                        </span>

                                    </td>


                                    {{-- UNIDAD --}}
                                    <td>
                                        {{ $insumo->unidad_medida }}
                                    </td>


                                    {{-- STOCK --}}
                                    <td class="text-center">

                                        <strong class="fs-6">

                                            {{ number_format($insumo->stock, 2) }}

                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            Mín:
                                            {{ number_format($insumo->stock_minimo, 2) }}

                                        </small>

                                    </td>


                                    {{-- PRECIO COMPRA --}}
                                    <td class="text-end">

                                        Bs.
                                        {{ number_format($insumo->precio_compra, 2) }}

                                    </td>


                                    {{-- PRECIO VENTA --}}
                                    <td class="text-end">

                                        Bs.
                                        {{ number_format($insumo->precio_venta, 2) }}

                                    </td>


                                    {{-- VALOR INVENTARIO --}}
                                    <td class="text-end">

                                        <strong>

                                            Bs.
                                            {{ number_format($valor, 2) }}

                                        </strong>

                                    </td>


                                    {{-- ESTADO --}}
                                    <td class="text-center">

                                        <span class="badge bg-{{ $badgeStock }}">

                                            {{ $estadoStock }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="10" class="text-center py-4">

                                        <i class="bi bi-box-seam fs-1 text-muted"></i>

                                        <p class="mt-2 mb-0">

                                            No hay insumos registrados.

                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- PAGINACIÓN --}}
        <div class="mt-3">

            {{ $insumos->links() }}

        </div>


    </div>

@endsection