@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">📅 Gestión de Citas Médicas</h3>

        <a href="{{ route('appointments.create') }}" class="btn btn-success">
            Nueva Cita
        </a>
    </div>

    <div class="card shadow-sm">

        <div class="card-body p-0">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark text-center">
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

                    @forelse($appointments as $a)

                        <tr>

                            <td>{{ $a->patient->nombres }} {{ $a->patient->apellidos }}</td>

                            <td>{{ $a->doctor->nombres }} {{ $a->doctor->apellidos }}</td>

                            <td>{{ $a->fecha }}</td>

                            <td>{{ $a->hora }}</td>

                            <td>
                                <span class="badge 
                                    @if($a->estado=='pendiente') bg-warning
                                    @elseif($a->estado=='atendido') bg-success
                                    @else bg-danger
                                    @endif">
                                    {{ ucfirst($a->estado) }}
                                </span>
                            </td>

                            <td>

                                <!-- VER OBS -->
                                <button class="btn btn-info btn-sm"
                                    onclick="toggleObs({{ $a->id }})">
                                    Ver
                                </button>

                                <!-- EDITAR -->
                                <a href="{{ route('appointments.edit', $a->id) }}"
                                    class="btn btn-warning btn-sm">
                                    Editar
                                </a>

                            </td>

                        </tr>

                        <!-- FILA OCULTA -->
                        <tr id="obs-{{ $a->id }}" style="display:none; background:#f8f9fa;">
                            <td colspan="6">
                                <strong>Observaciones:</strong><br>

                                @if($a->observaciones)
                                    {{ $a->observaciones }}
                                @else
                                    <span class="text-muted">Sin observaciones</span>
                                @endif
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center">
                                No hay citas registradas
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- SCRIPT -->
<script id="p0r9h7">
function toggleObs(id) {
    let fila = document.getElementById('obs-' + id);

    if (fila.style.display === 'none') {
        fila.style.display = 'table-row';
    } else {
        fila.style.display = 'none';
    }
}
</script>

@endsection