@extends('layouts.app')

@section('content')




        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h3 class="mb-0">
                    🩻 Estudios Complementarios
                </h3>

                <small class="text-muted">
                    Registro de estudios externos
                </small>
            </div>

            <a href="{{ route('estudios.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i>
                Registrar estudio
            </a>

        </div>
 


    <div class="container py-4">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        <div class="card shadow">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Fecha</th>
                                <th>Paciente</th>
                                <th>Doctor</th>
                                <th>Total paciente</th>
                                <th>Costo laboratorio</th>
                                <th>Utilidad</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($estudios as $estudio)

                                @php
                                    $paciente =
                                        $estudio->consultation?->appointment?->patient;

                                    $doctor =
                                        $estudio->consultation?->appointment?->doctor;
                                @endphp

                                <tr>

                                    <td>
                                        <strong>
                                            {{ $estudio->codigo }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $estudio->fecha?->format('d/m/Y') }}
                                    </td>

                                    <td>
                                        @if($paciente)
                                            {{ $paciente->nombres }}
                                            {{ $paciente->apellidos }}
                                        @else
                                            <span class="text-muted">
                                                Sin paciente
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($doctor)
                                            Dr./Dra.
                                            {{ $doctor->nombres }}
                                            {{ $doctor->apellidos }}
                                        @else
                                            <span class="text-muted">
                                                Sin doctor
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        Bs.
                                        {{ number_format($estudio->total_cobrado, 2) }}
                                    </td>

                                    <td>
                                        Bs.
                                        {{ number_format($estudio->total_laboratorio, 2) }}
                                    </td>

                                    <td>
                                        Bs.
                                        {{ number_format($estudio->utilidad, 2) }}
                                    </td>

                                    <td>

                                        @if($estudio->estado)
                                            <span class="badge bg-success">
                                                Activo
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Anulado
                                            </span>
                                        @endif

                                    </td>

                                    <td>

                                        <div class="btn-group">

                                            <a href="{{ route('estudios.show', $estudio->id) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            @if($estudio->estado)

                                                <a href="{{ route('estudios.edit', $estudio->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                <form action="{{ route('estudios.destroy', $estudio->id) }}" method="POST"
                                                    class="d-inline">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('¿Deseas anular este estudio?')">

                                                        <i class="bi bi-x-circle"></i>

                                                    </button>

                                                </form>

                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">

                                        No existen estudios complementarios registrados.

                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                <div class="mt-3">
                    {{ $estudios->links() }}
                </div>

            </div>

        </div>

    </div>

@endsection