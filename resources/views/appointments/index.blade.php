@extends('layouts.app')

@section('content')

    <div class="container">

        {{-- ENCABEZADO --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="fw-bold mb-1">
                    📅 Gestión de Citas Médicas
                </h3>

                <small class="text-muted">
                    Administración y seguimiento de citas médicas
                </small>
            </div>

            <a href="{{ route('appointments.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i>
                Nueva Cita
            </a>

        </div>


        {{-- MENSAJE DE ÉXITO --}}
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show" role="alert">

                <i class="bi bi-check-circle-fill"></i>

                <strong>Correcto:</strong>
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- MENSAJE DE ERROR --}}
        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show" role="alert">

                <i class="bi bi-exclamation-triangle-fill"></i>

                <strong>Error:</strong>
                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- MENSAJE DE ADVERTENCIA --}}
        @if(session('warning'))

            <div class="alert alert-warning alert-dismissible fade show" role="alert">

                <i class="bi bi-exclamation-circle-fill"></i>

                <strong>Advertencia:</strong>
                {{ session('warning') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- BUSCADOR --}}
        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <form method="GET" action="{{ route('appointments.index') }}">

                    <div class="row g-3 align-items-end">

                        {{-- CAMPO DE BÚSQUEDA --}}
                        <div class="col-md-8">

                            <label class="form-label fw-bold">
                                Buscar cita
                            </label>

                            <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control"
                                placeholder="Buscar por nombre o apellido de paciente o doctor...">

                        </div>


                        {{-- BOTONES --}}
                        <div class="col-md-4">

                            <div class="d-flex gap-2">

                                {{-- BUSCAR --}}
                                <button type="submit" class="btn btn-primary flex-fill">

                                    <i class="bi bi-search"></i>
                                    Buscar

                                </button>


                                {{-- LIMPIAR --}}
                                <a href="{{ route('appointments.index') }}" class="btn btn-secondary flex-fill">

                                    <i class="bi bi-x-circle"></i>
                                    Limpiar

                                </a>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- TABLA --}}
        <div class="card shadow-sm">

            <div class="card-header">

                <div class="d-flex justify-content-between align-items-center">

                    <strong>
                        <i class="bi bi-calendar-check"></i>
                        Citas registradas
                    </strong>

                    <span class="badge bg-primary">
                        {{ $appointments->total() }}
                        registros
                    </span>

                </div>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

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

                                {{-- FILA PRINCIPAL --}}
                                <tr>

                                    {{-- PACIENTE --}}
                                    <td>

                                        <i class="bi bi-person-fill text-primary"></i>

                                        {{ $a->patient->nombres }}
                                        {{ $a->patient->apellidos }}

                                    </td>


                                    {{-- DOCTOR --}}
                                    <td>

                                        <i class="bi bi-person-badge-fill text-success"></i>

                                        {{ $a->doctor->nombres }}
                                        {{ $a->doctor->apellidos }}

                                    </td>


                                    {{-- FECHA --}}
                                    <td class="text-center">

                                        {{ $a->fecha }}

                                    </td>


                                    {{-- HORA --}}
                                    <td class="text-center">

                                        {{ $a->hora }}

                                    </td>


                                    {{-- ESTADO --}}
                                    <td class="text-center">

                                        <span class="badge

                                                @if($a->estado == 'pendiente')
                                                    bg-warning text-dark

                                                @elseif($a->estado == 'atendido')
                                                    bg-success

                                                @else
                                                    bg-danger

                                                @endif
                                            ">

                                            @if($a->estado == 'pendiente')

                                                <i class="bi bi-clock"></i>

                                            @elseif($a->estado == 'atendido')

                                                <i class="bi bi-check-circle"></i>

                                            @else

                                                <i class="bi bi-x-circle"></i>

                                            @endif

                                            {{ ucfirst($a->estado) }}

                                        </span>

                                    </td>


                                    {{-- ACCIONES --}}
                                    <td class="text-center">

                                        {{-- VER OBSERVACIONES --}}
                                        <button type="button" class="btn btn-success btn-sm" onclick="toggleObs({{ $a->id }})"
                                            title="Ver observaciones">

                                            <i class="bi bi-chat-left-text"></i>

                                        </button>


                                        {{-- EDITAR --}}
                                        <a href="{{ route('appointments.edit', $a->id) }}" class="btn btn-info btn-sm"
                                            title="Editar">

                                            <i class="bi bi-pencil"></i>

                                        </a>

                                    </td>

                                </tr>


                                {{-- FILA DE OBSERVACIONES --}}
                                <tr id="obs-{{ $a->id }}" style="display: none; background: #f8f9fa;">

                                    <td colspan="6">

                                        <div class="p-2">

                                            <strong>
                                                <i class="bi bi-chat-left-text"></i>
                                                Observaciones:
                                            </strong>

                                            <br>

                                            @if($a->observaciones)

                                                <span>
                                                    {{ $a->observaciones }}
                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    Sin observaciones
                                                </span>

                                            @endif

                                        </div>

                                    </td>

                                </tr>


                            @empty

                                {{-- SIN RESULTADOS --}}
                                <tr>

                                    <td colspan="6" class="text-center py-5">

                                        <i class="bi bi-calendar-x fs-1 text-muted"></i>

                                        <h5 class="mt-3 text-muted">
                                            No hay citas registradas
                                        </h5>

                                        @if(!empty($search))

                                            <p class="text-muted">
                                                No se encontraron citas para:
                                                <strong>{{ $search }}</strong>
                                            </p>

                                            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">

                                                <i class="bi bi-x-circle"></i>
                                                Limpiar búsqueda

                                            </a>

                                        @else

                                            <p class="text-muted">
                                                Comienza registrando tu primera cita.
                                            </p>

                                            <a href="{{ route('appointments.create') }}" class="btn btn-success">

                                                <i class="bi bi-plus-circle"></i>
                                                Registrar primera cita

                                            </a>

                                        @endif

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- PAGINACIÓN --}}
        @if($appointments->hasPages())

            <div class="mt-3">

                {{ $appointments->links() }}

            </div>

        @endif

    </div>


    {{-- SCRIPT PARA MOSTRAR OBSERVACIONES --}}
    <script>

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