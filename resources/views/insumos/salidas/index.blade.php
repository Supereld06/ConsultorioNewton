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

                    Salidas de Insumos

                </h3>

                <small class="text-muted">

                    Historial de salidas registradas

                </small>

            </div>


            <a href="{{ route('insumos.salidas.create') }}" class="btn btn-danger">

                <i class="bi bi-plus-circle"></i>

                Nueva Salida

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


        {{-- ==========================================
        TABLA
        ========================================== --}}

        <div class="card shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-dark">

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Código
                                </th>

                                <th>
                                    Fecha
                                </th>

                                <th>
                                    Motivo
                                </th>

                                <th>
                                    Total
                                </th>

                                <th>
                                    Pagado
                                </th>

                                <th>
                                    Saldo
                                </th>

                                <th>
                                    Usuario
                                </th>

                                <th>
                                    Acciones
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($salidas as $salida)

                                <tr>

                                    <td>

                                        {{ $salida->id }}

                                    </td>


                                    <td>

                                        <span class="badge bg-danger">

                                            {{ $salida->codigo }}

                                        </span>

                                    </td>


                                    <td>

                                        {{ $salida->fecha->format('d/m/Y') }}

                                    </td>


                                    <td>

                                        {{ $salida->motivo ?: 'Sin motivo' }}

                                    </td>


                                    <td>

                                        <strong>

                                            Bs.
                                            {{ number_format($salida->total, 2) }}

                                        </strong>

                                    </td>


                                    <td>

                                        <span class="text-success">

                                            Bs.
                                            {{ number_format($salida->monto_pagado, 2) }}

                                        </span>

                                    </td>


                                    <td>

                                        @if($salida->saldo_pendiente > 0)

                                            <span class="text-danger fw-bold">

                                                Bs.
                                                {{ number_format($salida->saldo_pendiente, 2) }}

                                            </span>

                                        @else

                                            <span class="text-success">

                                                Bs. 0.00

                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        {{ $salida->usuario->name ?? 'N/A' }}

                                    </td>


                                    <td>

                                        {{-- PDF --}}

                                        <a href="{{ route('insumos.salidas.pdf', $salida->id) }}" target="_blank"
                                            class="btn btn-danger btn-sm" title="Ver PDF">

                                            <i class="bi bi-file-earmark-pdf-fill"></i>

                                            PDF

                                        </a>


                                        {{-- RECIBO --}}

                                        <a href="{{ route('insumos.salidas.recibo', $salida->id) }}" target="_blank"
                                            class="btn btn-success btn-sm" title="Ver recibo">

                                            <i class="bi bi-receipt"></i>

                                            RECIBO

                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="9" class="text-center py-5">

                                        <i class="bi bi-inbox fs-1 text-muted"></i>

                                        <p class="text-muted mb-0">

                                            No hay salidas registradas.

                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- ==========================================
        PAGINACIÓN
        ========================================== --}}

        <div class="mt-3">

            {{ $salidas->links() }}

        </div>
        

    </div>

@endsection