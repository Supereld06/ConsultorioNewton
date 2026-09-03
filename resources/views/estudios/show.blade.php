@extends('layouts.app')

@section('content')


    <x-slot name="header">

        <div class="d-flex justify-content-between align-items-center">

            <h3>
                🩻 Estudio {{ $estudio->codigo }}
            </h3>

            <a href="{{ route('estudios.index') }}"
               class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>
                Volver

            </a>

        </div>

    </x-slot>


    <div class="container py-4">


        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


        {{-- DATOS --}}

        <div class="card shadow mb-4">

            <div class="card-header">
                <strong>
                    Datos de la consulta
                </strong>
            </div>

            <div class="card-body">

                @php

                    $paciente =
                        $estudio->consultation?->appointment?->patient;

                    $doctor =
                        $estudio->consultation?->appointment?->doctor;

                @endphp


                <div class="row">

                    <div class="col-md-3">
                        <strong>Código:</strong>
                        <br>
                        {{ $estudio->codigo }}
                    </div>

                    <div class="col-md-3">
                        <strong>Fecha:</strong>
                        <br>
                        {{ $estudio->fecha?->format('d/m/Y') }}
                    </div>

                    <div class="col-md-3">
                        <strong>Paciente:</strong>
                        <br>

                        @if($paciente)
                            {{ $paciente->nombres }}
                            {{ $paciente->apellidos }}
                        @else
                            No registrado
                        @endif

                    </div>

                    <div class="col-md-3">
                        <strong>Doctor:</strong>
                        <br>

                        @if($doctor)
                            Dr./Dra.
                            {{ $doctor->nombres }}
                            {{ $doctor->apellidos }}
                        @else
                            No registrado
                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- DETALLES --}}

        <div class="card shadow mb-4">

            <div class="card-header">
                <strong>
                    Estudios registrados
                </strong>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <thead>

                            <tr>
                                <th>Estudio</th>
                                <th>Tipo</th>
                                <th>Laboratorio</th>
                                <th>Costo laboratorio</th>
                                <th>Cobrado paciente</th>
                                <th>Utilidad</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($estudio->detalles as $detalle)

                                <tr>

                                    <td>
                                        {{ $detalle->nombre_estudio }}
                                    </td>

                                    <td>
                                        {{ $detalle->tipo ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $detalle->laboratorio ?? '-' }}
                                    </td>

                                    <td>
                                        Bs.
                                        {{ number_format($detalle->precio_laboratorio, 2) }}
                                    </td>

                                    <td>
                                        Bs.
                                        {{ number_format($detalle->precio_cobrado, 2) }}
                                    </td>

                                    <td>
                                        Bs.
                                        {{ number_format($detalle->utilidad, 2) }}
                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- RESUMEN --}}

        <div class="row">

            <div class="col-md-4">

                <div class="card shadow mb-3">

                    <div class="card-body">

                        <h6>
                            Total cobrado al paciente
                        </h6>

                        <h3>
                            Bs.
                            {{ number_format($estudio->total_cobrado, 2) }}
                        </h3>

                        <hr>

                        <small>
                            Pagado:
                            Bs.
                            {{ number_format($estudio->monto_pagado_paciente, 2) }}
                        </small>

                        <br>

                        <small>
                            Saldo:
                            Bs.
                            {{ number_format($estudio->saldo_paciente, 2) }}
                        </small>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card shadow mb-3">

                    <div class="card-body">

                        <h6>
                            Laboratorio
                        </h6>

                        <h3>
                            Bs.
                            {{ number_format($estudio->total_laboratorio, 2) }}
                        </h3>

                        <hr>

                        <small>
                            Pagado:
                            Bs.
                            {{ number_format($estudio->monto_pagado_laboratorio, 2) }}
                        </small>

                        <br>

                        <small>
                            Saldo:
                            Bs.
                            {{ number_format($estudio->saldo_laboratorio, 2) }}
                        </small>

                    </div>

                </div>

            </div>


            <div class="col-md-4">

                <div class="card shadow mb-3">

                    <div class="card-body">

                        <h6>
                            Utilidad Newton
                        </h6>

                        <h3 class="text-success">

                            Bs.
                            {{ number_format($estudio->utilidad, 2) }}

                        </h3>

                    </div>

                </div>

            </div>

        </div>


        {{-- RECIBOS --}}

        <div class="card shadow">

            <div class="card-body">

                <div class="d-flex gap-2 flex-wrap">

                    <a href="{{ route('estudios.pdf.paciente', $estudio->id) }}"
                       target="_blank"
                       class="btn btn-primary">

                        <i class="bi bi-receipt"></i>
                        Recibo paciente

                    </a>


                    <a href="{{ route('estudios.pdf.laboratorio', $estudio->id) }}"
                       target="_blank"
                       class="btn btn-warning">

                        <i class="bi bi-receipt-cutoff"></i>
                        Recibo laboratorio

                    </a>


                    <a href="{{ route('estudios.edit', $estudio->id) }}"
                       class="btn btn-secondary">

                        <i class="bi bi-pencil"></i>
                        Editar

                    </a>

                </div>

            </div>

        </div>

    </div>

@endsection