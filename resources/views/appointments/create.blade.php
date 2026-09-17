@extends('layouts.app')

@section('content')

<div class="container-fluid px-3">

    
    <div class="card shadow-sm">

        {{-- ENCABEZADO --}}
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0">📅 Nueva Cita</h5>
        </div>

        <div class="card-body py-3">

            <form action="{{ route('appointments.store') }}" method="POST" id="formCita">
                @csrf

                {{-- =====================================================
                 PACIENTE / DOCTOR
            ====================================================== --}}
                <div class="row g-2 mb-2">

                    {{-- PACIENTE --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold mb-1">
                            PACIENTE
                        </label>

                        <select
                            name="patient_id"
                            class="form-control form-control-sm"
                            required>
                            <option value="">Seleccione</option>

                            @foreach($patients as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->nombres }} {{ $p->apellidos }}
                            </option>
                            @endforeach

                        </select>
                    </div>


                    {{-- DOCTOR --}}
                    <div class="col-md-6">
                        <label class="form-label fw-bold mb-1">
                            DOCTOR
                        </label>

                        <select
                            name="doctor_id"
                            id="doctor"
                            class="form-control form-control-sm"
                            required>
                            <option value="">Seleccione</option>

                            @foreach($doctors as $d)
                            <option value="{{ $d->id }}">
                                {{ $d->nombres }} {{ $d->apellidos }}
                            </option>
                            @endforeach

                        </select>
                    </div>

                </div>


                {{-- =====================================================
                 CALENDARIO / HORARIOS
            ====================================================== --}}
                <div class="row g-3 mb-2">

                    {{-- CALENDARIO --}}
                    <div class="col-lg-5">

                        <label class="form-label fw-bold mb-1">
                            📅 FECHA
                        </label>

                        <div class="calendar-container">

                            {{-- CABECERA --}}
                            <div class="calendar-header">

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary btn-sm calendar-nav"
                                    id="mesAnterior">
                                    ‹
                                </button>

                                <span
                                    id="mesActual"
                                    class="calendar-title"></span>

                                <button
                                    type="button"
                                    class="btn btn-outline-secondary btn-sm calendar-nav"
                                    id="mesSiguiente">
                                    ›
                                </button>

                            </div>


                            {{-- DÍAS --}}
                            <div class="calendar-weekdays">

                                <div>LUN</div>
                                <div>MAR</div>
                                <div>MIÉ</div>
                                <div>JUE</div>
                                <div>VIE</div>
                                <div>SÁB</div>
                                <div>DOM</div>

                            </div>


                            <div
                                id="calendario"
                                class="calendar-days"></div>

                        </div>


                        <input
                            type="hidden"
                            name="fecha"
                            id="fecha"
                            required>


                        <div
                            id="fechaSeleccionadaTexto"
                            class="selected-date">
                            Seleccione una fecha.
                        </div>

                    </div>


                    {{-- HORARIOS --}}
                    <div class="col-lg-7">

                        <label class="form-label fw-bold mb-1">
                            🕐 HORAS DISPONIBLES
                        </label>

                        <div
                            id="horas-container"
                            class="horas-container">
                            <span class="text-muted small">
                                Seleccione doctor y fecha.
                            </span>
                        </div>

                        <input
                            type="hidden"
                            name="hora"
                            id="horaSeleccionada"
                            required>

                    </div>

                </div>


                {{-- =====================================================
                 OBSERVACIONES
            ====================================================== --}}
                <div class="row mb-2">

                    <div class="col-12">

                        <label class="form-label fw-bold mb-1">
                            OBSERVACIONES
                        </label>

                        <textarea
                            name="observaciones"
                            class="form-control form-control-sm"
                            rows="2"
                            oninput="this.value = this.value.toUpperCase();"></textarea>

                    </div>

                </div>


                {{-- =====================================================
                 BOTONES
            ====================================================== --}}
                <div class="d-flex gap-2 mt-2">

                    <button
                        type="submit"
                        class="btn btn-success btn-sm px-3">
                        💾 Guardar
                    </button>

                    <a
                        href="{{ route('appointments.index') }}"
                        class="btn btn-secondary btn-sm px-3">
                        Cancelar
                    </a>

                </div>

            </form>

        </div>

    </div>


</div>

<style>
    /* =========================================================
   CONTENEDOR CALENDARIO
========================================================= */

    .calendar-container {

        border: 1px solid #dee2e6;

        border-radius: 7px;

        padding: 7px 9px;

        background: #fff;

        width: 100%;

    }


    /* =========================================================
   CABECERA CALENDARIO
========================================================= */

    .calendar-header {

        display: flex;

        align-items: center;

        justify-content: space-between;

        margin-bottom: 5px;

    }


    .calendar-title {

        font-size: 15px;

        font-weight: 600;

        text-transform: capitalize;

    }


    .calendar-nav {

        width: 30px;

        height: 28px;

        padding: 0;

        line-height: 1;

    }


    /* =========================================================
   DÍAS SEMANA
========================================================= */

    .calendar-weekdays {

        display: grid;

        grid-template-columns:
            repeat(7, 1fr);

        gap: 3px;

        margin-bottom: 3px;

    }


    .calendar-weekdays div {

        text-align: center;

        font-size: 10px;

        font-weight: bold;

        color: #6c757d;

        padding: 2px 0;

    }


    /* =========================================================
   DÍAS
========================================================= */

    .calendar-days {

        display: grid;

        grid-template-columns:
            repeat(7, 1fr);

        gap: 3px;

    }


    .calendar-day {

        height: 32px;

        min-width: 0;

        border: 1px solid #e9ecef;

        border-radius: 5px;

        background: #fff;

        cursor: pointer;

        display: flex;

        align-items: center;

        justify-content: center;

        font-size: 12px;

        padding: 0;

        transition: 0.12s;

    }


    .calendar-day:hover:not(.disabled) {

        background: #e9ecef;

    }


    /* =========================================================
   HOY
========================================================= */

    .calendar-day.today {

        border: 2px solid #0d6efd;

        font-weight: bold;

    }


    /* =========================================================
   SELECCIONADO
========================================================= */

    .calendar-day.selected {

        background: #0d6efd;

        color: white;

        border-color: #0d6efd;

        font-weight: bold;

    }


    /* =========================================================
   DÍAS ANTERIORES
========================================================= */

    .calendar-day.disabled {

        background: #f8f9fa;

        color: #adb5bd;

        cursor: not-allowed;

    }


    /* =========================================================
   ESPACIOS VACÍOS
========================================================= */

    .calendar-empty {

        height: 32px;

    }


    /* =========================================================
   FECHA SELECCIONADA
========================================================= */

    .selected-date {

        font-size: 12px;

        color: #495057;

        margin-top: 4px;

        min-height: 18px;

    }


    /* =========================================================
   HORARIOS
========================================================= */

    .horas-container {

        display: flex;

        flex-wrap: wrap;

        align-content: flex-start;

        gap: 6px;

        min-height: 165px;

        max-height: 165px;

        overflow-y: auto;

        padding: 8px;

        border: 1px solid #dee2e6;

        border-radius: 7px;

        background: #f8f9fa;

    }


    .hora-btn {

        min-width: 65px;

        height: 31px;

        font-size: 12px;

        padding: 3px 8px;

    }


    /* =========================================================
   FORMULARIO
========================================================= */

    .form-label {

        font-size: 12px;

    }


    .form-control-sm {

        font-size: 13px;

    }


    /* =========================================================
   PANTALLAS PEQUEÑAS
========================================================= */

    @media (max-width: 991px) {

        .calendar-day {

            height: 34px;

        }

        .calendar-empty {

            height: 34px;

        }

        .horas-container {

            min-height: 120px;

            max-height: 120px;

        }

    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {


        /* =====================================================
           ELEMENTOS
        ====================================================== */

        const doctor =
            document.getElementById('doctor');

        const fecha =
            document.getElementById('fecha');

        const horaSeleccionada =
            document.getElementById('horaSeleccionada');

        const calendario =
            document.getElementById('calendario');

        const mesActual =
            document.getElementById('mesActual');

        const fechaSeleccionadaTexto =
            document.getElementById(
                'fechaSeleccionadaTexto'
            );

        const horasContainer =
            document.getElementById(
                'horas-container'
            );

        const mesAnterior =
            document.getElementById(
                'mesAnterior'
            );

        const mesSiguiente =
            document.getElementById(
                'mesSiguiente'
            );


        /* =====================================================
           FECHA ACTUAL
        ====================================================== */

        let hoy = new Date();

        hoy.setHours(
            0,
            0,
            0,
            0
        );


        /* =====================================================
           MES MOSTRADO
        ====================================================== */

        let mesMostrado = new Date(
            hoy.getFullYear(),
            hoy.getMonth(),
            1
        );


        /* =====================================================
           MESES
        ====================================================== */

        const nombresMeses = [

            'enero',
            'febrero',
            'marzo',
            'abril',
            'mayo',
            'junio',
            'julio',
            'agosto',
            'septiembre',
            'octubre',
            'noviembre',
            'diciembre'

        ];


        /* =====================================================
           DIBUJAR CALENDARIO
        ====================================================== */

        function dibujarCalendario() {

            calendario.innerHTML = '';


            const año =
                mesMostrado.getFullYear();

            const mes =
                mesMostrado.getMonth();


            mesActual.innerText =
                nombresMeses[mes] +
                ' ' +
                año;


            /*
            | Primer día
            */

            let primerDia =
                new Date(
                    año,
                    mes,
                    1
                );


            let diaSemana =
                primerDia.getDay();


            /*
            | Lunes = 0
            */

            if (diaSemana === 0) {

                diaSemana = 6;

            } else {

                diaSemana--;

            }


            /*
            | Último día
            */

            const ultimoDia =
                new Date(
                    año,
                    mes + 1,
                    0
                ).getDate();


            /*
            | Espacios
            */

            for (
                let i = 0; i < diaSemana; i++
            ) {

                const espacio =
                    document.createElement(
                        'div'
                    );

                espacio.classList.add(
                    'calendar-empty'
                );

                calendario.appendChild(
                    espacio
                );

            }


            /*
            | DÍAS
            */

            for (
                let dia = 1; dia <= ultimoDia; dia++
            ) {

                const boton =
                    document.createElement(
                        'button'
                    );


                boton.type = 'button';

                boton.classList.add(
                    'calendar-day'
                );

                boton.innerText = dia;


                const fechaDia =
                    new Date(
                        año,
                        mes,
                        dia
                    );


                fechaDia.setHours(
                    0,
                    0,
                    0,
                    0
                );


                /*
                | FECHAS PASADAS
                */

                if (fechaDia < hoy) {

                    boton.classList.add(
                        'disabled'
                    );

                    boton.disabled = true;

                }


                /*
                | HOY
                */

                if (
                    fechaDia.getTime() ===
                    hoy.getTime()
                ) {

                    boton.classList.add(
                        'today'
                    );

                }


                /*
                | SELECCIONADA
                */

                if (
                    fecha.value &&
                    fecha.value ===
                    formatearFecha(
                        fechaDia
                    )
                ) {

                    boton.classList.add(
                        'selected'
                    );

                }


                /*
                | CLICK
                */

                boton.addEventListener(
                    'click',
                    function() {

                        seleccionarFecha(
                            fechaDia
                        );

                    }
                );


                calendario.appendChild(
                    boton
                );

            }

        }


        /* =====================================================
           FORMATEAR FECHA
        ====================================================== */

        function formatearFecha(
            fechaObj
        ) {

            const año =
                fechaObj.getFullYear();


            const mes =
                String(
                    fechaObj.getMonth() + 1
                ).padStart(
                    2,
                    '0'
                );


            const dia =
                String(
                    fechaObj.getDate()
                ).padStart(
                    2,
                    '0'
                );


            return (
                año +
                '-' +
                mes +
                '-' +
                dia
            );

        }


        /* =====================================================
           FECHA BONITA
        ====================================================== */

        function fechaBonita(
            fechaObj
        ) {

            return fechaObj.toLocaleDateString(
                'es-BO', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                }
            );

        }


        /* =====================================================
           SELECCIONAR FECHA
        ====================================================== */

        function seleccionarFecha(
            fechaObj
        ) {

            fechaObj.setHours(
                0,
                0,
                0,
                0
            );


            fecha.value =
                formatearFecha(
                    fechaObj
                );


            fechaSeleccionadaTexto.innerHTML =
                '📅 <strong>' +
                fechaBonita(
                    fechaObj
                ) +
                '</strong>';


            /*
            | Limpiar hora anterior
            */

            horaSeleccionada.value = '';


            /*
            | Redibujar
            */

            dibujarCalendario();


            /*
            | Cargar horarios
            */

            cargarHoras();

        }


        /* =====================================================
           MES ANTERIOR
        ====================================================== */

        mesAnterior.addEventListener(
            'click',
            function() {

                const anterior =
                    new Date(
                        mesMostrado.getFullYear(),
                        mesMostrado.getMonth() - 1,
                        1
                    );


                const mesActualFecha =
                    new Date(
                        hoy.getFullYear(),
                        hoy.getMonth(),
                        1
                    );


                if (
                    anterior <
                    mesActualFecha
                ) {

                    return;

                }


                mesMostrado =
                    anterior;


                dibujarCalendario();

            }
        );


        /* =====================================================
           MES SIGUIENTE
        ====================================================== */

        mesSiguiente.addEventListener(
            'click',
            function() {

                mesMostrado =
                    new Date(
                        mesMostrado.getFullYear(),
                        mesMostrado.getMonth() + 1,
                        1
                    );


                dibujarCalendario();

            }
        );


        /* =====================================================
           CAMBIO DE DOCTOR
        ====================================================== */

        doctor.addEventListener(
            'change',
            function() {

                horaSeleccionada.value = '';

                cargarHoras();

            }
        );


        /* =====================================================
           CARGAR HORAS
        ====================================================== */

        function cargarHoras() {

            const doctorId =
                doctor.value;

            const fechaSeleccionada =
                fecha.value;


            horasContainer.innerHTML = '';


            /*
            | FALTA DOCTOR
            */

            if (!doctorId) {

                horasContainer.innerHTML =
                    '<span class="text-muted small">' +
                    'Seleccione un doctor.' +
                    '</span>';

                return;

            }


            /*
            | FALTA FECHA
            */

            if (!fechaSeleccionada) {

                horasContainer.innerHTML =
                    '<span class="text-muted small">' +
                    'Seleccione una fecha.' +
                    '</span>';

                return;

            }


            /*
            | CARGANDO
            */

            horasContainer.innerHTML =
                '<span class="text-muted small">' +
                '⏳ Cargando horarios...' +
                '</span>';


            /*
            | CONSULTA
            */

            fetch(
                    `/horarios-disponibles?doctor_id=${doctorId}&fecha=${fechaSeleccionada}`
                )

                .then(response => {

                    if (!response.ok) {

                        throw new Error(
                            'Error al consultar horarios'
                        );

                    }

                    return response.json();

                })

                .then(data => {

                    horasContainer.innerHTML = '';


                    /*
                    | SIN HORARIOS
                    */

                    if (
                        !data ||
                        data.length === 0
                    ) {

                        horasContainer.innerHTML =
                            '<span class="text-muted small">' +
                            'No hay horarios para esta fecha.' +
                            '</span>';

                        return;

                    }


                    /*
                    | CREAR BOTONES
                    */

                    data.forEach(
                        item => {

                            const boton =
                                document.createElement(
                                    'button'
                                );


                            boton.type = 'button';


                            boton.innerText =
                                item.hora;


                            boton.classList.add(
                                'btn',
                                'btn-sm',
                                'hora-btn'
                            );


                            /*
                            | OCUPADO
                            */

                            if (
                                item.ocupado
                            ) {

                                boton.classList.add(
                                    'btn-danger'
                                );

                                boton.disabled =
                                    true;

                                boton.title =
                                    'Horario ocupado';

                            }


                            /*
                            | DISPONIBLE
                            */
                            else {

                                boton.classList.add(
                                    'btn-success'
                                );


                                boton.addEventListener(
                                    'click',
                                    function() {

                                        seleccionarHora(
                                            item.hora,
                                            boton
                                        );

                                    }
                                );

                            }


                            horasContainer.appendChild(
                                boton
                            );

                        }
                    );

                })

                .catch(error => {

                    console.error(error);

                    horasContainer.innerHTML =
                        '<span class="text-danger small">' +
                        '❌ No se pudieron cargar los horarios.' +
                        '</span>';

                });

        }


        /* =====================================================
           SELECCIONAR HORA
        ====================================================== */

        function seleccionarHora(
            hora,
            boton
        ) {

            horaSeleccionada.value =
                hora;


            /*
            | Restaurar botones
            */

            document
                .querySelectorAll(
                    '#horas-container button'
                )
                .forEach(
                    function(btn) {

                        if (!btn.disabled) {

                            btn.classList.remove(
                                'btn-primary'
                            );

                            btn.classList.add(
                                'btn-success'
                            );

                        }

                    }
                );


            /*
            | Seleccionada
            */

            boton.classList.remove(
                'btn-success'
            );

            boton.classList.add(
                'btn-primary'
            );

        }


        /* =====================================================
           VALIDAR FORMULARIO
        ====================================================== */

        document
            .getElementById('formCita')
            .addEventListener(
                'submit',
                function(event) {

                    if (!fecha.value) {

                        event.preventDefault();

                        alert(
                            'Por favor seleccione una fecha.'
                        );

                        return;

                    }


                    if (
                        !horaSeleccionada.value
                    ) {

                        event.preventDefault();

                        alert(
                            'Por favor seleccione una hora.'
                        );

                        return;

                    }

                }
            );


        /* =====================================================
           INICIAR
        ====================================================== */

        dibujarCalendario();

    });
</script>

@endsection