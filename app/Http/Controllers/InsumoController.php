<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsumoController extends Controller
{
    /**
     * LISTADO DE INSUMOS
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $tipo = $request->tipo;
        $estado = $request->estado;

        $insumos = Insumo::query()

            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where('codigo', 'like', "%{$search}%")
                        ->orWhere('nombre', 'like', "%{$search}%");

                });

            })

            ->when($tipo, function ($query) use ($tipo) {

                $query->where('tipo', $tipo);

            })

            ->when($estado !== null && $estado !== '', function ($query) use ($estado) {

                $query->where('estado', $estado);

            })

            ->orderBy('nombre', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view(
            'insumos.listado.index',
            compact('insumos', 'search', 'tipo', 'estado')
        );
    }


    /**
     * FORMULARIO PARA CREAR INSUMO
     */
    public function create()
    {
        return view('insumos.listado.create');
    }


    /**
     * GUARDAR INSUMO
     */
    public function store(Request $request)
    {
        $request->validate([

            'codigo' => 'required|string|max:50|unique:insumos,codigo',

            'nombre' => 'required|string|max:255',

            'tipo' => 'required|in:MEDICAMENTO,INSUMO_MEDICO',

            'descripcion' => 'nullable|string',

            'unidad_medida' => 'required|string|max:50',

            'stock_minimo' => 'required|numeric|min:0',

            'precio_compra' => 'required|numeric|min:0',

            'precio_venta' => 'required|numeric|min:0',

        ], [

            'codigo.required' => 'El código es obligatorio.',

            'codigo.unique' => 'El código ya está registrado.',

            'nombre.required' => 'El nombre del insumo es obligatorio.',

            'tipo.required' => 'Debe seleccionar un tipo de insumo.',

            'unidad_medida.required' => 'Debe seleccionar una unidad de medida.',

            'stock_minimo.required' => 'El stock mínimo es obligatorio.',

            'stock_minimo.numeric' => 'El stock mínimo debe ser numérico.',

            'precio_compra.required' => 'El precio de compra es obligatorio.',

            'precio_venta.required' => 'El precio de venta es obligatorio.',

        ]);


        try {

            DB::transaction(function () use ($request) {

                Insumo::create([

                    'codigo' => $request->codigo,

                    'nombre' => $request->nombre,

                    'tipo' => $request->tipo,

                    'descripcion' => $request->descripcion,

                    'unidad_medida' => $request->unidad_medida,

                    'stock' => 0,

                    'stock_minimo' => $request->stock_minimo,

                    'precio_compra' => $request->precio_compra,

                    'precio_venta' => $request->precio_venta,

                    'estado' => true,

                ]);

            });

            return redirect()
                ->route('insumos.index')
                ->with('success', 'Insumo registrado correctamente.');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al registrar el insumo.');

        }
    }


    /**
     * FORMULARIO EDITAR
     */
    public function edit($id)
    {
        $insumo = Insumo::findOrFail($id);

        return view(
            'insumos.listado.edit',
            compact('insumo')
        );
    }


    /**
     * ACTUALIZAR INSUMO
     */
    public function update(Request $request, $id)
    {
        $insumo = Insumo::findOrFail($id);

        $request->validate([

            'codigo' => 'required|string|max:50|unique:insumos,codigo,' . $id,

            'nombre' => 'required|string|max:255',

            'tipo' => 'required|in:MEDICAMENTO,INSUMO_MEDICO',

            'descripcion' => 'nullable|string',

            'unidad_medida' => 'required|string|max:50',

            'stock_minimo' => 'required|numeric|min:0',

            'precio_compra' => 'required|numeric|min:0',

            'precio_venta' => 'required|numeric|min:0',

        ], [

            'codigo.required' => 'El código es obligatorio.',

            'codigo.unique' => 'El código ya está registrado.',

            'nombre.required' => 'El nombre del insumo es obligatorio.',

            'tipo.required' => 'Debe seleccionar un tipo de insumo.',

            'unidad_medida.required' => 'Debe seleccionar una unidad de medida.',

            'stock_minimo.required' => 'El stock mínimo es obligatorio.',

            'precio_compra.required' => 'El precio de compra es obligatorio.',

            'precio_venta.required' => 'El precio de venta es obligatorio.',

        ]);


        try {

            $insumo->update([

                'codigo' => $request->codigo,

                'nombre' => $request->nombre,

                'tipo' => $request->tipo,

                'descripcion' => $request->descripcion,

                'unidad_medida' => $request->unidad_medida,

                'stock_minimo' => $request->stock_minimo,

                'precio_compra' => $request->precio_compra,

                'precio_venta' => $request->precio_venta,

            ]);

            return redirect()
                ->route('insumos.index')
                ->with('success', 'Insumo actualizado correctamente.');

        } catch (\Exception $e) {

            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al actualizar el insumo.');

        }
    }


    /**
     * ELIMINAR INSUMO
     */
    public function destroy($id)
    {
        $insumo = Insumo::findOrFail($id);

        /*
        No permitimos eliminar un insumo que tenga stock.
        Más adelante también podremos protegerlo si tiene
        movimientos registrados.
        */

        if ($insumo->stock > 0) {

            return back()->with(
                'error',
                'No se puede eliminar el insumo porque actualmente tiene stock disponible.'
            );

        }


        try {

            $insumo->delete();

            return redirect()
                ->route('insumos.index')
                ->with('success', 'Insumo eliminado correctamente.');

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'No se puede eliminar este insumo porque tiene información relacionada.'
            );

        }
    }
}