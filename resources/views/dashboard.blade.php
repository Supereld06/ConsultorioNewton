@extends('layouts.app')

@section('content')

    <div class="container mt-4">

        <h2 class="mb-4">🏥 Panel de Control</h2>

        {{-- 🔷 TARJETAS --}}
        <div class="row g-4">

            <!-- Pacientes -->
            <div class="col-md-3">
                <div class="card shadow text-center">
                    <div class="card-body">
                        <h5>👨 Pacientes</h5>
                        <h2 class="fw-bold text-primary">
                            {{ $patients }}
                        </h2>
                        <p class="text-muted">Registrados</p>
                        <a href="{{ route('patients.index') }}" class="btn btn-primary btn-sm">
                            Ver pacientes
                        </a>
                    </div>
                </div>
            </div>

            <!-- Citas Hoy -->
            <div class="col-md-3">
                <div class="card shadow text-center">
                    <div class="card-body">
                        <h5>📅 Citas hoy</h5>
                        <h2 class="fw-bold text-success">
                            {{ $appointmentsToday }}
                        </h2>
                        <p class="text-muted">Programadas hoy</p>
                        <a href="{{ route('appointments.index') }}" class="btn btn-success btn-sm">
                            Ver citas
                        </a>
                    </div>
                </div>
            </div>

            <!-- Consultas Hoy -->
            <div class="col-md-3">
                <div class="card shadow text-center">
                    <div class="card-body">
                        <h5>🩺 Consultas hoy</h5>
                        <h2 class="fw-bold text-warning">
                            {{ $consultationsToday }}
                        </h2>
                        <p class="text-muted">Atendidas</p>
                        <a href="{{ route('consultations.index') }}" class="btn btn-warning btn-sm">
                            Ver consultas
                        </a>
                    </div>
                </div>
            </div>

            <!-- Doctores -->
            <div class="col-md-3">
                <div class="card shadow text-center">
                    <div class="card-body">
                        <h5>👨‍⚕️ Doctores</h5>
                        <h2 class="fw-bold text-info">
                            {{ $doctors }}
                        </h2>
                        <p class="text-muted">Activos</p>
                        <a href="{{ route('doctors.index') }}" class="btn btn-info btn-sm">
                            Ver doctores
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- 🔥 LISTADO DE CITAS DE HOY --}}
        <div class="card mt-5 shadow">

            <div class="card-header bg-dark text-white">
                📅 Citas Médicas de Hoy
            </div>

            <div class="card-body p-0">

                <table class="table table-hover mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Hora</th>
                            <th>Paciente</th>
                            <th>Doctor</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($appointmentsList as $a)

                            <tr>

                                <td>
                                    <strong>{{ $a->hora }}</strong>
                                </td>

                                <td>
                                    {{ $a->patient->nombres }}
                                </td>

                                <td>
                                    {{ $a->doctor->nombres }}
                                </td>

                                <td>

                                    @if($a->consultation && $a->consultation->atendido)
                                        <span class="badge bg-success">Atendido</span>
                                    @else
                                        <span class="badge bg-danger">Pendiente</span>
                                    @endif

                                </td>

                                <td>

                                    {{-- ATENDER --}}
                                    <a href="{{ route('consultations.atender', $a->id) }}" class="btn btn-sm btn-primary">
                                        Atender
                                    </a>

                                    {{-- PDF --}}
                                    @if($a->consultation && $a->consultation->atendido)
                                        <a href="{{ route('consultations.pdf', $a->consultation->id) }}"
                                            class="btn btn-sm btn-secondary">
                                            PDF
                                        </a>
                                    @endif

                                    {{-- PAGO (FUTURO) --}}
                                    <button class="btn btn-sm btn-success">
                                        Pago
                                    </button>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No hay citas para hoy
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection