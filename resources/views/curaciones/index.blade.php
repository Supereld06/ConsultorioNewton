@extends('layouts.app')

@section('content')

    <div class="container-fluid py-4">

        {{-- =====================================================
        ENCABEZADO
        ====================================================== --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="mb-1">
                    🛡️ Curaciones
                </h3>

                <p class="text-muted mb-0">
                    Registro y control de curaciones realizadas
                </p>

            </div>

            <a href="{{ route('curaciones.create') }}" class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>
                Nueva Curación

            </a>

        </div>


        {{-- =====================================================
        MENSAJES
        ====================================================== --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="bi bi-check-circle-fill"></i>

                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="bi bi-exclamation-triangle-fill"></i>

                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- =====================================================
        BUSCADOR
        ====================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <form method="GET" action="{{ route('curaciones.index') }}">

                    <div class="row g-2">

                        <div class="col-md-10">

                            <input type="text" name="buscar" class="form-control" value="{{ $buscar ?? '' }}"
                                placeholder="Buscar por código, paciente o doctor...">

                        </div>

                        <div class="col-md-2">

                            <button type="submit" class="btn btn-primary w-100">

                                <i class="bi bi-search"></i>
                                Buscar

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- =====================================================
        TABLA
        ====================================================== --}}

        <div class="card shadow-sm">

            <div class="card-header bg-dark text-white">

                <i class="bi bi-shield-plus"></i>

                Historial de Curaciones

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover table-bordered mb-0 align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Código
                                </th>

                                <th>
                                    Fecha
                                </th>

                                <th>
                                    Paciente
                                </th>

                                <th>
                                    Doctor
                                </th>

                                <th>
                                    Consulta
                                </th>

                                <th class="text-end">
                                    Insumos
                                </th>

                                <th class="text-end">
                                    Costo Curación
                                </th>

                                <th class="text-end">
                                    Total
                                </th>

                                <th class="text-center">
                                    Estado
                                </th>

                                <th class="text-center">
                                    Acciones
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($curaciones as $curacion)

                                                    @php

                                                        $appointment =
                                                            $curacion->consultation?->appointment;

                                                        $paciente =
                                                            $appointment?->patient;

                                                        $doctor =
                                                            $appointment?->doctor;

                                                    @endphp

                                                    <tr>

                                                        {{-- CÓDIGO --}}

                                                        <td>

                                                            <span class="badge bg-secondary">

                                                                {{ $curacion->codigo }}

                                                            </span>

                                                        </td>


                                                        {{-- FECHA --}}

                                                        <td>

                                                            {{ $curacion->fecha?->format('d/m/Y') }}

                                                        </td>


                                                        {{-- PACIENTE --}}

                                                        <td>

                                                            @if($paciente)

                                                                <strong>
                                                                    {{ $paciente->nombres }}
                                                                    {{ $paciente->apellidos }}
                                                                </strong>

                                                            @else

                                                                <span class="text-muted">
                                                                    Sin paciente
                                                                </span>

                                                            @endif

                                                        </td>


                                                        {{-- DOCTOR --}}

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


                                                        {{-- CONSULTA --}}

                                                        <td>

                                                            <span class="badge bg-info text-dark">

                                                                Consulta #{{ $curacion->consultation_id }}

                                                            </span>

                                                        </td>


                                                        {{-- INSUMOS --}}

                                                        <td class="text-end">

                                                            Bs {{ number_format(
                                    $curacion->total_insumos,
                                    2
                                ) }}

                                                        </td>


                                                        {{-- COSTO CURACIÓN --}}

                                                        <td class="text-end">

                                                            Bs {{ number_format(
                                    $curacion->costo_curacion,
                                    2
                                ) }}

                                                        </td>


                                                        {{-- TOTAL --}}

                                                        <td class="text-end">

                                                            <strong>

                                                                Bs {{ number_format(
                                    $curacion->total,
                                    2
                                ) }}

                                                            </strong>

                                                        </td>


                                                        {{-- ESTADO --}}

                                                        <td class="text-center">

                                                            @if($curacion->estado)

                                                                <span class="badge bg-success">
                                                                    Activa
                                                                </span>

                                                            @else

                                                                <span class="badge bg-danger">
                                                                    Anulada
                                                                </span>

                                                            @endif

                                                        </td>


                                                        {{-- ACCIONES --}}

                                                        <td>

                                                            <div class="d-flex justify-content-center gap-1">

                                                                {{-- VER --}}

                                                                <a href="{{ route(
                                    'curaciones.show',
                                    $curacion->id
                                ) }}" class="btn btn-info btn-sm" title="Ver detalle">

                                                                    <i class="bi bi-eye"></i>

                                                                </a>


                                                                {{-- EDITAR --}}

                                                                <a href="{{ route(
                                    'curaciones.edit',
                                    $curacion->id
                                ) }}" class="btn btn-warning btn-sm" title="Editar">

                                                                    <i class="bi bi-pencil-square"></i>

                                                                </a>


                                                                {{-- RECIBO --}}

                                                                <a href="{{ route(
                                    'curaciones.pdf',
                                    $curacion->id
                                ) }}" target="_blank" class="btn btn-danger btn-sm" title="Recibo">

                                                                    <i class="bi bi-file-earmark-pdf-fill"></i>

                                                                </a>


                                                                {{-- RECETA --}}

                                                                @if($curacion->receta)

                                                                                                <a href="{{ route(
                                                                        'curaciones.receta.pdf',
                                                                        $curacion->id
                                                                    ) }}" target="_blank" class="btn btn-success btn-sm" title="PDF Receta">

                                                                                                    <i class="bi bi-file-earmark-medical"></i>

                                                                                                </a>

                                                                @else

                                                                                                <a href="{{ route(
                                                                        'curaciones.receta.create',
                                                                        $curacion->id
                                                                    ) }}" class="btn btn-outline-success btn-sm" title="Agregar receta">

                                                                                                    <i class="bi bi-prescription2"></i>

                                                                                                </a>

                                                                @endif


                                                                {{-- ELIMINAR --}}

                                                                <form action="{{ route(
                                    'curaciones.destroy',
                                    $curacion->id
                                ) }}" method="POST" onsubmit="return confirm(
                                                                            '¿Está seguro de eliminar esta curación?'
                                                                          );">

                                                                    @csrf

                                                                    @method('DELETE')

                                                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">

                                                                        <i class="bi bi-trash"></i>

                                                                    </button>

                                                                </form>

                                                            </div>

                                                        </td>

                                                    </tr>

                            @empty

                                <tr>

                                    <td colspan="10" class="text-center py-5">

                                        <i class="bi bi-shield-plus fs-1 text-muted"></i>

                                        <h5 class="mt-3">
                                            No hay curaciones registradas
                                        </h5>

                                        <p class="text-muted">
                                            Comience registrando una nueva curación.
                                        </p>

                                        <a href="{{ route('curaciones.create') }}" class="btn btn-primary">

                                            <i class="bi bi-plus-circle"></i>

                                            Nueva Curación

                                        </a>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            @if($curaciones->hasPages())

                <div class="card-footer">

                    {{ $curaciones->links() }}

                </div>

            @endif

        </div>

    </div>

@endsection