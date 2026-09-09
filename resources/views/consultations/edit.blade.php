@extends('layouts.app')

@section('content')

<div class="container py-3">

    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h3 class="fw-bold mb-0">🩺 Atender Consulta</h3>
            <small class="text-muted">
                Registra la información de la consulta
            </small>
        </div>

        <button type="button"
                class="btn btn-info"
                onclick="window.history.back()">
            ← Volver
        </button>

    </div>


    {{-- INFORMACIÓN DEL PACIENTE --}}
    <div class="card shadow-sm border-0 mb-3">

        <div class="card-body py-2">

            <div class="row">

                <div class="col-md-6">
                    <strong>👤 Paciente:</strong>
                    {{ $appointment->patient->nombres }}
                    {{ $appointment->patient->apellidos }}
                </div>

                <div class="col-md-6">
                    <strong>👨‍⚕️ Doctor:</strong>
                    {{ $appointment->doctor->nombres }}
                    {{ $appointment->doctor->apellidos }}
                </div>

            </div>

        </div>

    </div>


    {{-- FORMULARIO --}}
    <form method="POST"
          action="{{ route('consultations.update', $consultation->id) }}">

        @csrf
        @method('PUT')


        <div class="card shadow-sm border-0">

            <div class="card-body">

                <div class="row g-3">

                    {{-- MOTIVO --}}
                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            🤕 Motivo de la consulta
                        </label>

                        <textarea
                            name="motivo_consulta"
                            class="form-control"
                            rows="3"
                            placeholder="Ej: Dolor de cabeza desde hace 3 días...">{{ $consultation->motivo_consulta }}</textarea>

                    </div>


                    {{-- CUADRO CLÍNICO --}}
                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            🩹 Cuadro clínico
                        </label>

                        <textarea
                            name="cuadro_clinico"
                            class="form-control"
                            rows="3"
                            placeholder="Ej: Cefalea frontal, intensidad moderada...">{{ $consultation->cuadro_clinico }}</textarea>

                    </div>


                    {{-- DIAGNÓSTICO --}}
                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            🔎 Diagnóstico
                        </label>

                        <textarea
                            name="diagnostico"
                            class="form-control"
                            rows="3"
                            placeholder="Ej: Cefalea tensional...">{{ $consultation->diagnostico }}</textarea>

                    </div>


                    {{-- ESTUDIOS --}}
                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            🧪 Estudios
                        </label>

                        <textarea
                            name="estudios"
                            class="form-control"
                            rows="3"
                            placeholder="Ej: Hemograma, examen de orina...">{{ $consultation->estudios }}</textarea>

                    </div>


                    {{-- RECETA --}}
                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            💊 Receta
                        </label>

                        <textarea
                            name="receta"
                            class="form-control"
                            rows="3"
                            placeholder="Ej: Paracetamol 500 mg cada 8 horas...">{{ $consultation->receta }}</textarea>

                    </div>


                    {{-- TRATAMIENTO --}}
                    <div class="col-md-6">

                        <label class="form-label fw-bold">
                            🏥 Tratamiento
                        </label>

                        <textarea
                            name="tratamiento"
                            class="form-control"
                            rows="3"
                            placeholder="Ej: Reposo, hidratación y control...">{{ $consultation->tratamiento }}</textarea>

                    </div>


                    {{-- OBSERVACIONES --}}
                    <div class="col-md-12">

                        <label class="form-label fw-bold">
                            📝 Observaciones
                        </label>

                        <textarea
                            name="observaciones"
                            class="form-control"
                            rows="2"
                            placeholder="Ej: Paciente estable. Control en 7 días...">{{ $consultation->observaciones }}</textarea>

                    </div>

                </div>


                {{-- BOTONES --}}
                <div class="d-flex justify-content-end gap-2 mt-3">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        onclick="window.history.back()">

                         Cancelar

                    </button>

                    <button
                        type="submit"
                        class="btn btn-success">

                        Guardar y marcar como atendido

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

@endsection


