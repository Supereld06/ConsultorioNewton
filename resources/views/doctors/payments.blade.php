@extends('layouts.app')

@section('content')

<div class="container">

    <h3 class="mb-4">
        💰 Pagos pendientes - Dr. {{ $doctor->nombres }} {{ $doctor->apellidos }}
    </h3>

    @if( $doctor->medicalPayments->count() > 0)
    <a href="{{ route('doctors.pay', $doctor->id) }}" class="btn btn-success btn-sm">
        💳 PAGAR 
    </a>
    @else
    <button class="btn btn-secondary btn-sm" disabled>
        ✔ Sin deudas
    </button>
    @endif


    <a href="{{ route('doctors.index') }}" class="btn btn-secondary btn-sm">
        ← Volver
    </a>

    <div class="card">
        <div class="card-body p-0">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">
                    <tr>
                        <th>Paciente</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Costo Atención</th>
                        <th>% Newton</th>
                        <th>% Médico</th>
                        <th>Newton</th>
                        <th>Médico</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($payments as $p)
                    <tr>
                        <td>
                            {{ $p->consultation->appointment->patient->nombres }}
                            {{ $p->consultation->appointment->patient->apellidos }}
                        </td>
                        <td>{{ $p->consultation->appointment->fecha }}</td>
                        <td>{{ $p->consultation->appointment->hora }}</td>
                        <td>{{ $p->cost }}</td>
                        <td>{{ $p->percentage_newton }}%</td>
                        <td>{{ $p->percentage_doctor }}%</td>
                        <td>{{ $p->cost_newton }}</td>
                        <td>{{ $p->cost_doctor }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            No hay pagos pendientes
                        </td>
                    </tr>
                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

    <!-- TOTAL -->
    <div class="card mt-3">
        <div class="card-body text-end">

            <h4>
                Total a pagar al médico:
                <span class="text-success">
                    {{ $totalDoctor }}
                </span>
            </h4>

        </div>
    </div>

</div>

@endsection