@extends('layouts.app')

@section('content')

    <div class="container">

        {{-- ENCABEZADO --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h3>
                    <i class="bi bi-box-seam"></i>
                    Listado de Insumos
                </h3>

                <p class="text-muted mb-0">
                    Medicamentos e insumos médicos registrados
                </p>
            </div>

            <a href="{{ route('insumos.create') }}" class="btn btn-success">

                <i class="bi bi-plus-circle"></i>
                Nuevo Insumo

            </a>

        </div>


        {{-- MENSAJE ÉXITO --}}
        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show">

                <i class="bi bi-check-circle-fill"></i>

                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- MENSAJE ERROR --}}
        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show">

                <i class="bi bi-exclamation-triangle-fill"></i>

                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- ERRORES DE VALIDACIÓN --}}
        @if($errors->any())

            <div class="alert alert-danger">

                <strong>
                    <i class="bi bi-exclamation-triangle"></i>
                    Hay errores en el formulario:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- BUSCADOR Y FILTROS --}}
        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <form method="GET" action="{{ route('insumos.index') }}">

                    <div class="row g-3">

                        {{-- BUSCAR --}}
                        <div class="col-md-5">

                            <label class="form-label">
                                Buscar
                            </label>

                            <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control"
                                placeholder="Código o nombre del insumo">

                        </div>


                        {{-- TIPO --}}
                        <div class="col-md-3">

                            <label class="form-label">
                                Tipo
                            </label>

                            <select name="tipo" class="form-select">

                                <option value="">
                                    Todos
                                </option>

                                <option value="MEDICAMENTO" {{ ($tipo ?? '') == 'MEDICAMENTO' ? 'selected' : '' }}>

                                    Medicamentos

                                </option>

                                <option value="INSUMO_MEDICO" {{ ($tipo ?? '') == 'INSUMO_MEDICO' ? 'selected' : '' }}>

                                    Insumos médicos

                                </option>

                            </select>

                        </div>


                        {{-- ESTADO --}}
                        <div class="col-md-2">

                            <label class="form-label">
                                Estado
                            </label>

                            <select name="estado" class="form-select">

                                <option value="">
                                    Todos
                                </option>

                                <option value="1" {{ ($estado ?? '') === '1' ? 'selected' : '' }}>

                                    Activos

                                </option>

                                <option value="0" {{ ($estado ?? '') === '0' ? 'selected' : '' }}>

                                    Inactivos

                                </option>

                            </select>

                        </div>


                        {{-- BOTONES --}}
                        <div class="col-md-2 d-flex align-items-end gap-2">

                            <button type="submit" class="btn btn-primary">

                                <i class="bi bi-search"></i>
                                Buscar

                            </button>

                            <a href="{{ route('insumos.index') }}" class="btn btn-secondary">

                                <i class="bi bi-arrow-clockwise">Limpiar</i>

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- TABLA --}}
        <div class="card shadow">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-dark">

                            <tr>

                                <th>ID</th>

                                <th>Código</th>

                                <th>Insumo</th>

                                <th>Tipo</th>

                                <th>Unidad</th>

                                <th>Stock</th>

                                <th>Stock mínimo</th>

                                <th>Precio compra</th>

                                <th>Precio venta</th>

                                <th>Estado</th>

                                <th>Acciones</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($insumos as $insumo)

                                <tr>

                                    {{-- ID --}}
                                    <td>
                                        {{ $insumo->id }}
                                    </td>


                                    {{-- CÓDIGO --}}
                                    <td>

                                        <strong>
                                            {{ $insumo->codigo }}
                                        </strong>

                                    </td>


                                    {{-- NOMBRE --}}
                                    <td>

                                        <strong>
                                            {{ $insumo->nombre }}
                                        </strong>

                                        @if($insumo->descripcion)

                                            <br>

                                            <small class="text-muted">
                                                {{ $insumo->descripcion }}
                                            </small>

                                        @endif

                                    </td>


                                    {{-- TIPO --}}
                                    <td>

                                        @if($insumo->tipo === 'MEDICAMENTO')

                                            <span class="badge bg-info">
                                                <i class="bi bi-capsule"></i>
                                                Medicamento
                                            </span>

                                        @else

                                            <span class="badge bg-primary">
                                                <i class="bi bi-bandaid"></i>
                                                Insumo médico
                                            </span>

                                        @endif

                                    </td>


                                    {{-- UNIDAD --}}
                                    <td>
                                        {{ $insumo->unidad_medida }}
                                    </td>


                                    {{-- STOCK --}}
                                    <td>

                                        @if($insumo->stock <= 0)

                                            <span class="badge bg-danger">
                                                AGOTADO
                                            </span>

                                        @elseif($insumo->stock <= $insumo->stock_minimo)

                                            <span class="badge bg-warning text-dark">

                                                {{ number_format($insumo->stock, 2) }}

                                                <i class="bi bi-exclamation-triangle"></i>

                                            </span>

                                        @else

                                            <span class="badge bg-success">

                                                {{ number_format($insumo->stock, 2) }}

                                            </span>

                                        @endif

                                    </td>


                                    {{-- STOCK MÍNIMO --}}
                                    <td>

                                        {{ number_format($insumo->stock_minimo, 2) }}

                                    </td>


                                    {{-- PRECIO COMPRA --}}
                                    <td>

                                        Bs.
                                        {{ number_format($insumo->precio_compra, 2) }}

                                    </td>


                                    {{-- PRECIO VENTA --}}
                                    <td>

                                        Bs.
                                        {{ number_format($insumo->precio_venta, 2) }}

                                    </td>


                                    {{-- ESTADO --}}
                                    <td>

                                        @if($insumo->estado)

                                            <span class="badge bg-success">
                                                Activo
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                Inactivo
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACCIONES --}}
                                    <td>

                                        <div class="d-flex gap-1">

                                            {{-- EDITAR --}}
                                            <a href="{{ route('insumos.edit', $insumo->id) }}" class="btn btn-warning btn-sm"
                                                title="Editar">

                                                <i class="bi bi-pencil-square">Editar</i>

                                            </a>


                                            {{-- ELIMINAR --}}
                                            <form action="{{ route('insumos.destroy', $insumo->id) }}" method="POST"
                                                style="display:inline">

                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm" title="Eliminar"
                                                    onclick="return confirm('¿Está seguro de eliminar este insumo?')">

                                                    <i class="bi bi-trash">Eliminar</i>

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="11" class="text-center py-4">

                                        <i class="bi bi-inbox fs-2 text-muted"></i>

                                        <p class="text-muted mb-0 mt-2">

                                            No hay insumos registrados.

                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- PAGINACIÓN --}}
        <div class="mt-3">

            {{ $insumos->links() }}

        </div>

    </div>

@endsection