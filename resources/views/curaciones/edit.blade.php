@extends('layouts.app')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3>
                    <i class="bi bi-pencil-square"></i>
                    Curación {{ $curacion->codigo }}
                </h3>

                <small class="text-muted">
                    Edición de curación
                </small>
            </div>

            <a href="{{ route('curaciones.show', $curacion->id) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Volver
            </a>

        </div>


        @if($errors->any())

            <div class="alert alert-danger">

                <strong>Se encontraron errores:</strong>

                <ul class="mb-0">

                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        <div class="card shadow">

            <div class="card-body">

                <div class="alert alert-warning">

                    <i class="bi bi-exclamation-triangle"></i>

                    Las curaciones que ya generaron movimientos de
                    inventario no deben modificarse directamente, porque
                    podrían alterar el stock histórico.

                </div>


                <div class="row">

                    <div class="col-md-6">

                        <label class="form-label">
                            Consulta
                        </label>

                        <select class="form-select" disabled>

                            @foreach($consultations as $consultation)

                                                    <option value="{{ $consultation->id }}" @selected(
                                                        $consultation->id ==
                                                        $curacion->consultation_id
                                                    )>

                                                        Consulta #{{ $consultation->id }}

                                                        -
                                                        {{ optional(
                                    optional(
                                        $consultation->appointment
                                    )->patient
                                )->nombre }}

                                                        {{ optional(
                                    optional(
                                        $consultation->appointment
                                    )->patient
                                )->apellido }}

                                                    </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Fecha
                        </label>

                        <input type="date" class="form-control" value="{{ $curacion->fecha->format('Y-m-d') }}" disabled>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label">
                            Código
                        </label>

                        <input type="text" class="form-control" value="{{ $curacion->codigo }}" disabled>

                    </div>

                </div>


                <hr>


                <div class="mb-3">

                    <label class="form-label">
                        Descripción
                    </label>

                    <textarea class="form-control" rows="3" disabled>{{ $curacion->descripcion }}</textarea>

                </div>


                <h5 class="mt-4">
                    <i class="bi bi-box-seam"></i>
                    Detalles utilizados
                </h5>


                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead>

                            <tr>

                                <th>Tipo</th>
                                <th>Elemento</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Subtotal</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($curacion->detalles as $detalle)

                                                    <tr>

                                                        <td>

                                                            @if($detalle->insumo_id)
                                                                Insumo

                                                            @elseif($detalle->insumo_agrupado_id)
                                                                Agrupado

                                                            @else
                                                                Otro
                                                            @endif

                                                        </td>

                                                        <td>
                                                            {{ $detalle->nombre }}
                                                        </td>

                                                        <td>
                                                            {{ number_format($detalle->cantidad, 2) }}
                                                        </td>

                                                        <td>
                                                            Bs.
                                                            {{ number_format(
                                    $detalle->precio_unitario,
                                    2
                                ) }}
                                                        </td>

                                                        <td>
                                                            Bs.
                                                            {{ number_format(
                                    $detalle->subtotal,
                                    2
                                ) }}
                                                        </td>

                                                    </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                <div class="row justify-content-end">

                    <div class="col-md-4">

                        <div class="card">

                            <div class="card-body">

                                <p class="d-flex justify-content-between">

                                    <strong>Insumos:</strong>

                                    <span>
                                        Bs.
                                        {{ number_format(
        $curacion->total_insumos,
        2
    ) }}
                                    </span>

                                </p>


                                <p class="d-flex justify-content-between">

                                    <strong>Curación:</strong>

                                    <span>
                                        Bs.
                                        {{ number_format(
        $curacion->costo_curacion,
        2
    ) }}
                                    </span>

                                </p>


                                <hr>


                                <h5 class="d-flex justify-content-between">

                                    <strong>Total:</strong>

                                    <span>
                                        Bs.
                                        {{ number_format(
        $curacion->total,
        2
    ) }}
                                    </span>

                                </h5>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection