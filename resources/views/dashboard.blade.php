@extends('layouts.app')

@section('content')

    <div class="container-fluid mt-4">

        {{-- =====================================================
        ENCABEZADO
        ====================================================== --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="mb-1">🏥 Panel de Control</h2>

                <small class="text-muted">
                    Resumen general del consultorio
                </small>
            </div>

        </div>


        {{-- =====================================================
        TARJETAS
        ====================================================== --}}

        <div class="row g-3">

            {{-- PACIENTES --}}
            <div class="col-md-3">

                <div class="card shadow-sm text-center h-100">

                    <div class="card-body py-3">

                        <h6 class="mb-1">
                            👨 Pacientes
                        </h6>

                        <h2 class="fw-bold text-primary mb-1">
                            {{ $patients }}
                        </h2>

                        <small class="text-muted">
                            Registrados
                        </small>

                        <div class="mt-2">

                            <a href="{{ route('patients.index') }}" class="btn btn-primary btn-sm">

                                Ver pacientes

                            </a>

                        </div>

                    </div>

                </div>

            </div>


            {{-- CITAS HOY --}}
            <div class="col-md-3">

                <div class="card shadow-sm text-center h-100">

                    <div class="card-body py-3">

                        <h6 class="mb-1">
                            📅 Citas hoy
                        </h6>

                        <h2 class="fw-bold text-success mb-1">
                            {{ $appointmentsToday }}
                        </h2>

                        <small class="text-muted">
                            Programadas hoy
                        </small>

                        <div class="mt-2">

                            <a href="{{ route('appointments.index') }}" class="btn btn-success btn-sm">

                                Ver citas

                            </a>

                        </div>

                    </div>

                </div>

            </div>


            {{-- CONSULTAS HOY --}}
            <div class="col-md-3">

                <div class="card shadow-sm text-center h-100">

                    <div class="card-body py-3">

                        <h6 class="mb-1">
                            🩺 Consultas hoy
                        </h6>

                        <h2 class="fw-bold text-warning mb-1">
                            {{ $consultationsToday }}
                        </h2>

                        <small class="text-muted">
                            Atendidas
                        </small>

                        <div class="mt-2">

                            <a href="{{ route('consultations.index') }}" class="btn btn-warning btn-sm">

                                Ver consultas

                            </a>

                        </div>

                    </div>

                </div>

            </div>


            {{-- DOCTORES --}}
            <div class="col-md-3">

                <div class="card shadow-sm text-center h-100">

                    <div class="card-body py-3">

                        <h6 class="mb-1">
                            👨‍⚕️ Doctores
                        </h6>

                        <h2 class="fw-bold text-info mb-1">
                            {{ $doctors }}
                        </h2>

                        <small class="text-muted">
                            Activos
                        </small>

                        <div class="mt-2">

                            <a href="{{ route('doctors.index') }}" class="btn btn-info btn-sm">

                                Ver doctores

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
        TODAS LAS CITAS MÉDICAS
        ====================================================== --}}

        <div class="card mt-4 shadow-sm">

            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

                <div>
                    📅 <strong>Todas las Citas Médicas</strong>
                </div>

                <span class="badge bg-light text-dark">
                    {{ $appointmentsList->total() }} registros
                </span>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover table-bordered mb-0 align-middle">

                        <thead class="table-light">

                            <tr>

                                <th class="text-center">
                                    Fecha
                                </th>

                                <th class="text-center">
                                    Hora
                                </th>

                                <th>
                                    Paciente
                                </th>

                                <th>
                                    Doctor
                                </th>

                                <th class="text-center">
                                    Estado
                                </th>

                                <th class="text-center">
                                    Documentos
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($appointmentsList as $a)

                                @php

                                    $consulta = $a->consultation;

                                    $curacion = $consulta?->curacion;

                                    $estudios = $consulta?->estudiosComplementarios;

                                @endphp


                                <tr>

                                    {{-- =================================================
                                    FECHA
                                    ================================================== --}}

                                    <td class="text-center">

                                        <strong>
                                            {{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}
                                        </strong>

                                    </td>


                                    {{-- =================================================
                                    HORA
                                    ================================================== --}}

                                    <td class="text-center">

                                        <span class="badge bg-secondary">

                                            {{ \Carbon\Carbon::parse($a->hora)->format('H:i') }}

                                        </span>

                                    </td>


                                    {{-- =================================================
                                    PACIENTE
                                    ================================================== --}}

                                    <td>

                                        @if($a->patient)

                                            <strong>
                                                {{ $a->patient->nombres }}
                                                {{ $a->patient->apellidos }}
                                            </strong>

                                        @else

                                            <span class="text-muted">
                                                Sin paciente
                                            </span>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                    DOCTOR
                                    ================================================== --}}

                                    <td>

                                        @if($a->doctor)

                                            Dr./Dra.
                                            {{ $a->doctor->nombres }}
                                            {{ $a->doctor->apellidos }}

                                        @else

                                            <span class="text-muted">
                                                Sin doctor
                                            </span>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                    ESTADO
                                    ================================================== --}}

                                    <td class="text-center">

                                        @if(!$consulta)

                                            <span class="badge bg-secondary">
                                                Sin atención
                                            </span>

                                        @elseif(!$consulta->atendido)

                                            <span class="badge bg-warning text-dark">
                                                En proceso
                                            </span>

                                        @else

                                            <span class="badge bg-success">
                                                Atendido
                                            </span>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                    DOCUMENTOS
                                    ================================================== --}}

                                    <td>

                                        @if($consulta && $consulta->atendido)

                                                                    <div class="d-flex justify-content-center flex-wrap gap-1">


                                                                        {{-- ==============================
                                                                        RECETA
                                                                        =============================== --}}

                                                                        <a href="{{ route(
                                                'consultations.pdf',
                                                $consulta->id
                                            ) }}" target="_blank" class="btn btn-info btn-sm" title="Imprimir receta">

                                                                            📄

                                                                        </a>


                                                                        {{-- ==============================
                                                                        RECIBO CONSULTA
                                                                        =============================== --}}

                                                                        <a href="{{ route(
                                                'consultations.receipt',
                                                $consulta->id
                                            ) }}" target="_blank" class="btn btn-dark btn-sm"
                                                                            title="Recibo de consulta médica">

                                                                            🧾

                                                                        </a>


                                                                        {{-- ==============================
                                                                        RECIBO CURACIÓN
                                                                        =============================== --}}

                                                                        @if($curacion)

                                                                                                    <a href="{{ route(
                                                                                'curaciones.pdf',
                                                                                $curacion->id
                                                                            ) }}" target="_blank" class="btn btn-danger btn-sm"
                                                                                                        title="Recibo de curación">

                                                                                                        🛡️

                                                                                                    </a>

                                                                        @endif


                                                                        {{-- ==============================
                                                                        ESTUDIOS
                                                                        =============================== --}}

@foreach($estudios as $estudio)

    @if($estudio->estado == 1)

        <a href="{{ route('estudios.pdf.paciente', $estudio->id) }}"
           target="_blank"
           class="btn btn-primary btn-sm"
           title="Recibo de estudio">
            🩻
        </a>

    @endif

@endforeach


                                                                    </div>

                                        @else

                                            <span class="text-muted small">
                                                Sin documentos
                                            </span>

                                        @endif

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="6" class="text-center text-muted py-5">

                                        <div class="fs-1">
                                            📅
                                        </div>

                                        <h5 class="mt-2">
                                            No hay citas médicas registradas
                                        </h5>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- =====================================================
            PAGINACIÓN
            ====================================================== --}}

            @if($appointmentsList->hasPages())

                <div class="card-footer">

                    {{ $appointmentsList->links() }}

                </div>

            @endif

        </div>

    </div>

@endsection