@extends('layouts.app')

@section('content')

<div class="container-fluid py-3">


{{-- ENCABEZADO --}}
<div class="d-flex justify-content-between align-items-center mb-3">

    <div>

        <h3 class="mb-0">
            🩻 Editar Estudio Complementario
        </h3>

        <small class="text-muted">
            {{ $estudio->codigo }}
        </small>

    </div>

    <a href="{{ route('estudios.show', $estudio->id) }}"
       class="btn btn-secondary">

        <i class="bi bi-arrow-left"></i>
        Volver

    </a>

</div>


{{-- ERRORES --}}
@if($errors->any())

    <div class="alert alert-danger">

        <strong>
            Por favor corrige los siguientes errores:
        </strong>

        <ul class="mb-0 mt-2">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


<form
    action="{{ route('estudios.update', $estudio->id) }}"
    method="POST">

    @csrf
    @method('PUT')


    {{-- CONSULTA --}}
    <div class="card shadow mb-4">

        <div class="card-header">

            <strong>

                <i class="bi bi-person-vcard"></i>

                Datos de la consulta

            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- CONSULTA --}}
                <div class="col-md-8">

                    <label class="form-label">
                        Consulta
                    </label>

                    <select
                        name="consultation_id"
                        id="consultation_id"
                        class="form-select"
                        required>

                        <option value="">
                            -- Seleccionar consulta --
                        </option>

                        @foreach($consultations as $consulta)

                            @php

                                $appointment =
                                    $consulta->appointment;

                                $paciente =
                                    $appointment?->patient;

                                $doctor =
                                    $appointment?->doctor;

                            @endphp

                            <option
                                value="{{ $consulta->id }}"
                                data-paciente="{{ $paciente?->nombres }} {{ $paciente?->apellidos }}"
                                data-doctor="{{ $doctor?->nombres }} {{ $doctor?->apellidos }}"
                                {{ $estudio->consultation_id == $consulta->id ? 'selected' : '' }}>

                                Consulta #{{ $consulta->id }}

                                @if($paciente)

                                    -
                                    {{ $paciente->nombres }}
                                    {{ $paciente->apellidos }}

                                @endif

                                @if($doctor)

                                    -
                                    Dr./Dra.
                                    {{ $doctor->nombres }}
                                    {{ $doctor->apellidos }}

                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- FECHA --}}
                <div class="col-md-4">

                    <label class="form-label">
                        Fecha
                    </label>

                    <input
                        type="date"
                        name="fecha"
                        class="form-control"
                        value="{{ old('fecha', $estudio->fecha?->format('Y-m-d')) }}"
                        required>

                </div>

            </div>


            <div class="row mt-3">

                {{-- PACIENTE --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Paciente
                    </label>

                    <input
                        type="text"
                        id="paciente_nombre"
                        class="form-control"
                        value="{{ $estudio->consultation?->appointment?->patient?->nombres }}
                               {{ $estudio->consultation?->appointment?->patient?->apellidos }}"
                        readonly>

                </div>


                {{-- DOCTOR --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Doctor
                    </label>

                    <input
                        type="text"
                        id="doctor_nombre"
                        class="form-control"
                        value="Dr./Dra. {{ $estudio->consultation?->appointment?->doctor?->nombres }}
                               {{ $estudio->consultation?->appointment?->doctor?->apellidos }}"
                        readonly>

                </div>

            </div>

        </div>

    </div>


    {{-- ESTUDIOS --}}
    <div class="card shadow mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <strong>

                <i class="bi bi-clipboard2-pulse"></i>

                Estudios solicitados

            </strong>


            <button
                type="button"
                id="agregarEstudio"
                class="btn btn-success btn-sm">

                <i class="bi bi-plus-circle"></i>

                Agregar estudio

            </button>

        </div>


        <div class="card-body">

            <div id="contenedorEstudios">

                @foreach($estudio->detalles as $detalle)

                    <div class="estudio-item border rounded p-3 mb-3">

                        <div class="row">

                            {{-- NOMBRE --}}
                            <div class="col-md-4">

                                <label class="form-label">
                                    Nombre del estudio
                                </label>

                                <input
                                    type="text"
                                    name="nombre_estudio[]"
                                    class="form-control"
                                    value="{{ old('nombre_estudio.' . $loop->index, $detalle->nombre_estudio) }}"
                                    required>

                            </div>


                            {{-- TIPO --}}
                            <div class="col-md-2">

                                <label class="form-label">
                                    Tipo
                                </label>

                                <input
                                    type="text"
                                    name="tipo[]"
                                    class="form-control"
                                    value="{{ old('tipo.' . $loop->index, $detalle->tipo) }}">

                            </div>


                            {{-- LABORATORIO --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    Laboratorio externo
                                </label>

                                <input
                                    type="text"
                                    name="laboratorio[]"
                                    class="form-control"
                                    value="{{ old('laboratorio.' . $loop->index, $detalle->laboratorio) }}">

                            </div>


                            {{-- COSTO LABORATORIO --}}
                            <div class="col-md-1">

                                <label class="form-label">
                                    Costo Lab.
                                </label>

                                <input
                                    type="number"
                                    name="precio_laboratorio[]"
                                    class="form-control precio-laboratorio"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('precio_laboratorio.' . $loop->index, $detalle->precio_laboratorio) }}"
                                    required>

                            </div>


                            {{-- COBRADO --}}
                            <div class="col-md-2">

                                <label class="form-label">
                                    Cobrado
                                </label>

                                <input
                                    type="number"
                                    name="precio_cobrado[]"
                                    class="form-control precio-cobrado"
                                    step="0.01"
                                    min="0"
                                    value="{{ old('precio_cobrado.' . $loop->index, $detalle->precio_cobrado) }}"
                                    required>

                            </div>

                        </div>


                        <div class="row mt-3">

                            {{-- UTILIDAD --}}
                            <div class="col-md-3">

                                <label class="form-label">
                                    Utilidad
                                </label>

                                <input
                                    type="text"
                                    class="form-control utilidad-detalle"
                                    value="Bs. {{ number_format($detalle->utilidad, 2) }}"
                                    readonly>

                            </div>


                            {{-- ELIMINAR --}}
                            <div class="col-md-9 text-end">

                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm eliminarEstudio">

                                    <i class="bi bi-trash"></i>

                                    Eliminar

                                </button>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    </div>


    {{-- RESUMEN --}}
    <div class="card shadow mb-4">

        <div class="card-header">

            <strong>

                <i class="bi bi-calculator"></i>

                Resumen económico

            </strong>

        </div>


        <div class="card-body">

            <div class="row">

                {{-- COBRADO --}}
                <div class="col-md-4">

                    <div class="alert alert-primary mb-0">

                        <strong>
                            Total cobrado al paciente
                        </strong>

                        <h4 id="totalCobrado">
                            Bs. 0.00
                        </h4>

                    </div>

                </div>


                {{-- LABORATORIO --}}
                <div class="col-md-4">

                    <div class="alert alert-warning mb-0">

                        <strong>
                            Total laboratorio
                        </strong>

                        <h4 id="totalLaboratorio">
                            Bs. 0.00
                        </h4>

                    </div>

                </div>


                {{-- UTILIDAD --}}
                <div class="col-md-4">

                    <div class="alert alert-success mb-0">

                        <strong>
                            Utilidad
                        </strong>

                        <h4 id="totalUtilidad">
                            Bs. 0.00
                        </h4>

                    </div>

                </div>

            </div>


            <hr>


            <div class="row">

                {{-- PAGO PACIENTE --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Monto pagado por el paciente
                    </label>

                    <input
                        type="number"
                        name="monto_pagado_paciente"
                        id="montoPagadoPaciente"
                        class="form-control"
                        step="0.01"
                        min="0"
                        value="{{ old('monto_pagado_paciente', $estudio->monto_pagado_paciente) }}"
                        required>

                </div>


                {{-- PAGO LABORATORIO --}}
                <div class="col-md-6">

                    <label class="form-label">
                        Monto pagado al laboratorio
                    </label>

                    <input
                        type="number"
                        name="monto_pagado_laboratorio"
                        id="montoPagadoLaboratorio"
                        class="form-control"
                        step="0.01"
                        min="0"
                        value="{{ old('monto_pagado_laboratorio', $estudio->monto_pagado_laboratorio) }}"
                        required>

                </div>

            </div>


            {{-- OBSERVACIONES --}}
            <div class="row mt-3">

                <div class="col-md-6">

                    <label class="form-label">
                        Observaciones
                    </label>

                    <textarea
                        name="observaciones"
                        class="form-control"
                        rows="3">{{ old('observaciones', $estudio->observaciones) }}</textarea>

                </div>

            </div>

        </div>

    </div>


    {{-- BOTONES --}}
    <div class="d-flex justify-content-end gap-2">

        <a
            href="{{ route('estudios.show', $estudio->id) }}"
            class="btn btn-secondary">

            Cancelar

        </a>


        <button
            type="submit"
            class="btn btn-primary">

            <i class="bi bi-save"></i>

            Guardar cambios

        </button>

    </div>

</form>


</div>

<script>

/*
|--------------------------------------------------------------------------
| MOSTRAR PACIENTE Y DOCTOR
|--------------------------------------------------------------------------
*/

document
    .getElementById('consultation_id')
    .addEventListener('change', function () {

        const option =
            this.options[this.selectedIndex];

        document
            .getElementById('paciente_nombre')
            .value =
            option.dataset.paciente || '';

        document
            .getElementById('doctor_nombre')
            .value =
            option.dataset.doctor
                ? 'Dr./Dra. ' + option.dataset.doctor
                : '';

    });


/*
|--------------------------------------------------------------------------
| AGREGAR ESTUDIO
|--------------------------------------------------------------------------
*/

document
    .getElementById('agregarEstudio')
    .addEventListener('click', function () {

        const contenedor =
            document.getElementById('contenedorEstudios');

        const original =
            document.querySelector('.estudio-item');

        const nuevo =
            original.cloneNode(true);


        nuevo
            .querySelectorAll('input')
            .forEach(input => {

                if (
                    input.name === 'precio_laboratorio[]' ||
                    input.name === 'precio_cobrado[]'
                ) {

                    input.value = '0';

                } else if (
                    input.classList.contains('utilidad-detalle')
                ) {

                    input.value = 'Bs. 0.00';

                } else {

                    input.value = '';

                }

            });


        contenedor.appendChild(nuevo);

        actualizarTotales();

    });


/*
|--------------------------------------------------------------------------
| ELIMINAR ESTUDIO
|--------------------------------------------------------------------------
*/

document.addEventListener('click', function (e) {

    if (
        e.target.closest('.eliminarEstudio')
    ) {

        const estudios =
            document.querySelectorAll('.estudio-item');


        if (estudios.length <= 1) {

            alert(
                'Debe existir al menos un estudio.'
            );

            return;

        }


        e.target
            .closest('.estudio-item')
            .remove();


        actualizarTotales();

    }

});


/*
|--------------------------------------------------------------------------
| CAMBIAR PRECIOS
|--------------------------------------------------------------------------
*/

document.addEventListener('input', function (e) {

    if (
        e.target.classList.contains('precio-laboratorio') ||
        e.target.classList.contains('precio-cobrado')
    ) {

        actualizarTotales();

    }

});


/*
|--------------------------------------------------------------------------
| CALCULAR TOTALES
|--------------------------------------------------------------------------
*/

function actualizarTotales()
{

    let totalCobrado = 0;

    let totalLaboratorio = 0;


    document
        .querySelectorAll('.estudio-item')
        .forEach(item => {

            const laboratorio =
                parseFloat(
                    item
                        .querySelector('.precio-laboratorio')
                        .value
                ) || 0;


            const cobrado =
                parseFloat(
                    item
                        .querySelector('.precio-cobrado')
                        .value
                ) || 0;


            const utilidad =
                cobrado - laboratorio;


            totalLaboratorio +=
                laboratorio;

            totalCobrado +=
                cobrado;


            item
                .querySelector('.utilidad-detalle')
                .value =
                'Bs. ' +
                utilidad.toFixed(2);

        });


    const utilidad =
        totalCobrado -
        totalLaboratorio;


    document
        .getElementById('totalCobrado')
        .innerText =
        'Bs. ' +
        totalCobrado.toFixed(2);


    document
        .getElementById('totalLaboratorio')
        .innerText =
        'Bs. ' +
        totalLaboratorio.toFixed(2);


    document
        .getElementById('totalUtilidad')
        .innerText =
        'Bs. ' +
        utilidad.toFixed(2);

}


actualizarTotales();

</script>

@endsection
