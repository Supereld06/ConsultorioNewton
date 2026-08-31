@extends('layouts.app')

@section('content')

    <div class="container">

        {{-- ENCABEZADO --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="mb-1">
                    <i class="bi bi-box-arrow-in-down text-primary"></i>
                    Ingresos de Insumos
                </h3>

                <small class="text-muted">
                    Historial de ingresos de medicamentos e insumos médicos
                </small>
            </div>

            <a href="{{ route('insumos.ingresos.create') }}" class="btn btn-success">

                <i class="bi bi-plus-circle"></i>
                Nuevo Ingreso

            </a>

        </div>


        {{-- MENSAJE DE ÉXITO --}}
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show" role="alert">

                <i class="bi bi-check-circle-fill"></i>

                <strong>Correcto:</strong>
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- MENSAJE DE ERROR --}}
        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show" role="alert">

                <i class="bi bi-exclamation-triangle-fill"></i>

                <strong>Error:</strong>
                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- BUSCADOR --}}
        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <form method="GET" action="{{ route('insumos.ingresos.index') }}">

                    <div class="row g-3 align-items-end">

                        <div class="col-md-10">

                            <label class="form-label">
                                Buscar ingreso
                            </label>

                            <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control"
                                placeholder="Buscar por código o proveedor">

                        </div>

                        <div class="col-md-2">

                            <button type="submit" class="btn btn-primary w-100">

                                <i class="bi bi-search"></i>
                                Buscar

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- TABLA --}}
        <div class="card shadow">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <strong>
                        <i class="bi bi-list-ul"></i>
                        Ingresos registrados
                    </strong>

                    <span class="badge bg-primary">
                        {{ $ingresos->total() }}
                        registros
                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>

                                <th>Código</th>

                                <th>Fecha</th>

                                <th>Proveedor</th>

                                <th>Productos</th>

                                <th>Total</th>

                                <th>Pagado</th>

                                <th>Pendiente</th>

                                <th>Usuario</th>

                                <th>Acciones</th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($ingresos as $ingreso)

                                                    <tr>

                                                        {{-- ID --}}
                                                        <td>
                                                            {{ $ingreso->id }}
                                                        </td>


                                                        {{-- CÓDIGO --}}
                                                        <td>

                                                            <span class="badge bg-secondary">

                                                                {{ $ingreso->codigo }}

                                                            </span>

                                                        </td>


                                                        {{-- FECHA --}}
                                                        <td>

                                                            {{ $ingreso->fecha
                                    ? $ingreso->fecha->format('d/m/Y')
                                    : '-' }}

                                                        </td>


                                                        {{-- PROVEEDOR --}}
                                                        <td>

                                                            @if($ingreso->proveedor)

                                                                {{ $ingreso->proveedor }}

                                                            @else

                                                                <span class="text-muted">
                                                                    Sin proveedor
                                                                </span>

                                                            @endif

                                                        </td>


                                                        {{-- CANTIDAD DE PRODUCTOS --}}
                                                        <td>

                                                            <span class="badge bg-info">

                                                                {{ $ingreso->detalles->count() }}

                                                                producto(s)

                                                            </span>

                                                        </td>


                                                        {{-- TOTAL --}}
                                                        <td>

                                                            <strong>
                                                                Bs.
                                                                {{ number_format(
                                    $ingreso->total,
                                    2
                                ) }}
                                                            </strong>

                                                        </td>


                                                        {{-- PAGADO --}}
                                                        <td>

                                                            <span class="text-success">

                                                                Bs.
                                                                {{ number_format(
                                    $ingreso->monto_pagado,
                                    2
                                ) }}

                                                            </span>

                                                        </td>


                                                        {{-- PENDIENTE --}}
                                                        <td>

                                                            @if($ingreso->saldo_pendiente > 0)

                                                                                        <span class="badge bg-warning text-dark">

                                                                                            Bs.
                                                                                            {{ number_format(
                                                                    $ingreso->saldo_pendiente,
                                                                    2
                                                                ) }}

                                                                                        </span>

                                                            @else

                                                                <span class="badge bg-success">

                                                                    PAGADO

                                                                </span>

                                                            @endif

                                                        </td>


                                                        {{-- USUARIO --}}
                                                        <td>

                                                            {{ $ingreso->usuario->name ?? 'N/A' }}

                                                        </td>


                                                        {{-- ACCIONES --}}
                                                        <td>

                                                            <a href="#" class="btn btn-info btn-sm" title="Ver detalle">

                                                                <i class="bi bi-eye"></i>

                                                            </a>

                                                        </td>

                                                    </tr>

                            @empty

                                <tr>

                                    <td colspan="10" class="text-center py-5">

                                        <i class="bi bi-inbox fs-1 text-muted"></i>

                                        <h5 class="mt-3 text-muted">

                                            No hay ingresos registrados

                                        </h5>

                                        <p class="text-muted">

                                            Comienza registrando tu primer ingreso.

                                        </p>

                                        <a href="{{ route('insumos.ingresos.create') }}" class="btn btn-success">

                                            <i class="bi bi-plus-circle"></i>

                                            Registrar primer ingreso

                                        </a>

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

            {{ $ingresos->links() }}

        </div>

    </div>

@endsection