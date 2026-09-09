@extends('layouts.app')

@section('content')

    <div class="container py-4">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif


        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2>
                    💰 Pago Médico Registrado
                </h2>

                <p class="text-muted mb-0">
                    Detalle de la distribución del ingreso.
                </p>

            </div>

            <a href="{{ route('consultations.index') }}" class="btn btn-secondary">

                ← Volver a consultas

            </a>

        </div>


        {{-- INFORMACIÓN GENERAL --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-dark text-white">

                <strong>
                    Información del pago
                </strong>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <strong>Número de pago</strong>

                        <div>
                            #{{ str_pad($pagoMedico->id, 6, '0', STR_PAD_LEFT) }}
                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <strong>Consulta</strong>

                        <div>
                            #{{ str_pad(
        $pagoMedico->consultation_id,
        6,
        '0',
        STR_PAD_LEFT
    ) }}
                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <strong>Estado</strong>

                        <div>

                            @if($pagoMedico->estado === 'registrado')

                                <span class="badge bg-success">
                                    Registrado
                                </span>

                            @elseif($pagoMedico->estado === 'liquidado')

                                <span class="badge bg-primary">
                                    Liquidado
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Anulado
                                </span>

                            @endif

                        </div>

                    </div>


                    <div class="col-md-6 mb-3">

                        <strong>Paciente</strong>

                        <div>

                            @if($pagoMedico->consultation?->appointment?->patient)

                                {{ $pagoMedico->consultation->appointment->patient->nombres }}
                                {{ $pagoMedico->consultation->appointment->patient->apellidos }}

                            @else

                                Sin paciente

                            @endif

                        </div>

                    </div>


                    <div class="col-md-6 mb-3">

                        <strong>Médico</strong>

                        <div>

                            {{ $pagoMedico->doctor->nombres }}
                            {{ $pagoMedico->doctor->apellidos }}

                        </div>

                    </div>


                    <div class="col-md-6">

                        <strong>Fecha de atención</strong>

                        <div>
                            {{ $pagoMedico->fecha_atencion->format('d/m/Y') }}
                        </div>

                    </div>


                    <div class="col-md-6">

                        <strong>Costo total</strong>

                        <div class="fs-4 fw-bold">

                            Bs.
                            {{ number_format(
        $pagoMedico->costo_atencion,
        2
    ) }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- DISTRIBUCIÓN --}}

        <div class="card shadow-sm">

            <div class="card-header bg-primary text-white">

                <strong>
                    Distribución registrada
                </strong>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>Concepto</th>

                                <th>Caja</th>

                                <th>Porcentaje</th>

                                <th class="text-end">
                                    Monto
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($pagoMedico->distribuciones as $distribucion)

                                                    <tr>

                                                        <td>

                                                            @if($distribucion->concepto === 'medico')

                                                                👨‍⚕️ Médico

                                                            @elseif($distribucion->concepto === 'institucion')

                                                                🏢 Empresa

                                                            @else

                                                                📦 Otros

                                                            @endif

                                                        </td>


                                                        <td>
                                                            {{ $distribucion->caja->nombre }}
                                                        </td>


                                                        <td>
                                                            {{ number_format(
                                    $distribucion->porcentaje,
                                    2
                                ) }}%
                                                        </td>


                                                        <td class="text-end fw-bold">

                                                            Bs.
                                                            {{ number_format(
                                    $distribucion->monto,
                                    2
                                ) }}

                                                        </td>

                                                    </tr>

                            @endforeach

                        </tbody>

                        <tfoot class="table-dark">

                            <tr>

                                <th colspan="2">
                                    TOTAL
                                </th>

                                <th>

                                    {{
        number_format(
            $pagoMedico->distribuciones->sum('porcentaje'),
            2
        )
                                    }}%

                                </th>

                                <th class="text-end">

                                    Bs.
                                    {{
        number_format(
            $pagoMedico->distribuciones->sum('monto'),
            2
        )
                                    }}

                                </th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

    </div>

@endsection