<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\InsumoAgrupado;
use App\Models\InsumoAgrupadoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InsumoAgrupadoController extends Controller
{
    /**
     * LISTADO DE COMBOS
     */
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $agrupados = InsumoAgrupado::with([
            'detalles.insumo',
            'usuario'
        ])
            ->when($buscar, function ($query) use ($buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('codigo', 'like', "%{$buscar}%")
                        ->orWhere('nombre', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('insumos.agrupados.index', compact(
            'agrupados',
            'buscar'
        ));
    }


    /**
     * FORMULARIO NUEVO COMBO
     */
    public function create()
    {
        $insumos = Insumo::where('estado', true)
            ->orderBy('nombre', 'asc')
            ->get();

        return view('insumos.agrupados.create', compact('insumos'));
    }


    /**
     * GUARDAR COMBO
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'detalles' => 'nullable|array|min:1',

            'detalles.*.insumo_id' => 'nullable|exists:insumos,id',
            'detalles.*.nombre_otro' => 'nullable|string|max:255',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
        ], [
            'nombre.required' => 'El nombre del combo es obligatorio.',
            'precio.required' => 'El precio del combo es obligatorio.',
            'precio.numeric' => 'El precio debe ser numérico.',
            'detalles.nullable' => 'Debe agregar al menos un elemento al combo.',
            'detalles.min' => 'Debe agregar al menos un elemento al combo.',
            'detalles.*.cantidad.required' => 'La cantidad es obligatoria.',
            'detalles.*.cantidad.min' => 'La cantidad debe ser mayor a cero.',
        ]);

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | GENERAR CÓDIGO
            |--------------------------------------------------------------------------
            */

            $ultimo = InsumoAgrupado::orderBy('id', 'desc')->first();

            $numero = $ultimo
                ? $ultimo->id + 1
                : 1;

            $codigo = 'AGR-' . str_pad($numero, 6, '0', STR_PAD_LEFT);


            /*
            |--------------------------------------------------------------------------
            | CREAR COMBO
            |--------------------------------------------------------------------------
            */

            $agrupado = InsumoAgrupado::create([
                'codigo' => $codigo,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'precio' => $request->precio,
                'estado' => true,
                'usuario_id' => Auth::id(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | GUARDAR DETALLES
            |--------------------------------------------------------------------------
            */

            foreach ($request->detalles as $detalle) {

                $insumoId = !empty($detalle['insumo_id'])
                    ? $detalle['insumo_id']
                    : null;

                $nombreOtro = empty($insumoId)
                    ? ($detalle['nombre_otro'] ?? null)
                    : null;

                if (!$insumoId && empty($nombreOtro)) {
                    throw new \Exception(
                        'Cada elemento debe tener un insumo o un nombre de Otro.'
                    );
                }

                InsumoAgrupadoDetalle::create([
                    'insumo_agrupado_id' => $agrupado->id,
                    'insumo_id' => $insumoId,
                    'nombre_otro' => $nombreOtro,
                    'cantidad' => $detalle['cantidad'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('insumos.agrupados.index')
                ->with('success', 'Combo registrado correctamente.');

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'No se pudo registrar el combo: ' . $e->getMessage());
        }
    }


    /**
     * FORMULARIO EDITAR
     */
    public function edit($id)
    {
        $agrupado = InsumoAgrupado::with([
            'detalles.insumo'
        ])->findOrFail($id);

        $insumos = Insumo::where('estado', true)
            ->orderBy('nombre', 'asc')
            ->get();

        return view('insumos.agrupados.edit', compact(
            'agrupado',
            'insumos'
        ));
    }


    /**
     * ACTUALIZAR COMBO
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'detalles' => 'nullable|array|min:1',

            'detalles.*.insumo_id' => 'nullable|exists:insumos,id',
            'detalles.*.nombre_otro' => 'nullable|string|max:255',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
        ], [
            'nombre.required' => 'El nombre del combo es obligatorio.',
            'precio.required' => 'El precio del combo es obligatorio.',
            'detalles.required' => 'Debe agregar al menos un elemento al combo.',
        ]);

        DB::beginTransaction();

        try {

            $agrupado = InsumoAgrupado::findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | ACTUALIZAR CABECERA
            |--------------------------------------------------------------------------
            */

            $agrupado->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'precio' => $request->precio,
            ]);


            /*
            |--------------------------------------------------------------------------
            | ELIMINAR DETALLES ANTERIORES
            |--------------------------------------------------------------------------
            */

            $agrupado->detalles()->delete();


            /*
            |--------------------------------------------------------------------------
            | CREAR NUEVOS DETALLES
            |--------------------------------------------------------------------------
            */

            foreach ($request->detalles as $detalle) {

                $insumoId = !empty($detalle['insumo_id'])
                    ? $detalle['insumo_id']
                    : null;

                $nombreOtro = empty($insumoId)
                    ? ($detalle['nombre_otro'] ?? null)
                    : null;

                if (!$insumoId && empty($nombreOtro)) {
                    throw new \Exception(
                        'Cada elemento debe tener un insumo o un nombre de Otro.'
                    );
                }

                InsumoAgrupadoDetalle::create([
                    'insumo_agrupado_id' => $agrupado->id,
                    'insumo_id' => $insumoId,
                    'nombre_otro' => $nombreOtro,
                    'cantidad' => $detalle['cantidad'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('insumos.agrupados.index')
                ->with('success', 'Combo actualizado correctamente.');

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'No se pudo actualizar el combo: ' . $e->getMessage());
        }
    }


    /**
     * ELIMINAR COMBO
     */
    public function destroy($id)
    {
        try {

            $agrupado = InsumoAgrupado::findOrFail($id);

            $agrupado->delete();

            return redirect()
                ->route('insumos.agrupados.index')
                ->with('success', 'Combo eliminado correctamente.');

        } catch (\Throwable $e) {

            return back()
                ->with('error', 'No se pudo eliminar el combo.');
        }
    }
}