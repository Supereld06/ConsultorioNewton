@extends('layouts.app')

@section('content')

    <div class="container-fluid">

        {{-- ENCABEZADO --}}
        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h2 class="mb-0">
                    📋 Movimientos - {{ $caja->nombre }}
                </h2>

                <small class="text-muted">
                    Historial de movimientos de la caja
                </small>

            </div>

            <div>

                <a href="{{ route('cajas.index') }}" class="btn btn-secondary btn-sm">

                    ← Volver a cajas

                </a>

            </div>

        </div>


        {{-- MENSAJE --}}
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show py-2">

                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- SALDO ACTUAL --}}
        <div class="card shadow-sm mb-3">

            <div class="card-body py-3">

                <div class="row align-items-center">

                    <div class="col-md-6">

                        <small class="text-muted">
                            Caja
                        </small>

                        <h5 class="mb-0">
                            {{ $caja->nombre }}
                        </h5>

                    </div>

                    <div class="col-md-6 text-md-end">

                        <small class="text-muted">
                            Saldo actual
                        </small>

                        <div class="fs-4 fw-bold">
                            Bs. {{ number_format($caja->saldo, 2) }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- BOTONES --}}
        <div class="mb-3">

            <a href="{{ route('cajas.ingreso', $caja->id) }}" class="btn btn-success btn-sm">

                ➕ Ingreso

            </a>

            <a href="{{ route('cajas.egreso', $caja->id) }}" class="btn btn-danger btn-sm">

                ➖ Egreso

            </a>

            <a href="{{ route('cajas.transferencia', $caja->id) }}" class="btn btn-warning btn-sm">

                🔄 Traspasar

            </a>

        </div>


        {{-- TABLA --}}
        <div class="card shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-sm table-hover mb-0 align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Concepto</th>
                                <th>Usuario</th>
                                <th class="text-end">Monto</th>
                                <th class="text-end">Saldo anterior</th>
                                <th class="text-end">Saldo nuevo</th>
                                <th class="text-center">Recibo</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($movimientos as $movimiento)

                                                <tr>

                                                    {{-- FECHA --}}
                                                    <td class="text-nowrap">

                                                        {{ $movimiento->fecha
                                ? $movimiento->fecha->format('d/m/Y H:i')
                                : '-' }}

                                                    </td>


                                                    {{-- TIPO --}}
                                                    <td>

                                                        @if($movimiento->tipo === 'ingreso')

                                                            <span class="badge bg-success">
                                                                INGRESO
                                                            </span>

                                                        @else

                                                            <span class="badge bg-danger">
                                                                EGRESO
                                                            </span>

                                                        @endif

                                                    </td>


                                                    {{-- CONCEPTO --}}
                                                    <td>

                                                        <div>
                                                            {{ $movimiento->concepto }}
                                                        </div>

                                                        @if($movimiento->observacion)

                                                            <small class="text-muted">
                                                                {{ $movimiento->observacion }}
                                                            </small>

                                                        @endif

                                                    </td>


                                                    {{-- USUARIO --}}
                                                    <td>

                                                        {{ $movimiento->usuario->name ?? '-' }}

                                                    </td>


                                                    {{-- MONTO --}}
                                                    <td class="text-end fw-bold">

                                                        @if($movimiento->tipo === 'ingreso')

                                                            <span class="text-success">
                                                                + Bs. {{ number_format($movimiento->monto, 2) }}
                                                            </span>

                                                        @else

                                                            <span class="text-danger">
                                                                - Bs. {{ number_format($movimiento->monto, 2) }}
                                                            </span>

                                                        @endif

                                                    </td>


                                                    {{-- SALDO ANTERIOR --}}
                                                    <td class="text-end">

                                                        Bs.
                                                        {{ number_format($movimiento->saldo_anterior, 2) }}

                                                    </td>


                                                    {{-- SALDO NUEVO --}}
                                                    <td class="text-end fw-semibold">

                                                        Bs.
                                                        {{ number_format($movimiento->saldo_nuevo, 2) }}

                                                    </td>
                                                    <td class="text-center">

                                                        <a href="{{ route('cajas.movimientos.recibo', $movimiento->id) }}" target="_blank"
                                                            class="btn btn-outline-dark btn-sm" title="Imprimir recibo">

                                                            🖨️

                                                        </a>

                                                    </td>

                                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8" class="text-center text-muted py-4">

                                        No existen movimientos registrados
                                        para esta caja.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

@endsection