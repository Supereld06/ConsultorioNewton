@extends('layouts.app')

@section('content')

    <div class="container py-4">

        {{-- MENSAJES --}}

        @if(session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
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

        {{-- ENCABEZADO --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h2 class="mb-1">
                    💰 Pago de Atención Médica
                </h2>

                <p class="text-muted mb-0">
                    Registrar el costo y distribución de la atención.
                </p>
            </div>

            <a href="{{ route('consultations.index') }}" class="btn btn-secondary">
                ← Volver
            </a>

        </div>


        {{-- INFORMACIÓN DE LA CONSULTA --}}

        <div class="card shadow-sm mb-4">

            <div class="card-header bg-primary text-white">
                <strong>Información de la consulta</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="fw-bold">
                            Paciente
                        </label>

                        <div class="form-control bg-light">

                            @if($consultation->appointment?->patient)

                                {{ $consultation->appointment->patient->nombres }}
                                {{ $consultation->appointment->patient->apellidos }}

                            @else

                                Sin paciente

                            @endif

                        </div>

                    </div>


                    <div class="col-md-6 mb-3">

                        <label class="fw-bold">
                            Médico
                        </label>

                        <div class="form-control bg-light">

                            @if($consultation->appointment?->doctor)

                                Dr./Dra.
                                {{ $consultation->appointment->doctor->nombres }}
                                {{ $consultation->appointment->doctor->apellidos }}

                            @else

                                Sin médico

                            @endif

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="fw-bold">
                            Consulta
                        </label>

                        <div class="form-control bg-light">
                            #{{ str_pad($consultation->id, 6, '0', STR_PAD_LEFT) }}
                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="fw-bold">
                            Fecha
                        </label>

                        <div class="form-control bg-light">

                            {{ \Carbon\Carbon::parse($consultation->appointment?->fecha)->format('d/m/Y') }}

                        </div>

                    </div>


                    <div class="col-md-4 mb-3">

                        <label class="fw-bold">
                            Hora
                        </label>

                        <div class="form-control bg-light">

                            {{ $consultation->appointment?->hora }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- FORMULARIO --}}

        <form method="POST" action="{{ route('pagos_medicos.store') }}" id="formPagoMedico">

            @csrf

            <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">


            {{-- COSTO --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-success text-white">
                    <strong>Costo de la atención</strong>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">

                            <label for="costo_atencion" class="form-label fw-bold">

                                Costo de atención médica (Bs.)

                            </label>

                            <input type="number" step="0.01" min="0.01" class="form-control form-control-lg"
                                id="costo_atencion" name="costo_atencion" value="{{ old('costo_atencion') }}"
                                placeholder="0.00" required>

                        </div>

                    </div>

                </div>

            </div>


            {{-- DISTRIBUCIÓN --}}

            <div class="card shadow-sm mb-4">

                <div class="card-header bg-dark text-white">

                    <strong>
                        Distribución del ingreso
                    </strong>

                </div>

                <div class="card-body">

                    <div class="alert alert-secondary">

                        <strong>
                            La distribución debe sumar exactamente 100%.
                        </strong>

                    </div>


                    <div class="row">


                        {{-- MÉDICO --}}

                        <div class="col-md-4 mb-3">

                            <label for="porcentaje_medico" class="form-label fw-bold">

                                👨‍⚕️ Médico (%)

                            </label>

                            <input type="number" step="0.01" min="0" max="100" class="form-control porcentaje"
                                id="porcentaje_medico" name="porcentaje_medico" value="{{ old(
        'porcentaje_medico',
        $configuracion->porcentaje_medico
    ) }}" required>

                            <div class="input-group mt-2">

                                <span class="input-group-text">
                                    Bs.
                                </span>

                                <input type="text" class="form-control monto" id="monto_medico" value="0.00" readonly>

                            </div>

                            <small class="text-muted">
                                Se registrará en Caja Doctores.
                            </small>

                        </div>


                        {{-- EMPRESA --}}

                        <div class="col-md-4 mb-3">

                            <label for="porcentaje_institucion" class="form-label fw-bold">

                                🏢 Empresa (%)

                            </label>

                            <input type="number" step="0.01" min="0" max="100" class="form-control porcentaje"
                                id="porcentaje_institucion" name="porcentaje_institucion" value="{{ old(
        'porcentaje_institucion',
        $configuracion->porcentaje_institucion
    ) }}" required>

                            <div class="input-group mt-2">

                                <span class="input-group-text">
                                    Bs.
                                </span>

                                <input type="text" class="form-control monto" id="monto_institucion" value="0.00" readonly>

                            </div>

                            <small class="text-muted">
                                Se registrará en Caja Empresa.
                            </small>

                        </div>


                        {{-- OTROS --}}

                        <div class="col-md-4 mb-3">

                            <label for="porcentaje_otros" class="form-label fw-bold">

                                📦 Otros (%)

                            </label>

                            <input type="number" step="0.01" min="0" max="100" class="form-control porcentaje"
                                id="porcentaje_otros" name="porcentaje_otros" value="{{ old(
        'porcentaje_otros',
        $configuracion->porcentaje_otros
    ) }}" required>

                            <div class="input-group mt-2">

                                <span class="input-group-text">
                                    Bs.
                                </span>

                                <input type="text" class="form-control monto" id="monto_otros" value="0.00" readonly>

                            </div>

                            <small class="text-muted">
                                Se registrará en Caja Otros.
                            </small>

                        </div>

                    </div>


                    {{-- TOTAL --}}

                    <hr>

                    <div class="row align-items-center">

                        <div class="col-md-6">

                            <h5>
                                Total distribución
                            </h5>

                            <span id="totalPorcentaje" class="badge bg-secondary fs-6">

                                100%

                            </span>

                        </div>


                        <div class="col-md-6 text-md-end">

                            <h4 class="mb-0">

                                Total:
                                <span id="totalMonto">
                                    Bs. 0.00
                                </span>

                            </h4>

                        </div>

                    </div>


                    <div id="alertaPorcentaje" class="alert alert-danger mt-3 d-none">

                        La distribución debe sumar exactamente 100%.

                    </div>

                </div>

            </div>


            {{-- BOTONES --}}

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-end gap-2">

                        <a href="{{ route('consultations.index') }}" class="btn btn-secondary">

                            Cancelar

                        </a>

                        <button type="submit" class="btn btn-success" id="btnRegistrar">

                            💾 Registrar pago

                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>


    {{-- JAVASCRIPT --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const costoInput = document.getElementById('costo_atencion');

            const porcentajeMedico =
                document.getElementById('porcentaje_medico');

            const porcentajeInstitucion =
                document.getElementById('porcentaje_institucion');

            const porcentajeOtros =
                document.getElementById('porcentaje_otros');

            const montoMedico =
                document.getElementById('monto_medico');

            const montoInstitucion =
                document.getElementById('monto_institucion');

            const montoOtros =
                document.getElementById('monto_otros');

            const totalPorcentaje =
                document.getElementById('totalPorcentaje');

            const totalMonto =
                document.getElementById('totalMonto');

            const alerta =
                document.getElementById('alertaPorcentaje');

            const btnRegistrar =
                document.getElementById('btnRegistrar');

            function numero(valor) {

                const resultado = parseFloat(valor);

                return isNaN(resultado) ? 0 : resultado;

            }


            function calcular() {

                const costo = numero(costoInput.value);

                const medico = numero(porcentajeMedico.value);

                const institucion =
                    numero(porcentajeInstitucion.value);

                const otros =
                    numero(porcentajeOtros.value);


                const sumaPorcentajes =
                    medico + institucion + otros;


                const valorMedico =
                    costo * medico / 100;

                const valorInstitucion =
                    costo * institucion / 100;

                /*
                 * Otros recibe el restante para evitar
                 * pequeños errores de redondeo.
                 */

                const valorOtros =
                    costo - valorMedico - valorInstitucion;


                montoMedico.value =
                    valorMedico.toFixed(2);

                montoInstitucion.value =
                    valorInstitucion.toFixed(2);

                montoOtros.value =
                    valorOtros.toFixed(2);


                totalPorcentaje.textContent =
                    sumaPorcentajes.toFixed(2) + '%';


                totalMonto.textContent =
                    'Bs. ' + costo.toFixed(2);


                if (Math.abs(sumaPorcentajes - 100) < 0.001) {

                    totalPorcentaje.classList
                        .remove('bg-danger');

                    totalPorcentaje.classList
                        .add('bg-success');

                    alerta.classList.add('d-none');

                    btnRegistrar.disabled = false;

                } else {

                    totalPorcentaje.classList
                        .remove('bg-success');

                    totalPorcentaje.classList
                        .add('bg-danger');

                    alerta.classList.remove('d-none');

                    btnRegistrar.disabled = true;

                }

            }


            costoInput.addEventListener(
                'input',
                calcular
            );

            porcentajeMedico.addEventListener(
                'input',
                calcular
            );

            porcentajeInstitucion.addEventListener(
                'input',
                calcular
            );

            porcentajeOtros.addEventListener(
                'input',
                calcular
            );


            calcular();

        });

    </script>

@endsection