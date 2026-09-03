<?php

namespace App\Http\Controllers;

use App\Models\EstudioComplementario;
use App\Models\EstudioComplementarioDetalle;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class EstudioComplementarioController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $estudios = EstudioComplementario::with([
            'consultation.appointment.patient',
            'consultation.appointment.doctor',
            'detalles'
        ])
            ->orderByDesc('id')
            ->paginate(10);

        return view('estudios.index', compact('estudios'));
    }


    /*
    |--------------------------------------------------------------------------
    | FORMULARIO REGISTRO
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $consultations = Consultation::with([
            'appointment.patient',
            'appointment.doctor'
        ])
            ->where('atendido', true)
            ->orderByDesc('id')
            ->get();

        return view('estudios.create', compact(
            'consultations'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTRAR
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'fecha' => 'required|date',

            'nombre_estudio' => 'required|array|min:1',
            'nombre_estudio.*' => 'required|string|max:255',

            'tipo' => 'nullable|array',
            'tipo.*' => 'nullable|string|max:100',

            'laboratorio' => 'nullable|array',
            'laboratorio.*' => 'nullable|string|max:255',

            'precio_laboratorio' => 'required|array',
            'precio_laboratorio.*' => 'required|numeric|min:0',

            'precio_cobrado' => 'required|array',
            'precio_cobrado.*' => 'required|numeric|min:0',

            'monto_pagado_paciente' => 'required|numeric|min:0',

            'monto_pagado_laboratorio' => 'required|numeric|min:0',

            'observaciones' => 'nullable|string',
        ]);


        DB::transaction(function () use ($request) {

            $consulta = Consultation::with([
                'appointment.doctor'
            ])->findOrFail($request->consultation_id);


            /*
            |--------------------------------------------------------------------------
            | GENERAR CÓDIGO
            |--------------------------------------------------------------------------
            */

            $ultimo = EstudioComplementario::orderByDesc('id')->first();

            $numero = $ultimo
                ? $ultimo->id + 1
                : 1;

            $codigo = 'EST-' . str_pad(
                $numero,
                6,
                '0',
                STR_PAD_LEFT
            );


            /*
            |--------------------------------------------------------------------------
            | CALCULAR TOTALES
            |--------------------------------------------------------------------------
            */

            $totalCobrado = 0;
            $totalLaboratorio = 0;

            foreach ($request->nombre_estudio as $i => $nombre) {

                $precioLaboratorio =
                    (float) $request->precio_laboratorio[$i];

                $precioCobrado =
                    (float) $request->precio_cobrado[$i];

                $totalLaboratorio += $precioLaboratorio;
                $totalCobrado += $precioCobrado;
            }


            $utilidad = $totalCobrado - $totalLaboratorio;


            /*
            |--------------------------------------------------------------------------
            | PAGOS
            |--------------------------------------------------------------------------
            */

            $montoPagadoPaciente =
                (float) $request->monto_pagado_paciente;

            $montoPagadoLaboratorio =
                (float) $request->monto_pagado_laboratorio;


            $saldoPaciente =
                max(0, $totalCobrado - $montoPagadoPaciente);

            $saldoLaboratorio =
                max(0, $totalLaboratorio - $montoPagadoLaboratorio);


            /*
            |--------------------------------------------------------------------------
            | DOCTOR
            |--------------------------------------------------------------------------
            */

            $doctorId =
                $consulta->appointment?->doctor_id;


            /*
            |--------------------------------------------------------------------------
            | CABECERA
            |--------------------------------------------------------------------------
            */

            $estudio = EstudioComplementario::create([

                'codigo' => $codigo,

                'consultation_id' =>
                    $request->consultation_id,

                'doctor_id' =>
                    $doctorId,

                'fecha' =>
                    $request->fecha,

                'total_cobrado' =>
                    $totalCobrado,

                'total_laboratorio' =>
                    $totalLaboratorio,

                'utilidad' =>
                    $utilidad,

                'monto_pagado_paciente' =>
                    $montoPagadoPaciente,

                'saldo_paciente' =>
                    $saldoPaciente,

                'monto_pagado_laboratorio' =>
                    $montoPagadoLaboratorio,

                'saldo_laboratorio' =>
                    $saldoLaboratorio,

                'estado' => true,

                'observaciones' =>
                    $request->observaciones,

                'usuario_id' =>
                    Auth::id(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | DETALLES
            |--------------------------------------------------------------------------
            */

            foreach ($request->nombre_estudio as $i => $nombre) {

                $precioLaboratorio =
                    (float) $request->precio_laboratorio[$i];

                $precioCobrado =
                    (float) $request->precio_cobrado[$i];

                $utilidadDetalle =
                    $precioCobrado - $precioLaboratorio;


                EstudioComplementarioDetalle::create([

                    'estudio_complementario_id' =>
                        $estudio->id,

                    'nombre_estudio' =>
                        $nombre,

                    'tipo' =>
                        $request->tipo[$i] ?? null,

                    'laboratorio' =>
                        $request->laboratorio[$i] ?? null,

                    'precio_laboratorio' =>
                        $precioLaboratorio,

                    'precio_cobrado' =>
                        $precioCobrado,

                    'utilidad' =>
                        $utilidadDetalle,

                    'observaciones' =>
                        null,
                ]);
            }
        });


        return redirect()
            ->route('estudios.index')
            ->with(
                'success',
                'Estudio complementario registrado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VER DETALLE
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $estudio = EstudioComplementario::with([
            'consultation.appointment.patient',
            'consultation.appointment.doctor',
            'detalles',
            'usuario'
        ])->findOrFail($id);

        return view(
            'estudios.show',
            compact('estudio')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDITAR
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $estudio = EstudioComplementario::with([
            'consultation.appointment.patient',
            'consultation.appointment.doctor',
            'detalles'
        ])->findOrFail($id);

        return view(
            'estudios.edit',
            compact('estudio')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $estudio = EstudioComplementario::findOrFail($id);

        $request->validate([
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        $estudio->update([
            'fecha' => $request->fecha,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()
            ->route('estudios.show', $estudio->id)
            ->with(
                'success',
                'Estudio actualizado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ANULAR
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $estudio = EstudioComplementario::findOrFail($id);

        $estudio->update([
            'estado' => false
        ]);

        return redirect()
            ->route('estudios.index')
            ->with(
                'success',
                'Estudio complementario anulado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PDF PACIENTE
    |--------------------------------------------------------------------------
    */

    public function pdfPaciente($id)
    {
        $estudio = EstudioComplementario::with([
            'consultation.appointment.patient',
            'consultation.appointment.doctor',
            'detalles'
        ])->findOrFail($id);

        $pdf = Pdf::loadView(
            'estudios.pdf-paciente',
            compact('estudio')
        );

        return $pdf->stream(
            'recibo-paciente-' . $estudio->codigo . '.pdf'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PDF LABORATORIO
    |--------------------------------------------------------------------------
    */

    public function pdfLaboratorio($id)
    {
        $estudio = EstudioComplementario::with([
            'consultation.appointment.patient',
            'consultation.appointment.doctor',
            'detalles'
        ])->findOrFail($id);

        $pdf = Pdf::loadView(
            'estudios.pdf-laboratorio',
            compact('estudio')
        );

        return $pdf->stream(
            'recibo-laboratorio-' . $estudio->codigo . '.pdf'
        );
    }
}