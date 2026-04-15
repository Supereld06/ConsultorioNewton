@extends('layouts.app')

@section('content')

<div class="container">

    <h4 class="mb-3">Pago Médico</h4>

    {{-- MENSAJES --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- FORMULARIO -->
    <div class="card mb-3">
        <div class="card-body">

            <form method="POST" action="{{ route('medical_payments.store') }}">
                @csrf

                <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">

                <div class="row">

                    <div class="col-md-3">
                        <input type="number" step="0.01" name="cost" class="form-control" placeholder="Costo atención Bs" required>
                    </div>

                    <div class="col-md-3">
                        <select name="doctor_id" class="form-control">
                            <option value="{{ $consultation->appointment->doctor->id }}">
                                {{ $consultation->appointment->doctor->nombres }}
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <input type="number" id="pn" name="percentage_newton" class="form-control" placeholder="% Newton">
                    </div>

                    <div class="col-md-2">
                        <input type="number" id="pd" name="percentage_doctor" class="form-control" placeholder="% Doctor">
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-success">Guardar</button>
                        <a href="{{ route('consultations.index') }}" class="btn btn-secondary">Volver</a>
                    </div>

                </div>

            </form>

        </div>
    </div>

    <!-- LISTADO -->
    <div class="card">
        <div class="card-body p-0">

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Costo</th>
                        <th>Doctor</th>
                        <th>% Newton</th>
                        <th>% Doctor</th>
                        <th>Newton</th>
                        <th>Doctor</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($consultation->medicalPayments as $p)
                        <tr>
                            <td>{{ $p->cost }}</td>
                            <td>{{ $p->doctor->nombres }}</td>
                            <td>{{ $p->percentage_newton }}%</td>
                            <td>{{ $p->percentage_doctor }}%</td>
                            <td>{{ $p->cost_newton }}</td>
                            <td>{{ $p->cost_doctor }}</td>
                            <td>
                                @if($p->paid)
                                    <span class="badge bg-success">Pagado</span>
                                @else
                                    <span class="badge bg-danger">Pendiente</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>

</div>

{{-- SCRIPT AUTOMÁTICO --}}
<script>
    const pn = document.getElementById('pn');
    const pd = document.getElementById('pd');

    pn.addEventListener('input', () => {
        let v = parseFloat(pn.value);
        if (!isNaN(v)) pd.value = (100 - v).toFixed(2);
    });

    pd.addEventListener('input', () => {
        let v = parseFloat(pd.value);
        if (!isNaN(v)) pn.value = (100 - v).toFixed(2);
    });
</script>

@endsection