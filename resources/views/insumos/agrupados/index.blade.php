@extends('layouts.app')

@section('content')

    <div class="container-fluid py-4">

        {{-- =====================================================
        ENCABEZADO
        ====================================================== --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3 class="mb-1">
                    🧰 Insumos Agrupados
                </h3>

                <p class="text-muted mb-0">
                    Listado de combos de insumos y medicamentos
                </p>
            </div>

            <a href="{{ route('insumos.agrupados.create') }}" class="btn btn-success">

                <i class="bi bi-plus-circle"></i>
                Nuevo Combo

            </a>

        </div>


        {{-- =====================================================
        MENSAJES
        ====================================================== --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- =====================================================
        ERRORES
        ====================================================== --}}

        @if($errors->any())

            <div class="alert alert-danger">

                <strong>Se encontraron los siguientes errores:</strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        {{-- =====================================================
        BUSCADOR
        ====================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <form method="GET" action="{{ route('insumos.agrupados.index') }}">

                    <div class="row g-2">

                        <div class="col-md-8">

                            <input type="text" name="buscar" class="form-control"
                                placeholder="Buscar por código o nombre del combo..." value="{{ $buscar }}">

                        </div>

                        <div class="col-md-2">

                            <button type="submit" class="btn btn-primary w-100">

                                <i class="bi bi-search"></i>
                                Buscar

                            </button>

                        </div>

                        <div class="col-md-2">

                            <a href="{{ route('insumos.agrupados.index') }}" class="btn btn-secondary w-100">

                                <i class="bi bi-x-circle"></i>
                                Limpiar

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        {{-- =====================================================
        TABLA
        ====================================================== --}}

        <div class="card shadow-sm">

            <div class="card-header bg-dark text-white">

                <i class="bi bi-boxes"></i>

                Combos registrados

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover table-bordered mb-0 align-middle">

                        <thead class="table-light">

                            <tr>

                                <th width="100">
                                    Código
                                </th>

                                <th width="220">
                                    Nombre del Combo
                                </th>

                                <th>
                                    Insumos / Elementos
                                </th>

                                <th width="130" class="text-end">
                                    Precio
                                </th>

                                <th width="100" class="text-center">
                                    Estado
                                </th>

                                <th width="150" class="text-center">
                                    Acciones
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($agrupados as $agrupado)

                                <tr>

                                    {{-- CÓDIGO --}}

                                    <td>

                                        <span class="badge bg-secondary">
                                            {{ $agrupado->codigo }}
                                        </span>

                                    </td>


                                    {{-- NOMBRE --}}

                                    <td>

                                        <strong>
                                            {{ $agrupado->nombre }}
                                        </strong>

                                        @if($agrupado->descripcion)

                                            <br>

                                            <small class="text-muted">
                                                {{ $agrupado->descripcion }}
                                            </small>

                                        @endif

                                    </td>


                                    {{-- DETALLES --}}

                                    <td>

                                        @if($agrupado->detalles->count())

                                            <ul class="mb-0 ps-3">

                                                @foreach($agrupado->detalles as $detalle)

                                                                        <li>

                                                                            <strong>
                                                                                {{ number_format($detalle->cantidad, 2) }}
                                                                            </strong>

                                                                            ×

                                                                            {{ $detalle->insumo
                                                    ? $detalle->insumo->nombre
                                                    : $detalle->nombre_otro
                                                                                                                            }}

                                                                            @if($detalle->insumo)

                                                                                <small class="text-muted">
                                                                                    (Insumo)
                                                                                </small>

                                                                            @else

                                                                                <span class="badge bg-warning text-dark">
                                                                                    Otro
                                                                                </span>

                                                                            @endif

                                                                        </li>

                                                @endforeach

                                            </ul>

                                        @else

                                            <span class="text-muted">
                                                Sin elementos
                                            </span>

                                        @endif

                                    </td>


                                    {{-- PRECIO --}}

                                    <td class="text-end">

                                        <strong>
                                            Bs {{ number_format($agrupado->precio, 2) }}
                                        </strong>

                                    </td>


                                    {{-- ESTADO --}}

                                    <td class="text-center">

                                        @if($agrupado->estado)

                                            <span class="badge bg-success">
                                                Activo
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Inactivo
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACCIONES --}}

                                    <td class="text-center">

                                        <div class="d-flex justify-content-center gap-1">

                                            <a href="{{ route('insumos.agrupados.edit', $agrupado->id) }}"
                                                class="btn btn-warning btn-sm" title="Editar">

                                                <i class="bi bi-pencil-square"></i>

                                            </a>


                                            <form action="{{ route('insumos.agrupados.destroy', $agrupado->id) }}" method="POST"
                                                onsubmit="return confirm('¿Está seguro de eliminar este combo?');">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="text-center py-5">

                                        <i class="bi bi-box-seam fs-1 text-muted"></i>

                                        <h5 class="mt-3">
                                            No hay combos registrados
                                        </h5>

                                        <p class="text-muted">
                                            Comience registrando un nuevo combo.
                                        </p>

                                        <a href="{{ route('insumos.agrupados.create') }}" class="btn btn-primary">

                                            <i class="bi bi-plus-circle"></i>
                                            Nuevo Combo

                                        </a>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- PAGINACIÓN --}}

            @if($agrupados->hasPages())

                <div class="card-footer">

                    {{ $agrupados->links() }}

                </div>

            @endif

        </div>

    </div>

@endsection