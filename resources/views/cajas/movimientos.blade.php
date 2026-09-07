@extends('layouts.app')

@section('content')

    <h3 class="mb-0">
        Movimientos - {{ $caja->nombre }}
    </h3>


    <div class="container-fluid">

        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">

                        <h6 class="text-muted">
                            Saldo actual
                        </h6>

                        <h2 class="fw-bold">
                            Bs. {{ number_format($caja->saldo, 2) }}
                        </h2>

                    </div>

                    <div class="col-md-6 text-end">

                        <a href="{{ route('cajas.index') }}" class="btn btn-secondary">

                            ← Volver

                        </a>

                    </div>

                </div>

            </div>

        </div>




        <div class="card">

            <div class="card-body p-0">

                <table class="table table-hover align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>Fecha</th>

                            <th>Tipo</th>

                            <th>Concepto</th>

                            <th>Monto</th>

                            <th>Saldo anterior</th>

                            <th>Saldo nuevo</th>

                            <th>Usuario</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($movimientos as $movimiento)

                            <tr>

                                <td>
                                    {{ $movimiento->fecha->format('d/m/Y H:i') }}
                                </td>

                                <td>

                                    @if($movimiento->tipo == 'ingreso')

                                        <span class="badge bg-success">
                                            INGRESO
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            EGRESO
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $movimiento->concepto }}
                                </td>

                                <td class="fw-bold">

                                    @if($movimiento->tipo == 'ingreso')

                                        <span class="text-success">
                                            + Bs.
                                            {{ number_format($movimiento->monto, 2) }}
                                        </span>

                                    @else

                                        <span class="text-danger">
                                            - Bs.
                                            {{ number_format($movimiento->monto, 2) }}
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    Bs.
                                    {{ number_format($movimiento->saldo_anterior, 2) }}
                                </td>

                                <td>
                                    Bs.
                                    {{ number_format($movimiento->saldo_nuevo, 2) }}
                                </td>

                                <td>

                                    {{ $movimiento->usuario->name ?? 'Sistema' }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center">

                                    No existen movimientos.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

@endsection