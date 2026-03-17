@extends('layouts.app')

@section('content')

    <div class="container">

        <h3>Atender Consulta</h3>

        <div class="card p-4">

            <p><strong>Paciente:</strong> {{ $appointment->patient->nombres }}</p>
            <p><strong>Doctor:</strong> {{ $appointment->doctor->nombres }}</p>

            <form method="POST" action="{{ route('consultations.update', $consultation->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Diagnóstico</label>
                    <textarea name="diagnostico" class="form-control" required>{{ $consultation->diagnostico }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Tratamiento</label>
                    <textarea name="tratamiento" class="form-control">{{ $consultation->tratamiento }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Receta</label>
                    <textarea name="receta" class="form-control">{{ $consultation->receta }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Observaciones</label>
                    <textarea name="observaciones" class="form-control">{{ $consultation->observaciones }}</textarea>
                </div>

                <button class="btn btn-success">
                    Guardar y marcar como atendido
                </button>

            </form>

        </div>

    </div>

@endsection