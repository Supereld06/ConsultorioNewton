@extends('layouts.app')

@section('content')

    <div class="container">

        <h3>Registrar Doctor</h3>

        <form method="POST" action="{{ route('doctors.store') }}" enctype="multipart/form-data">

            @csrf

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Apellidos</label>
                    <input type="text" name="apellidos" class="form-control" oninput="this.value = this.value.toUpperCase();">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Nombres</label>
                    <input type="text" name="nombres" class="form-control" oninput="this.value = this.value.toUpperCase();">
                </div>

                <div class="col-md-4 mb-3">
                    <label>CI</label>
                    <input type="text" name="ci" class="form-control" oninput="this.value = this.value.toUpperCase();">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Especialidad</label>
                    <input type="text" name="especialidad" class="form-control" oninput="this.value = this.value.toUpperCase();">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Foto</label>
                    <input type="file" name="foto" class="form-control">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Hora inicio</label>
                    <input type="time" name="hora_inicio" class="form-control"
                        value="{{ old('hora_inicio', $doctor->hora_inicio ?? '08:00') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Hora fin</label>
                    <input type="time" name="hora_fin" class="form-control"
                        value="{{ old('hora_fin', $doctor->hora_fin ?? '18:00') }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Duración cita (min)</label>
                    <input type="number" name="duracion_cita" class="form-control"
                        value="{{ old('duracion_cita', $doctor->duracion_cita ?? 30) }}">
                </div>

            </div>

            <button class="btn btn-success">
                Guardar Doctor
            </button>

            <a href="{{ route('doctors.index') }}" class="btn btn-secondary">
                Cancelar
            </a>

        </form>

    </div>

@endsection