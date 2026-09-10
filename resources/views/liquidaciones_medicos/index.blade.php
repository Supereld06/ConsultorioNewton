@extends('layouts.app')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">👨‍⚕️ Liquidaciones médicas</h2>
                <p class="text-muted mb-0">
                    Consulta y genera las liquidaciones de los médicos.
                </p>
            </div>

            <a href="{{ route('liquidaciones_medicos.index') }}" class="btn btn-secondary">
                🔄 Nueva consulta
            </a>
        </div>

        {{-- MENSAJES --}}

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Se encontraron errores:</strong>

                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORMULARIO DE CONSULTA --}}

        <div class="card shadow-sm">

            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    🔎 Consultar ingresos del médico
                </h5>
            </div>

            <div class="card-body">

                <form action="{{ route('liquidaciones_medicos.consultar') }}" method="POST">

                    @csrf

                    <div class="row">

                        {{-- MÉDICO --}}

                        <div class="col-md-4 mb-3">

                            <label for="doctor_id" class="form-label">
                                Médico
                            </label>

                            <select name="doctor_id" id="doctor_id" class="form-select" required>

                                <option value="">
                                    -- Seleccione un médico --
                                </option>

                                @foreach($doctores as $doctor)

                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->apellidos }}
                                        {{ $doctor->nombres }}
                                        - {{ $doctor->especialidad }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- FECHA DESDE --}}

                        <div class="col-md-3 mb-3">

                            <label for="fecha_desde" class="form-label">
                                Fecha desde
                            </label>

                            <input type="date" name="fecha_desde" id="fecha_desde" class="form-control"
                                value="{{ old('fecha_desde') }}" required>

                        </div>

                        {{-- FECHA HASTA --}}

                        <div class="col-md-3 mb-3">

                            <label for="fecha_hasta" class="form-label">
                                Fecha hasta
                            </label>

                            <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control"
                                value="{{ old('fecha_hasta') }}" required>

                        </div>

                        {{-- BOTÓN --}}

                        <div class="col-md-2 mb-3 d-flex align-items-end">

                            <button type="submit" class="btn btn-primary w-100">
                                🔎 CONSULTAR
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <div class="card shadow-sm mt-4">

        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">
                📋 Historial de liquidaciones médicas
            </h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-bordered mb-0">

                    <thead class="table-dark">

                        <tr>
                            <th>N° Liquidación</th>
                            <th>Médico</th>
                            <th>Fecha</th>
                            <th>Total generado</th>
                            <th>Total pagado</th>
                            <th>Saldo</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($liquidaciones as $liquidacion)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $liquidacion->numero }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $liquidacion->doctor->apellidos }}
                                    {{ $liquidacion->doctor->nombres }}
                                </td>

                                <td>
                                    {{ $liquidacion->fecha->format('d/m/Y') }}
                                </td>

                                <td>
                                    Bs.
                                    {{ number_format($liquidacion->total_generado, 2) }}
                                </td>

                                <td>
                                    Bs.
                                    {{ number_format($liquidacion->total_pagado, 2) }}
                                </td>

                                <td>
                                    Bs.
                                    {{ number_format($liquidacion->saldo, 2) }}
                                </td>

                                <td>

                                    @if($liquidacion->estado === 'pagada')

                                        <span class="badge bg-success">
                                            PAGADA
                                        </span>

                                    @elseif($liquidacion->estado === 'pendiente')

                                        <span class="badge bg-warning text-dark">
                                            PENDIENTE
                                        </span>

                                    @elseif($liquidacion->estado === 'anulada')

                                        <span class="badge bg-danger">
                                            ANULADA
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    {{-- VER DETALLE --}}
                                    <a href="{{ route('liquidaciones_medicos.show', $liquidacion->id) }}"
                                        class="btn btn-primary btn-sm">

                                        🔍 Ver

                                    </a>


                                    {{-- PDF --}}
                                    <a href="{{ route('liquidaciones_medicos.pdf', $liquidacion->id) }}" target="_blank"
                                        class="btn btn-danger btn-sm">

                                        📄 PDF

                                    </a>


                                    {{-- PAGAR --}}
                                    @if($liquidacion->estado === 'pendiente')

                                        <form action="{{ route('liquidaciones_medicos.pagar', $liquidacion->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('¿Está seguro de pagar esta liquidación por Bs. {{ number_format($liquidacion->saldo, 2) }}?');">

                                            @csrf

                                            <button type="submit" class="btn btn-success btn-sm">

                                                💵 Pagar

                                            </button>

                                        </form>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">

                                        <h5>📭 No hay liquidaciones registradas</h5>

                                        <p class="mb-0">
                                            Las liquidaciones que generes aparecerán aquí.
                                        </p>

                                    </div>
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection