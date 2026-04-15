@extends('layouts.app')

@section('content')

<div class="container">

    <h4 class="mb-3">
        🧾 Detalle del Recibo:
        <span class="text-primary">{{ $details->first()->receipt_number }}</span>
    </h4>


    <div class="card mb-3">
        <div class="card-body">

            <p>
                <strong>Médico:</strong>
                {{ $details->first()->doctor->nombres }}
                {{ $details->first()->doctor->apellidos }}
            </p>

            <p>
                <strong>Fecha de Pago:</strong>
                {{ $details->first()->created_at->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>

    <a href="{{ route('medical_receipts.pdf', $details->first()->receipt_number) }}"
        target="_blank"
        class="btn btn-danger mb-3">
        🧾 Imprimir PDF
    </a>

    <a href="{{ route('medical_receipts.index') }}" class="btn btn-secondary mb-3">
        ← Volver
    </a>

    <div class="card">
        <div class="card-body p-0">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">
                    <tr>
                        <th>Paciente</th>
                        <th>Fecha Atención</th>
                        <th>Hora</th>
                        <th>Costo Médico</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($details as $d)
                    <tr>
                        <td>
                            {{ $d->patient->nombres }}
                            {{ $d->patient->apellidos }}
                        </td>
                        <td>{{ $d->date }}</td>
                        <td>{{ $d->time }}</td>
                        <td>{{ $d->cost_medico }}</td>
                    </tr>
                    @endforeach

                </tbody>

            </table>

        </div>
    </div>

    <div class="text-end mt-3">
        <h4>
            Total Pagado:
            <span class="text-success">
                {{ $details->first()->total }}
            </span>
        </h4>
    </div>

</div>

@endsection