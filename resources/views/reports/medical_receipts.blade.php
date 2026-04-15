@extends('layouts.app')

@section('content')

<div class="container">

    <h3 class="mb-4">📋 Recibos de Pago a Médicos</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body p-0">

            <table class="table table-hover table-bordered">

                <thead class="table-dark">
                    <tr>
                        <th>N° Recibo</th>
                        <th>Médico</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($receipts as $r)
                        <tr>
                            <td>{{ $r->receipt_number }}</td>

                            <td>
                                {{ $r->doctor->nombres }} 
                                {{ $r->doctor->apellidos }}
                            </td>

                            <td>{{ $r->total }}</td>

                            <td>{{ $r->created_at->format('d/m/Y H:i') }}</td>

                            <td>
                                <a href="{{ route('medical_receipts.show', $r->receipt_number) }}"
                                   class="btn btn-primary btn-sm">
                                   🔍 Ver Detalle
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                No hay recibos registrados
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection