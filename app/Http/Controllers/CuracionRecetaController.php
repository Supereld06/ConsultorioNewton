<?php

namespace App\Http\Controllers;

use App\Models\Curacion;
use App\Models\CuracionReceta;
use App\Models\CuracionRecetaDetalle;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CuracionRecetaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CREAR RECETA
    |--------------------------------------------------------------------------
    */

    public function create($id)
    {
        $curacion = Curacion::with([
            'consultation.appointment.patient',
            'consultation.appointment.doctor',
            'receta.detalles'
        ])->findOrFail($id);

        /*
        | Una curación solamente puede tener una receta.
        */

        if ($curacion->receta) {
            return redirect()
                ->route('curaciones.show', $curacion->id)
                ->with(
                    'info',
                    'Esta curación ya tiene una receta registrada.'
                );
        }

        return view(
            'curaciones.recetas.create',
            compact('curacion')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GUARDAR RECETA
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, $id)
    {
        $request->validate([

            'indicaciones' =>
                'nullable|string',

            'detalles' =>
                'required|array|min:1',

            'detalles.*.medicamento' =>
                'required|string|max:255',

            'detalles.*.dosis' =>
                'nullable|string|max:255',

            'detalles.*.frecuencia' =>
                'nullable|string|max:255',

            'detalles.*.duracion' =>
                'nullable|string|max:255',

            'detalles.*.indicaciones' =>
                'nullable|string',
        ]);


        $curacion = Curacion::findOrFail($id);


        if ($curacion->receta) {
            return redirect()
                ->route('curaciones.show', $curacion->id)
                ->with(
                    'info',
                    'Esta curación ya tiene una receta.'
                );
        }


        $receta = CuracionReceta::create([
            'curacion_id' => $curacion->id,
            'indicaciones' => $request->indicaciones,
        ]);


        foreach ($request->detalles as $detalle) {

            CuracionRecetaDetalle::create([
                'curacion_receta_id' => $receta->id,
                'medicamento' => $detalle['medicamento'],
                'dosis' => $detalle['dosis'] ?? null,
                'frecuencia' => $detalle['frecuencia'] ?? null,
                'duracion' => $detalle['duracion'] ?? null,
                'indicaciones' =>
                    $detalle['indicaciones'] ?? null,
            ]);
        }


        return redirect()
            ->route('curaciones.show', $curacion->id)
            ->with(
                'success',
                'Receta registrada correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PDF RECETA
    |--------------------------------------------------------------------------
    */

    public function pdf($id)
    {
        $curacion = Curacion::with([
            'consultation.appointment.patient',
            'consultation.appointment.doctor',
            'receta.detalles',
        ])->findOrFail($id);


        if (!$curacion->receta) {

            return redirect()
                ->route('curaciones.show', $curacion->id)
                ->with(
                    'error',
                    'Esta curación no tiene receta registrada.'
                );
        }


        $pdf = Pdf::loadView(
            'curaciones.recetas.pdf',
            compact('curacion')
        );


        return $pdf->stream(
            'Receta-' . $curacion->codigo . '.pdf'
        );
    }
}