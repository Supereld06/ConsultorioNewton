@extends('layouts.app')

@section('content')

    <div class="container">

        <h3>Atender Consulta</h3>

        <div class="card p-4">

            <p><strong>Paciente:</strong> {{ $appointment->patient->nombres }} {{ $appointment->patient->apellidos }}</p>
            <p><strong>Doctor:</strong> {{ $appointment->doctor->nombres }} {{ $appointment->doctor->apellidos }}</p>

            <form method="POST" action="{{ route('consultations.update', $consultation->id) }}">
                @csrf
                @method('PUT')
<br>
                <div class="mb-3">
                    <label>Motivo de la Consulta</label>
                    <textarea name="motivo_consulta" class="form-control" required oninput="this.value = this.value.toUpperCase();">{{ $consultation->motivo_consulta }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Cuadro Clínico</label>
                    <textarea name="cuadro_clinico" class="form-control" oninput="this.value = this.value.toUpperCase();">{{ $consultation->cuadro_clinico }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Diagnóstico</label>
                    <textarea name="diagnostico" class="form-control" oninput="this.value = this.value.toUpperCase();">{{ $consultation->diagnostico }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Estudios</label>
                    <textarea name="estudios" class="form-control" oninput="this.value = this.value.toUpperCase();">{{ $consultation->estudios }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Receta</label>
                    <textarea name="receta" class="form-control" oninput="this.value = this.value.toUpperCase();">{{ $consultation->receta }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Tratamiento</label>
                    <textarea name="tratamiento" class="form-control" oninput="this.value = this.value.toUpperCase();">{{ $consultation->tratamiento }}</textarea> 
                </div>

                <div class="mb-3">
                    <label>Observaciones</label>
                    <textarea name="observaciones" class="form-control" oninput="this.value = this.value.toUpperCase();">{{ $consultation->observaciones }}</textarea>
                </div>

                <button class="btn btn-success">
                    Guardar y marcar como atendido
                </button>

                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                    Cancelar
                </button>

            </form>

        </div>

    </div>

@endsection