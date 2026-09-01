<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO DE INVENTARIO
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = $request->search;

        $insumos = Insumo::query()

            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('codigo', 'like', "%{$search}%")
                        ->orWhere('nombre', 'like', "%{$search}%")
                        ->orWhere('tipo', 'like', "%{$search}%");

                });

            })

            ->orderBy('nombre', 'asc')
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | TOTALES DEL INVENTARIO
        |--------------------------------------------------------------------------
        */

        $totalProductos = Insumo::count();

        $productosAgotados = Insumo::where('stock', '<=', 0)->count();

        $productosStockBajo = Insumo::whereColumn(
            'stock',
            '<=',
            'stock_minimo'
        )
            ->where('stock', '>', 0)
            ->count();

        $valorInventario = Insumo::selectRaw(
            'SUM(stock * precio_compra) as total'
        )->value('total');


        return view(
            'insumos.inventario.index',
            compact(
                'insumos',
                'search',
                'totalProductos',
                'productosAgotados',
                'productosStockBajo',
                'valorInventario'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | PDF DEL INVENTARIO
    |--------------------------------------------------------------------------
    */

    public function pdf(Request $request)
    {
        $search = $request->search;

        $insumos = Insumo::query()

            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('codigo', 'like', "%{$search}%")
                        ->orWhere('nombre', 'like', "%{$search}%")
                        ->orWhere('tipo', 'like', "%{$search}%");

                });

            })

            ->orderBy('nombre', 'asc')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | TOTALES
        |--------------------------------------------------------------------------
        */

        $totalProductos = $insumos->count();

        $productosAgotados = $insumos
            ->where('stock', '<=', 0)
            ->count();

        $productosStockBajo = $insumos
            ->filter(function ($insumo) {

                return $insumo->stock > 0 &&
                    $insumo->stock <= $insumo->stock_minimo;

            })
            ->count();

        $valorInventario = $insumos->sum(function ($insumo) {

            return $insumo->stock * $insumo->precio_compra;

        });


        $pdf = Pdf::loadView(
            'insumos.inventario.pdf',
            compact(
                'insumos',
                'totalProductos',
                'productosAgotados',
                'productosStockBajo',
                'valorInventario',
                'search'
            )
        );


        return $pdf->stream(
            'Inventario-Insumos.pdf'
        );
    }
}
