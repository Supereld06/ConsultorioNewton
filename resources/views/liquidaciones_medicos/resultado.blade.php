@extends('layouts.app')

@section('content')

    <div class="container-fluid">

        {{-- ENCABEZADO --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="mb-1">
                    👨‍⚕️ Liquidación médica
                </h2>

                <p class="text-muted mb-0">
                    Detalle de importes pendientes de liquidar.
                </p>
            </div>

            <a href="{{ route('liquidaciones_medicos.index') }}" class="btn btn-secondary">
                ← Nueva consulta
            </a>

        </div>


        {{-- INFORMACIÓN DEL MÉDICO --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    👨‍⚕️ Información del médico
                </h5>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">

                        <strong>Médico:</strong><br>

                        {{ $doctor->apellidos }}
                        {{ $doctor->nombres }}

                    </div>

                    <div class="col-md-4">

                        <strong>Especialidad:</strong><br>

                        {{ $doctor->especialidad }}

                    </div>

                    <div class="col-md-4">

                        <strong>Periodo:</strong><br>

                        {{ \Carbon\Carbon::parse($fechaDesde)->format('d/m/Y') }}

                        al

                        {{ \Carbon\Carbon::parse($fechaHasta)->format('d/m/Y') }}

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================= --}}
        {{-- ATENCIONES MÉDICAS --}}
        {{-- ============================= --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-success text-white">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        🩺 ATENCIONES MÉDICAS
                    </h5>

                    <span class="badge bg-light text-dark">
                        {{ $atenciones->count() }} registros
                    </span>

                </div>

            </div>

            <div class="card-body p-0">

                @if($atenciones->count() > 0)

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>#</th>

                                    <th>Fecha</th>

                                    <th>Paciente</th>

                                    <th>Concepto</th>

                                    <th class="text-end">
                                        Monto médico
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($atenciones as $atencion)

                                    <tr>

                                        <td>
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            {{ $atencion->pagoMedico->fecha_atencion?->format('d/m/Y') }}
                                        </td>

                                        <td>

                                            @if($atencion->pagoMedico->consultation?->appointment?->patient)

                                                {{ $atencion->pagoMedico->consultation->appointment->patient->apellidos }}

                                                {{ $atencion->pagoMedico->consultation->appointment->patient->nombres }}

                                            @else

                                                <span class="text-muted">
                                                    Paciente no disponible
                                                </span>

                                            @endif

                                        </td>

                                        <td>
                                            Atención médica
                                        </td>

                                        <td class="text-end">

                                            <strong>
                                                Bs.
                                                {{ number_format($atencion->monto, 2) }}
                                            </strong>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                            <tfoot>

                                <tr class="table-success">

                                    <th colspan="4" class="text-end">
                                        SUBTOTAL ATENCIONES:
                                    </th>

                                    <th class="text-end">
                                        Bs.
                                        {{ number_format($totalAtenciones, 2) }}
                                    </th>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                @else

                    <div class="p-4 text-center text-muted">

                        No existen atenciones médicas pendientes
                        de liquidar en este periodo.

                    </div>

                @endif

            </div>

        </div>


        {{-- ============================= --}}
        {{-- CURACIONES --}}
        {{-- ============================= --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-warning">

                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">
                        🩹 CURACIONES
                    </h5>

                    <span class="badge bg-dark">
                        {{ $curaciones->count() }} registros
                    </span>

                </div>

            </div>

            <div class="card-body p-0">

                @if($curaciones->count() > 0)

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>#</th>

                                    <th>Fecha</th>

                                    <th>Código</th>

                                    <th>Concepto</th>

                                    <th class="text-end">
                                        Monto médico
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach($curaciones as $curacion)

                                    <tr>

                                        <td>
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            {{ $curacion->curacion->fecha?->format('d/m/Y') }}
                                        </td>

                                        <td>
                                            {{ $curacion->curacion->codigo }}
                                        </td>

                                        <td>
                                            {{ $curacion->concepto }}
                                        </td>

                                        <td class="text-end">

                                            <strong>
                                                Bs.
                                                {{ number_format($curacion->monto, 2) }}
                                            </strong>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                            <tfoot>

                                <tr class="table-warning">

                                    <th colspan="4" class="text-end">
                                        SUBTOTAL CURACIONES:
                                    </th>

                                    <th class="text-end">
                                        Bs.
                                        {{ number_format($totalCuraciones, 2) }}
                                    </th>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                @else

                    <div class="p-4 text-center text-muted">

                        No existen curaciones pendientes
                        de liquidar en este periodo.

                    </div>

                @endif

            </div>

        </div>


        {{-- ============================= --}}
        {{-- TOTAL --}}
        {{-- ============================= --}}

        <div class="card shadow-lg border-primary mb-4">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-md-8">

                        <h4 class="mb-1">
                            💰 TOTAL A LIQUIDAR
                        </h4>

                        <p class="text-muted mb-0">
                            Atención médica + curaciones
                        </p>

                    </div>

                    <div class="col-md-4 text-end">

                        <h2 class="text-primary mb-0">

                            Bs.
                            {{ number_format($totalGeneral, 2) }}

                        </h2>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================= --}}
        {{-- GENERAR LIQUIDACIÓN --}}
        {{-- ============================= --}}

        @if($totalGeneral > 0)

            <div class="card shadow-sm mb-4">

                <div class="card-body">

                    <form action="{{ route('liquidaciones_medicos.store') }}" method="POST"
                        onsubmit="return confirmarLiquidacion();">

                        @csrf

                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">

                        <input type="hidden" name="fecha_desde" value="{{ $fechaDesde }}">

                        <input type="hidden" name="fecha_hasta" value="{{ $fechaHasta }}">

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('liquidaciones_medicos.index') }}" class="btn btn-secondary">
                                Cancelar
                            </a>

                            <button type="submit" class="btn btn-primary">
                                📋 GENERAR LIQUIDACIÓN
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        @endif

    </div>


    <script>

        function confirmarLiquidacion() {
            const total = @json(number_format($totalGeneral, 2));

            return confirm(
                '¿Desea generar la liquidación médica por Bs. ' +
                total +
                '?'
            );
        }

    </script>

@endsection