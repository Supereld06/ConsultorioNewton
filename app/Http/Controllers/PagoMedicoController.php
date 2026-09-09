<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Consultation;
use App\Models\ConfiguracionDistribucionMedica;
use App\Models\MovimientoCaja;
use App\Models\PagoMedico;
use App\Models\PagoMedicoDistribucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagoMedicoController extends Controller
{
    /**
     * Mostrar formulario de registro del pago médico.
     */
    public function create($consultation_id)
    {
        $consultation = Consultation::with([
            'appointment.patient',
            'appointment.doctor',
            'pagoMedico'
        ])->findOrFail($consultation_id);

        // Verificar que la consulta todavía no tenga un pago registrado
        if ($consultation->pagoMedico) {
            return redirect()
                ->route('pagos_medicos.show', $consultation->pagoMedico->id)
                ->with('info', 'Esta consulta ya tiene un pago médico registrado.');
        }

        // Obtener la última configuración
        $configuracion = ConfiguracionDistribucionMedica::latest('id')->first();

        // Si no existe configuración, utilizar 70/20/10
        if (!$configuracion) {
            $configuracion = new ConfiguracionDistribucionMedica([
                'porcentaje_medico' => 70,
                'porcentaje_institucion' => 20,
                'porcentaje_otros' => 10,
            ]);
        }

        return view('pagos_medicos.create', compact(
            'consultation',
            'configuracion'
        ));
    }

    /**
     * Registrar el pago médico.
     */
    public function store(Request $request)
    {
        $request->validate([
            'consultation_id' => [
                'required',
                'integer',
                'exists:consultations,id'
            ],

            'costo_atencion' => [
                'required',
                'numeric',
                'min:0.01'
            ],

            'porcentaje_medico' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],

            'porcentaje_institucion' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],

            'porcentaje_otros' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],
        ], [
            'costo_atencion.required' => 'Debe ingresar el costo de la atención.',
            'costo_atencion.numeric' => 'El costo debe ser un número válido.',
            'costo_atencion.min' => 'El costo debe ser mayor a cero.',

            'porcentaje_medico.required' => 'Debe indicar el porcentaje del médico.',
            'porcentaje_institucion.required' => 'Debe indicar el porcentaje de la empresa.',
            'porcentaje_otros.required' => 'Debe indicar el porcentaje de otros.',
        ]);

        // Convertimos a números
        $costo = round((float) $request->costo_atencion, 2);

        $porcentajeMedico = round((float) $request->porcentaje_medico, 2);
        $porcentajeInstitucion = round((float) $request->porcentaje_institucion, 2);
        $porcentajeOtros = round((float) $request->porcentaje_otros, 2);

        // Verificar que la suma sea exactamente 100
        $sumaPorcentajes = round(
            $porcentajeMedico +
            $porcentajeInstitucion +
            $porcentajeOtros,
            2
        );

        if ($sumaPorcentajes != 100) {
            return back()
                ->withInput()
                ->withErrors([
                    'porcentaje_medico' =>
                        "La distribución debe sumar 100%. Actualmente suma {$sumaPorcentajes}%."
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | BUSCAR CONSULTA
        |--------------------------------------------------------------------------
        */

        $consultation = Consultation::with([
            'appointment.patient',
            'appointment.doctor'
        ])->findOrFail($request->consultation_id);

        // Evitar doble registro
        if ($consultation->pagoMedico()->exists()) {
            return redirect()
                ->route(
                    'pagos_medicos.show',
                    $consultation->pagoMedico->id
                )
                ->with(
                    'info',
                    'Esta consulta ya tiene un pago médico registrado.'
                );
        }

        // La consulta debe tener una cita
        if (!$consultation->appointment) {
            return back()
                ->withInput()
                ->withErrors([
                    'consultation_id' =>
                        'La consulta no tiene una cita asociada.'
                ]);
        }

        // La cita debe tener médico
        if (!$consultation->appointment->doctor) {
            return back()
                ->withInput()
                ->withErrors([
                    'consultation_id' =>
                        'La cita no tiene un médico asociado.'
                ]);
        }

        $doctor = $consultation->appointment->doctor;
        $appointment = $consultation->appointment;
        $patient = $appointment->patient;

        /*
        |--------------------------------------------------------------------------
        | CALCULAR DISTRIBUCIÓN
        |--------------------------------------------------------------------------
        */

        $montoMedico = round(
            $costo * ($porcentajeMedico / 100),
            2
        );

        $montoInstitucion = round(
            $costo * ($porcentajeInstitucion / 100),
            2
        );

        /*
         * Para evitar problemas de redondeo,
         * Otros recibe el saldo restante.
         */
        $montoOtros = round(
            $costo - $montoMedico - $montoInstitucion,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | CAJAS
        |--------------------------------------------------------------------------
        */

        $cajaDoctores = Caja::where('nombre', 'Doctores')
            ->where('estado', true)
            ->first();

        $cajaEmpresa = Caja::where('nombre', 'Empresa')
            ->where('estado', true)
            ->first();

        $cajaOtros = Caja::where('nombre', 'Otros')
            ->where('estado', true)
            ->first();

        if (!$cajaDoctores || !$cajaEmpresa || !$cajaOtros) {
            return back()
                ->withInput()
                ->withErrors([
                    'costo_atencion' =>
                        'No se encontraron las tres cajas activas: Doctores, Empresa y Otros.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | TRANSACCIÓN
        |--------------------------------------------------------------------------
        */

        try {

            $pagoMedico = DB::transaction(function () use ($consultation, $doctor, $costo, $porcentajeMedico, $porcentajeInstitucion, $porcentajeOtros, $montoMedico, $montoInstitucion, $montoOtros, $cajaDoctores, $cajaEmpresa, $cajaOtros, $patient) {

                /*
                |--------------------------------------------------------------------------
                | BLOQUEAR CAJAS
                |--------------------------------------------------------------------------
                */

                $cajaDoctores = Caja::where('id', $cajaDoctores->id)
                    ->lockForUpdate()
                    ->first();

                $cajaEmpresa = Caja::where('id', $cajaEmpresa->id)
                    ->lockForUpdate()
                    ->first();

                $cajaOtros = Caja::where('id', $cajaOtros->id)
                    ->lockForUpdate()
                    ->first();

                /*
                |--------------------------------------------------------------------------
                | CREAR PAGO
                |--------------------------------------------------------------------------
                */

                $pagoMedico = PagoMedico::create([
                    'consultation_id' => $consultation->id,
                    'doctor_id' => $doctor->id,
                    'costo_atencion' => $costo,
                    'fecha_atencion' => $consultation->appointment->fecha,
                    'hora_atencion' => $consultation->appointment->hora,
                    'usuario_id' => auth()->id(),
                    'estado' => 'registrado',
                ]);

                /*
                |--------------------------------------------------------------------------
                | DISTRIBUCIÓN MÉDICO
                |--------------------------------------------------------------------------
                */

                PagoMedicoDistribucion::create([
                    'pago_medico_id' => $pagoMedico->id,
                    'doctor_id' => $doctor->id,
                    'caja_id' => $cajaDoctores->id,
                    'concepto' => 'medico',
                    'porcentaje' => $porcentajeMedico,
                    'monto' => $montoMedico,
                ]);

                /*
                |--------------------------------------------------------------------------
                | DISTRIBUCIÓN EMPRESA
                |--------------------------------------------------------------------------
                */

                PagoMedicoDistribucion::create([
                    'pago_medico_id' => $pagoMedico->id,
                    'doctor_id' => null,
                    'caja_id' => $cajaEmpresa->id,
                    'concepto' => 'institucion',
                    'porcentaje' => $porcentajeInstitucion,
                    'monto' => $montoInstitucion,
                ]);

                /*
                |--------------------------------------------------------------------------
                | DISTRIBUCIÓN OTROS
                |--------------------------------------------------------------------------
                */

                PagoMedicoDistribucion::create([
                    'pago_medico_id' => $pagoMedico->id,
                    'doctor_id' => null,
                    'caja_id' => $cajaOtros->id,
                    'concepto' => 'otros',
                    'porcentaje' => $porcentajeOtros,
                    'monto' => $montoOtros,
                ]);

                /*
                |--------------------------------------------------------------------------
                | INGRESO CAJA DOCTORES
                |--------------------------------------------------------------------------
                */

                $saldoAnterior = $cajaDoctores->saldo;
                $saldoNuevo = $saldoAnterior + $montoMedico;

                $cajaDoctores->update([
                    'saldo' => $saldoNuevo
                ]);

                MovimientoCaja::create([
                    'caja_id' => $cajaDoctores->id,
                    'tipo' => 'ingreso',
                    'concepto' => 'Atención médica',
                    'monto' => $montoMedico,
                    'saldo_anterior' => $saldoAnterior,
                    'saldo_nuevo' => $saldoNuevo,
                    'referencia_tipo' => 'pago_medico',
                    'referencia_id' => $pagoMedico->id,
                    'usuario_id' => auth()->id(),
                    'fecha' => now(),
                    'observacion' =>
                        'Paciente: ' .
                        ($patient
                            ? $patient->nombres . ' ' . $patient->apellidos
                            : 'Sin paciente'),
                ]);

                /*
                |--------------------------------------------------------------------------
                | INGRESO CAJA EMPRESA
                |--------------------------------------------------------------------------
                */

                $saldoAnterior = $cajaEmpresa->saldo;
                $saldoNuevo = $saldoAnterior + $montoInstitucion;

                $cajaEmpresa->update([
                    'saldo' => $saldoNuevo
                ]);

                MovimientoCaja::create([
                    'caja_id' => $cajaEmpresa->id,
                    'tipo' => 'ingreso',
                    'concepto' => 'Atención médica - Empresa',
                    'monto' => $montoInstitucion,
                    'saldo_anterior' => $saldoAnterior,
                    'saldo_nuevo' => $saldoNuevo,
                    'referencia_tipo' => 'pago_medico',
                    'referencia_id' => $pagoMedico->id,
                    'usuario_id' => auth()->id(),
                    'fecha' => now(),
                    'observacion' =>
                        'Paciente: ' .
                        ($patient
                            ? $patient->nombres . ' ' . $patient->apellidos
                            : 'Sin paciente'),
                ]);

                /*
                |--------------------------------------------------------------------------
                | INGRESO CAJA OTROS
                |--------------------------------------------------------------------------
                */

                $saldoAnterior = $cajaOtros->saldo;
                $saldoNuevo = $saldoAnterior + $montoOtros;

                $cajaOtros->update([
                    'saldo' => $saldoNuevo
                ]);

                MovimientoCaja::create([
                    'caja_id' => $cajaOtros->id,
                    'tipo' => 'ingreso',
                    'concepto' => 'Atención médica - Otros',
                    'monto' => $montoOtros,
                    'saldo_anterior' => $saldoAnterior,
                    'saldo_nuevo' => $saldoNuevo,
                    'referencia_tipo' => 'pago_medico',
                    'referencia_id' => $pagoMedico->id,
                    'usuario_id' => auth()->id(),
                    'fecha' => now(),
                    'observacion' =>
                        'Paciente: ' .
                        ($patient
                            ? $patient->nombres . ' ' . $patient->apellidos
                            : 'Sin paciente'),
                ]);

                return $pagoMedico;
            });

        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'costo_atencion' =>
                        'No se pudo registrar el pago médico. ' .
                        $e->getMessage()
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | GUARDAR CONFIGURACIÓN
        |--------------------------------------------------------------------------
        */

        ConfiguracionDistribucionMedica::create([
            'porcentaje_medico' => $porcentajeMedico,
            'porcentaje_institucion' => $porcentajeInstitucion,
            'porcentaje_otros' => $porcentajeOtros,
        ]);

        return redirect()
            ->route('pagos_medicos.show', $pagoMedico->id)
            ->with(
                'success',
                'Pago médico registrado correctamente.'
            );
    }

    /**
     * Mostrar detalle del pago médico.
     */
    public function show($id)
    {
        $pagoMedico = PagoMedico::with([
            'consultation.appointment.patient',
            'consultation.appointment.doctor',
            'doctor',
            'usuario',
            'distribuciones.caja'
        ])->findOrFail($id);

        return view(
            'pagos_medicos.show',
            compact('pagoMedico')
        );
    }
}