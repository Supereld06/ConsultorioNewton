@extends('layouts.app')

@section('content')

    <div class="container-fluid py-3">


        {{-- ENCABEZADO --}}
        <div class="d-flex justify-content-between align-items-center mb-3">

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


        {{-- MENSAJE --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show py-2">
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>
            </div>
        @endif


        {{-- BUSCADOR --}}

        <div class="card shadow-sm mb-3">

            
            <div class="card-body py-2">

                <form action="{{ route('estudios.index') }}" method="GET">

                    <div class="row g-2 align-items-end">

                        {{-- CAMPO DE BÚSQUEDA --}}
                        <div class="col-md-9">

                            <label class="form-label mb-1">
                                Buscar estudio
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>

                                <input type="text" name="buscar" class="form-control" value="{{ request('buscar') }}"
                                    placeholder="Código, paciente o doctor..." autocomplete="off">

                            </div>

                        </div>


                        {{-- BOTONES --}}
                        <div class="col-md-3">

                            <div class="d-flex gap-2">

                                {{-- BUSCAR --}}
                                <button type="submit" class="btn btn-primary flex-grow-1">

                                    <i class="bi bi-search"></i>
                                    Buscar

                                </button>


                                {{-- LIMPIAR --}}
                      

                                    <a href="{{ route('estudios.index') }}" class="btn btn-outline-secondary"
                                        title="Limpiar búsqueda">

                                        <i class="bi bi-x-lg"></i>
                                        Limpiar

                                    </a>

                            </div>

                        </div>

                    </div>

                </form>

            </div>
            

        </div>



        {{-- TABLA --}}
        <div class="card shadow">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover table-sm align-middle mb-0">

                        <thead>

                            <tr>

                                <th>Código</th>

                                <th>Fecha</th>

                                <th>Paciente</th>

                                <th>Doctor</th>

                                <th class="text-end">
                                    Total paciente
                                </th>

                                <th class="text-end">
                                    Costo laboratorio
                                </th>

                                <th class="text-end">
                                    Utilidad
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

                            @forelse($estudios as $estudio)

                                @php

                                    $paciente =
                                        $estudio->consultation?->appointment?->patient;

                                    $doctor =
                                        $estudio->consultation?->appointment?->doctor;

                                @endphp

                                <tr>

                                    {{-- CÓDIGO --}}
                                    <td>

                                        <strong>
                                            {{ $estudio->codigo }}
                                        </strong>

                                    </td>


                                    {{-- FECHA --}}
                                    <td>

                                        {{ $estudio->fecha?->format('d/m/Y') }}

                                    </td>


                                    {{-- PACIENTE --}}
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


                                    {{-- TOTAL PACIENTE --}}
                                    <td class="text-end">

                                        Bs.
                                        {{ number_format($estudio->total_cobrado, 2) }}

                                    </td>


                                    {{-- COSTO LABORATORIO --}}
                                    <td class="text-end">

                                        Bs.
                                        {{ number_format($estudio->total_laboratorio, 2) }}

                                    </td>


                                    {{-- UTILIDAD --}}
                                    <td class="text-end">

                                        <strong>
                                            Bs.
                                            {{ number_format($estudio->utilidad, 2) }}
                                        </strong>

                                    </td>


                                    {{-- ESTADO --}}
                                    <td class="text-center">

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


                                    {{-- ACCIONES --}}
                                    <td>

                                        <div class="btn-group btn-group-sm">

                                            {{-- VER --}}
                                            <a href="{{ route('estudios.show', $estudio->id) }}" class="btn btn-info"
                                                title="Ver estudio">

                                                <i class="bi bi-eye"></i>

                                            </a>


                                            {{-- RECIBO PACIENTE --}}
                                            @if($estudio->estado)

                                                <a href="{{ route('estudios.pdf.paciente', $estudio->id) }}" target="_blank"
                                                    class="btn btn-success" title="Imprimir recibo del paciente">

                                                    <i class="bi bi-receipt"></i>

                                                </a>


                                                {{-- RECIBO LABORATORIO --}}
                                                <a href="{{ route('estudios.pdf.laboratorio', $estudio->id) }}" target="_blank"
                                                    class="btn btn-secondary" title="Imprimir recibo del laboratorio">

                                                    <i class="bi bi-file-earmark-medical"></i>

                                                </a>


                                                {{-- EDITAR --}}
                                                <a href="{{ route('estudios.edit', $estudio->id) }}" class="btn btn-warning"
                                                    title="Editar estudio">

                                                    <i class="bi bi-pencil"></i>

                                                </a>


                                                {{-- ANULAR --}}
                                                <form action="{{ route('estudios.destroy', $estudio->id) }}" method="POST"
                                                    class="d-inline">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger" title="Anular estudio"
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

                                        @if(request('buscar'))

                                            No se encontraron estudios para:

                                            <strong>
                                                "{{ request('buscar') }}"
                                            </strong>

                                        @else

                                            No existen estudios complementarios
                                            registrados.

                                        @endif

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- PAGINACIÓN --}}
                @if($estudios->hasPages())

                    <div class="mt-3">

                        {{ $estudios->withQueryString()->links() }}

                    </div>

                @endif

            </div>

        </div>


    </div>

@endsection