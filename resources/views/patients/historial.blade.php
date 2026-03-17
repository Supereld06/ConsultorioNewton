@extends('layouts.app')

@section('content')

    <div class="container mt-4">

        {{-- 🔙 BOTONES SUPERIORES --}}
        <div class="d-flex justify-content-between mb-3">

            <a href="{{ route('patients.index') }}" class="btn btn-secondary">
                ← Volver
            </a>

            <a href="{{ route('patients.historial.pdf', $patient->id) }}" class="btn btn-danger">
                🧾 Descargar Historial PDF
            </a>

        </div>

        <h3 class="mb-4">🧾 Historial Clínico</h3>

        {{-- 🔷 DATOS PACIENTE --}}
        <div class="card mb-4 shadow">
            <div class="card-body">
                <h5>👤 {{ $patient->nombres }} {{ $patient->apellidos }}</h5>
                <p class="text-muted">CI: {{ $patient->ci }}</p>
            </div>
        </div>

        {{-- 🔷 TABLA --}}
        <div class="card shadow">

            <div class="card-header bg-dark text-white">
                📋 Consultas Médicas
            </div>

            <div class="card-body p-0">

                <table class="table table-hover mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Doctor</th>
                            <th>Diagnóstico</th>
                            <th>Tratamiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($patient->appointments as $a)

                            @if($a->consultation && $a->consultation->atendido)

                                <tr>

                                    <td>
                                        {{ $a->fecha }} <br>
                                        <small>{{ $a->hora }}</small>
                                    </td>

                                    <td>
                                        {{ $a->doctor->nombres }}
                                    </td>

                                    <td>
                                        {{ Str::limit($a->consultation->diagnostico, 50) }}
                                    </td>

                                    <td>
                                        {{ Str::limit($a->consultation->tratamiento, 50) }}
                                    </td>

                                    <td>

                                        {{-- 🔥 VER DETALLE ABAJO --}}
                                        <button class="btn btn-info btn-sm" onclick="verDetalle({{ $a->consultation->id }})">
                                            Ver detalle
                                        </button>

                                    </td>

                                </tr>

                            @endif

                        @empty

                            <tr>
                                <td colspan="5" class="text-center">
                                    Sin historial médico
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        {{-- 🔥 DETALLE DINÁMICO --}}
        <div id="detalleConsulta" class="mt-4"></div>

    </div>

    {{-- 🔥 SCRIPT --}}
    <script>

        function verDetalle(id) {

            fetch(`/consultations/${id}`)
                .then(res => res.json())
                .then(data => {

                    document.getElementById('detalleConsulta').innerHTML = `
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            🩺 Detalle de Consulta
                        </div>
                        <div class="card-body">

                            <p><strong>Diagnóstico:</strong><br>${data.diagnostico ?? ''}</p>

                            <p><strong>Tratamiento:</strong><br>${data.tratamiento ?? ''}</p>

                            <p><strong>Receta:</strong><br>${data.receta ?? ''}</p>

                            <p><strong>Observaciones:</strong><br>${data.observaciones ?? ''}</p>

                        </div>
                    </div>
                `;
                });
        }

    </script>

@endsection