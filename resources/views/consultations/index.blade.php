@extends('layouts.app')

@section('content')

    <div class="container">

        <h3 class="mb-4">Consultas Médicas</h3>

        <!-- FILTRO -->
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <select name="doctor_id" class="form-control">
                        <option value="">-- Filtrar por doctor --</option>
                        @foreach($doctors as $d)
                            <option value="{{ $d->id }}" {{ request('doctor_id') == $d->id ? 'selected' : '' }}>
                                {{ $d->nombres }} {{ $d->apellidos }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>

        <div class="card shadow">
            <div class="card-body p-0">

                <table class="table table-hover">

                    <thead class="table-dark">
                        <tr>
                            <th>Paciente</th>
                            <th>Doctor</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($appointments as $a)

                            @php
                                $consulta = $a->consultation;
                            @endphp

                            <tr>
                                <td>{{ $a->patient->nombres }}</td>
                                <td>{{ $a->doctor->nombres }}</td>
                                <td>{{ $a->fecha }}</td>
                                <td>{{ $a->hora }}</td>

                                <td>
                                    @if(!$consulta)
                                        <span class="badge bg-secondary">Sin atención</span>
                                    @elseif(!$consulta->atendido)
                                        <span class="badge bg-warning text-dark">En proceso</span>
                                    @else
                                        <span class="badge bg-success">Atendido</span>
                                    @endif
                                </td>

                                <td>

                                    <!-- ATENDER -->
                                    <a href="{{ route('consultations.atender', $a->id) }}" class="btn btn-success btn-sm">
                                        🩺 Atender
                                    </a>

                                    <!-- PAGO -->
                                    <button class="btn btn-primary btn-sm">
                                        💳 Pago
                                    </button>

                                    <!-- PDF -->
                                    @if($consulta && $consulta->atendido)
                                        <a href="{{ route('consultations.pdf', $consulta->id) }}" class="btn btn-secondary btn-sm">
                                            📄 PDF
                                        </a>
                                    @endif

                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>
        </div>

        <div class="mt-3">
            {{ $appointments->links() }}
        </div>

    </div>

@endsection