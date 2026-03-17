@extends('layouts.app')

@section('content')

    <div class="container">

        <h3 class="mb-4">✏ Editar Cita Médica</h3>

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('appointments.update', $appointment->id) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="row">

                <!-- PACIENTE -->
                <div class="col-md-6 mb-3">
                    <label>Paciente</label>
                    <select name="patient_id" class="form-control" required>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}" {{ $appointment->patient_id == $p->id ? 'selected' : '' }}>
                                {{ $p->nombres }} {{ $p->apellidos }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- DOCTOR -->
                <div class="col-md-6 mb-3">
                    <label>Doctor</label>
                    <select name="doctor_id" class="form-control" required>
                        @foreach($doctors as $d)
                            <option value="{{ $d->id }}" {{ $appointment->doctor_id == $d->id ? 'selected' : '' }}>
                                {{ $d->nombres }} {{ $d->apellidos }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- FECHA -->
                <div class="col-md-6 mb-3">
                    <label>Fecha</label>
                    <input type="date" name="fecha" value="{{ $appointment->fecha }}" class="form-control" required>
                </div>

                <!-- HORA -->
                <div class="col-md-6 mb-3">
                    <label>Hora</label>
                    <input type="time" name="hora" value="{{ $appointment->hora }}" class="form-control" required>
                </div>

                <!-- ESTADO -->
                <div class="col-md-6 mb-3">
                    <label>Estado</label>
                    <select name="estado" class="form-control">
                        <option value="pendiente" {{ $appointment->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="atendido" {{ $appointment->estado == 'atendido' ? 'selected' : '' }}>Atendido</option>
                        <option value="cancelado" {{ $appointment->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>

                <!-- OBSERVACIONES -->
                <div class="col-md-12 mb-3">
                    <label>Observaciones</label>
                    <textarea name="observaciones" class="form-control">{{ $appointment->observaciones }}</textarea>
                </div>

            </div>

            <button class="btn btn-success">Actualizar</button>

            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                Volver
            </a>

        </form>

    </div>

@endsection