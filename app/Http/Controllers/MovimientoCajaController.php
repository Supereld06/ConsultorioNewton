<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\MovimientoCaja;
use App\Services\CajaService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovimientoCajaController extends Controller
{
    /**
     * Mostrar movimientos de una caja
     */
    public function index(Request $request, Caja $caja)
    {
        $query = MovimientoCaja::with('usuario')
            ->where('caja_id', $caja->id);

        // FILTRO POR TIPO
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        // FILTRO POR ESTADO
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // FILTRO POR FECHA INICIAL
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        // FILTRO POR FECHA FINAL
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        // BUSQUEDA
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;

            $query->where(function ($q) use ($buscar) {
                $q->where('concepto', 'like', "%{$buscar}%")
                    ->orWhere('observacion', 'like', "%{$buscar}%")
                    ->orWhere('transferencia_id', 'like', "%{$buscar}%");
            });
        }

        $movimientos = $query
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        // TOTALES
        $totalIngresos = (clone $query)
            ->where('tipo', 'ingreso')
            ->sum('monto');

        $totalEgresos = (clone $query)
            ->where('tipo', 'egreso')
            ->sum('monto');

        return view('cajas.movimientos.index', compact(
            'caja',
            'movimientos',
            'totalIngresos',
            'totalEgresos'
        ));
    }

    /**
     * Generar recibo PDF de un movimiento
     */
    public function recibo(MovimientoCaja $movimiento)
    {
        $movimiento->load([
            'caja',
            'usuario',
        ]);

        // Si es una transferencia, buscamos los movimientos relacionados
        $transferencia = null;

        if ($movimiento->transferencia_id) {

            $movimientosTransferencia = MovimientoCaja::with('caja')
                ->where('transferencia_id', $movimiento->transferencia_id)
                ->orderBy('id')
                ->get();

            $transferencia = $movimientosTransferencia;
        }

        $pdf = Pdf::loadView('cajas.movimientos.recibo', [
            'movimiento' => $movimiento,
            'transferencia' => $transferencia,
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream(
            'recibo-movimiento-' . $movimiento->id . '.pdf'
        );
    }
}