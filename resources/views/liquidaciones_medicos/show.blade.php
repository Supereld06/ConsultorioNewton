@extends('layouts.app')

@section('content')

    <div class="container-fluid">

        {{-- ENCABEZADO --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="mb-1">
                    📋 Liquidación {{ $liquidacion->numero }}
                </h2>

                <p class="text-muted mb-0">
                    Detalle de liquidación médica
                </p>

            </div>

            <a href="{{ route('liquidaciones_medicos.index') }}" class="btn btn-secondary">
                ← Volver
            </a>

        </div>


        {{-- MENSAJES --}}

        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger">
                {{ session('error') }}
            </div>

        @endif


        {{-- INFORMACIÓN GENERAL --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">
                    Información de la liquidación
                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <strong>Número:</strong>

                        <br>

                        {{ $liquidacion->numero }}

                    </div>

                    <div class="col-md-4 mb-3">

                        <strong>Médico:</strong>

                        <br>

                        {{ $liquidacion->doctor->apellidos }}

                        {{ $liquidacion->doctor->nombres }}

                    </div>

                    <div class="col-md-4 mb-3">

                        <strong>Fecha:</strong>

                        <br>

                        {{ $liquidacion->fecha?->format('d/m/Y') }}

                    </div>

                    <div class="col-md-4 mb-3">

                        <strong>Estado:</strong>

                        <br>

                        @if($liquidacion->estado === 'pendiente')

                            <span class="badge bg-warning text-dark">
                                PENDIENTE
                            </span>

                        @elseif($liquidacion->estado === 'pagada')

                            <span class="badge bg-success">
                                PAGADA
                            </span>

                        @else

                            <span class="badge bg-danger">
                                ANULADA
                            </span>

                        @endif

                    </div>

                    <div class="col-md-8 mb-3">

                        <strong>Observación:</strong>

                        <br>

                        {{ $liquidacion->observacion ?: 'Sin observaciones' }}

                    </div>

                </div>

            </div>

        </div>


        {{-- DETALLES --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-dark text-white">

                <h5 class="mb-0">
                    📑 Detalle de ingresos generados
                </h5>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>#</th>

                                <th>Fecha</th>

                                <th>Origen</th>

                                <th>Concepto</th>

                                <th class="text-end">
                                    Monto
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($liquidacion->detalles as $detalle)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $detalle->fecha?->format('d/m/Y') }}
                                    </td>

                                    <td>

                                        @if($detalle->tipo_origen === 'pago_medico')

                                            <span class="badge bg-primary">
                                                Atención médica
                                            </span>

                                        @elseif($detalle->tipo_origen === 'curacion')

                                            <span class="badge bg-warning text-dark">
                                                Curación
                                            </span>

                                        @endif

                                    </td>

                                    <td>
                                        {{ $detalle->concepto }}
                                    </td>

                                    <td class="text-end">

                                        <strong>
                                            Bs.
                                            {{ number_format($detalle->monto, 2) }}
                                        </strong>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="text-center text-muted p-4">
                                        No existen detalles.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                        <tfoot>

                            <tr class="table-primary">

                                <th colspan="4" class="text-end">
                                    TOTAL GENERADO:
                                </th>

                                <th class="text-end">

                                    Bs.
                                    {{ number_format($liquidacion->total_generado, 2) }}

                                </th>

                            </tr>

                            <tr>

                                <th colspan="4" class="text-end">
                                    TOTAL PAGADO:
                                </th>

                                <th class="text-end">

                                    Bs.
                                    {{ number_format($liquidacion->total_pagado, 2) }}

                                </th>

                            </tr>

                            <tr class="table-warning">

                                <th colspan="4" class="text-end">
                                    SALDO PENDIENTE:
                                </th>

                                <th class="text-end">

                                    Bs.
                                    {{ number_format($liquidacion->saldo, 2) }}

                                </th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>


        {{-- ACCIONES --}}

        <div class="card shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-end gap-2">

                    @if($liquidacion->estado === 'pendiente')

                        <form action="{{ route('liquidaciones_medicos.pagar', $liquidacion->id) }}" method="POST"
                            class="d-inline" onsubmit="return confirmarPago();">

                            @csrf

                            <button type="submit" class="btn btn-success">
                                💵 PAGAR LIQUIDACIÓN
                            </button>

                        </form>

                    @endif

                    <button type="button" class="btn btn-danger"
                        onclick="alert('El PDF se implementará en el siguiente paso.');">
                        📄 PDF
                    </button>

                </div>

            </div>

        </div>

    </div>

    <script>

        function confirmarPago() {
            return confirm(
                '¿Está seguro de realizar el pago completo de esta liquidación?\n\n' +
                'Monto a pagar: Bs. {{ number_format($liquidacion->saldo, 2) }}\n\n' +
                'El monto será descontado de la Caja Doctores.'
            );
        }

    </script>

@endsection