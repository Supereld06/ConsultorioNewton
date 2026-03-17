@extends('layouts.app')

@section('content')

    @php
        use Carbon\Carbon;
    @endphp

    <div class="container">

        <!-- TITULO + BOTON -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <h3 class="mb-0">Listado de Pacientes</h3>

            <a href="{{ route('patients.create') }}" class="btn btn-success">
                <i class="bi bi-person-plus"></i> Nuevo Paciente
            </a>

        </div>


        <!-- BUSCADOR -->
        <div class="card mb-4">

            <div class="card-body">

                <form method="GET">

                    <div class="input-group">

                        <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control"
                            placeholder="Buscar por nombre, apellido o CI">

                        <button class="btn btn-primary">
                            <i class="bi bi-search"></i> Buscar
                        </button>

                    </div>

                </form>

            </div>

        </div>


        <!-- TABLA -->
        <div class="card shadow-sm">

            <div class="card-body p-0">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">

                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>CI</th>
                            <th>Edad</th>
                            <th>Teléfono</th>
                            <th>Historial</th>
                            <th width="180">Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($patients as $patient)

                            <tr>

                                <td>{{ $patient->id }}</td>

                                <!-- PACIENTE -->
                                <td>

                                    <div class="d-flex align-items-center">

                                        @if($patient->fotografia)

                                            <img src="{{ asset('storage/' . $patient->fotografia) }}" width="45" height="45"
                                                class="rounded-circle me-3" style="object-fit:cover;">

                                        @else

                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3"
                                                style="width:45px;height:45px;font-weight:bold;">

                                                {{ strtoupper(substr($patient->nombres, 0, 1)) }}

                                            </div>

                                        @endif


                                        <div>

                                            <strong>
                                                {{ $patient->nombres }} {{ $patient->apellidos }}
                                            </strong>

                                            <div class="text-muted small">
                                                {{ Carbon::parse($patient->fecha_nacimiento)->format('d/m/Y') }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                <!-- CI -->
                                <td>

                                    <span class="">
                                        {{ $patient->ci }}
                                    </span>

                                </td>


                                <!-- EDAD -->
                                <td>

                                    <span class="">
                                        {{ Carbon::parse($patient->fecha_nacimiento)->age }} años
                                    </span>

                                </td>


                                <!-- TELEFONO -->
                                <td>

                                    {{ $patient->telefono }}

                                </td>

                                <td>
                                    
                                    <a href="{{ route('patients.historial', $patient->id) }}" class="btn btn-info btn-sm">
                                        Historial
                                    </a>
                                </td>

                                <!-- ACCIONES -->
                                <td>

                                    <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning btn-sm">

                                        <i class="bi bi-pencil">Editar</i>

                                    </a>

                                    <form action="{{ route('patients.destroy', $patient->id) }}" method="POST"
                                        style="display:inline">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar paciente?')">

                                            <i class="bi bi-trash">Eliminar</i>

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center p-4">

                                    <div class="text-muted">

                                        No existen pacientes registrados

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        <!-- PAGINACION -->
        <div class="mt-4">

            {{ $patients->links() }}

        </div>

    </div>

@endsection