@extends('layouts.app')

@section('content')

    <div class="container-fluid py-4">

        @php

            $appointment =
                $curacion->consultation?->appointment;

            $paciente =
                $appointment?->patient;

            $doctor =
                $appointment?->doctor;

        @endphp


        {{-- =====================================================
        ENCABEZADO
        ====================================================== --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="mb-1">
                    🛡️ Detalle de Curación
                </h3>

                <p class="text-muted mb-0">
                    {{ $curacion->codigo }}
                </p>

            </div>


            <div class="d-flex gap-2">

                <a href="{{ route(
        'curaciones.pdf',
        $curacion->id
    ) }}" target="_blank" class="btn btn-danger">

                    <i class="bi bi-file-earmark-pdf"></i>
                    Recibo PDF

                </a>


                <a href="{{ route(
        'curaciones.index'
    ) }}" class="btn btn-secondary">

                    <i class="bi bi-arrow-left"></i>
                    Volver

                </a>

            </div>

        </div>


        {{-- =====================================================
        PACIENTE Y DOCTOR
        ====================================================== --}}

        <div class="row g-4 mb-4">

            <div class="col-md-6">

                <div class="card shadow-sm h-100">

                    <div class="card-header bg-primary text-white">

                        <i class="bi bi-person"></i>
                        Paciente

                    </div>

                    <div class="card-body">

                        @if($paciente)

                            <h5>

                                {{ $paciente->nombres }}
                                {{ $paciente->apellidos }}

                            </h5>

                            @if(isset($paciente->ci))

                                <p class="mb-1">

                                    <strong>CI:</strong>

                                    {{ $paciente->ci }}

                                </p>

                            @endif

                        @else

                            <span class="text-muted">
                                Paciente no disponible
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            <div class="col-md-6">

                <div class="card shadow-sm h-100">

                    <div class="card-header bg-dark text-white">

                        <i class="bi bi-person-badge"></i>
                        Doctor

                    </div>

                    <div class="card-body">

                        @if($doctor)

                            <h5>

                                Dr./Dra.

                                {{ $doctor->nombres }}
                                {{ $doctor->apellidos }}

                            </h5>

                        @else

                            <span class="text-muted">
                                Doctor no disponible
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
        DATOS
        ====================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-primary text-white">

                <i class="bi bi-info-circle"></i>

                Información de la Curación

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">

                        <strong>Código:</strong>

                        <br>

                        {{ $curacion->codigo }}

                    </div>


                    <div class="col-md-3">

                        <strong>Fecha:</strong>

                        <br>

                        {{ $curacion->fecha?->format('d/m/Y') }}

                    </div>


                    <div class="col-md-3">

                        <strong>Consulta:</strong>

                        <br>

                        #{{ $curacion->consultation_id }}

                    </div>


                    <div class="col-md-3">

                        <strong>Estado:</strong>

                        <br>

                        @if($curacion->estado)

                            <span class="badge bg-success">
                                Activa
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Anulada
                            </span>

                        @endif

                    </div>

                </div>


                @if($curacion->descripcion)

                    <hr>

                    <strong>
                        Detalle de la curación:
                    </strong>

                    <p class="mt-2 mb-0">

                        {{ $curacion->descripcion }}

                    </p>

                @endif

            </div>

        </div>


        {{-- =====================================================
        INSUMOS
        ====================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-primary text-white">

                <i class="bi bi-box-seam"></i>

                Insumos utilizados

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Elemento
                                </th>

                                <th>
                                    Tipo
                                </th>

                                <th class="text-end">
                                    Cantidad
                                </th>

                                <th class="text-end">
                                    Precio
                                </th>

                                <th class="text-end">
                                    Subtotal
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($curacion->detalles as $detalle)

                                                    <tr>

                                                        <td>

                                                            @if($detalle->insumo)

                                                                {{ $detalle->insumo->nombre }}

                                                            @elseif($detalle->agrupado)

                                                                {{ $detalle->agrupado->nombre }}

                                                            @else

                                                                {{ $detalle->nombre_otro }}

                                                            @endif

                                                        </td>


                                                        <td>

                                                            @if($detalle->insumo)

                                                                <span class="badge bg-primary">
                                                                    Insumo
                                                                </span>

                                                            @elseif($detalle->agrupado)

                                                                <span class="badge bg-info text-dark">
                                                                    Combo
                                                                </span>

                                                            @else

                                                                <span class="badge bg-warning text-dark">
                                                                    Otro
                                                                </span>

                                                            @endif

                                                        </td>


                                                        <td class="text-end">

                                                            {{ number_format(
                                    $detalle->cantidad,
                                    2
                                ) }}

                                                        </td>


                                                        <td class="text-end">

                                                            Bs {{ number_format(
                                    $detalle->precio_unitario,
                                    2
                                ) }}

                                                        </td>


                                                        <td class="text-end">

                                                            <strong>

                                                                Bs {{ number_format(
                                    $detalle->subtotal,
                                    2
                                ) }}

                                                            </strong>

                                                        </td>

                                                    </tr>

                            @empty

                                <tr>

                                    <td colspan="5" class="text-center py-4">

                                        No se registraron insumos.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- =====================================================
        TOTALES
        ====================================================== --}}

        <div class="row justify-content-end mb-4">

            <div class="col-md-5">

                <div class="card shadow-sm">

                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                Total Insumos:
                            </span>

                            <strong>

                                Bs {{ number_format(
        $curacion->total_insumos,
        2
    ) }}

                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                Costo Curación:
                            </span>

                            <strong>

                                Bs {{ number_format(
        $curacion->costo_curacion,
        2
    ) }}

                            </strong>

                        </div>


                        <hr>


                        <div class="d-flex justify-content-between">

                            <h5>
                                Total:
                            </h5>

                            <h5>

                                Bs {{ number_format(
        $curacion->total,
        2
    ) }}

                            </h5>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        @foreach($curacion->distribuciones as $distribucion)

            <tr>

                <td>
                    {{ $distribucion->concepto }}
                </td>

                <td>

                    @if($distribucion->doctor)

                        {{ $distribucion->doctor->nombres }}
                        {{ $distribucion->doctor->apellidos }}

                    @else

                        —

                    @endif

                </td>

                <td>
                    {{ number_format($distribucion->porcentaje, 2) }} %
                </td>

                <td>
                    Bs.
                    {{ number_format($distribucion->monto, 2) }}
                </td>

            </tr>

        @endforeach


        {{-- =====================================================
        RECETA
        ====================================================== --}}

        <div class="card shadow-sm">

            <div class="card-header bg-success text-white">

                <i class="bi bi-prescription2"></i>

                Receta

            </div>


            <div class="card-body">

                @if($curacion->receta)

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <strong>
                                        Receta registrada
                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        La receta fue creada para esta curación.

                                    </small>

                                </div>


                                <a href="{{ route(
                        'curaciones.receta.pdf',
                        $curacion->id
                    ) }}" target="_blank" class="btn btn-success">

                                    <i class="bi bi-file-earmark-pdf"></i>

                                    Imprimir Receta

                                </a>

                            </div>

                @else

                            <div class="d-flex justify-content-between align-items-center">

                                <div>

                                    <strong>
                                        No existe receta
                                    </strong>

                                    <br>

                                    <small class="text-muted">
                                        La receta es opcional.
                                    </small>

                                </div>


                                <a href="{{ route(
                        'curaciones.receta.create',
                        $curacion->id
                    ) }}" class="btn btn-outline-success">

                                    <i class="bi bi-plus-circle"></i>

                                    Agregar Receta

                                </a>

                            </div>

                @endif

            </div>

        </div>

    </div>

@endsection