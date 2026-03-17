@extends('layouts.app')

@section('content')

<div class="container">

    <div class="d-flex justify-content-between mb-4">

        <h3>Listado de Doctores</h3>

        <a href="{{ route('doctors.create') }}" class="btn btn-success">
            <i class="bi bi-person-plus"></i> Nuevo Doctor
        </a>

    </div>

    <!-- BUSCADOR -->
    <form method="GET" class="mb-3">

        <div class="input-group">

            <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control"
                placeholder="Buscar por apellido, nombre o CI">

            <button class="btn btn-primary">
                Buscar
            </button>

        </div>

    </form>

    <!-- TABLA -->
    <div class="card">

        <div class="card-body p-0">

            <table class="table table-hover align-middle">

                <thead class="table-dark">

                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Doctor</th>
                        <th>CI</th>
                        <th>Especialidad</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($doctors as $doctor)

                        <tr>

                            <td>{{ $doctor->id }}</td>

                            <!-- FOTO -->
                            <td>
                                @if($doctor->foto)
                                    <img src="{{ asset('storage/'.$doctor->foto) }}"
                                        class="rounded-circle shadow"
                                        width="60" height="60"
                                        style="object-fit: cover;">
                                @else
                                    <img src="https://via.placeholder.com/60"
                                        class="rounded-circle"
                                        width="60" height="60">
                                @endif
                            </td>

                            <!-- NOMBRE -->
                            <td>
                                <strong>{{ $doctor->nombres }} {{ $doctor->apellidos }}</strong>
                            </td>

                            <td>{{ $doctor->ci }}</td>

                            <td>
                                <span class="badge bg-info">
                                    {{ $doctor->especialidad }}
                                </span>
                            </td>

                            <td>{{ $doctor->telefono }}</td>

                            <td>

                                <!-- EDITAR -->
                                <a href="{{ route('doctors.edit', $doctor->id) }}" class="btn btn-warning btn-sm">
                                    Editar
                                </a>

                                <!-- ELIMINAR -->
                                <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST"
                                    style="display:inline">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de eliminar este doctor?')">
                                        Eliminar
                                    </button>

                                </form>

                                <!-- CONSULTAS -->
                                <a href="#" class="btn btn-primary btn-sm">
                                    Consultas
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center">
                                No hay doctores registrados
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <!-- PAGINACIÓN -->
    <div class="mt-3">
        {{ $doctors->links() }}
    </div>

</div>

@endsection