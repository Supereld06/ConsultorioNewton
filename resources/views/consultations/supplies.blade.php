@extends('layouts.app')

@section('content')

    <div class="container">

        <h4 class="mb-3">Gestión de Insumos</h4>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- FORMULARIO -->
        <div class="card mb-3">
            <div class="card-body">

                <form method="POST" action="{{ route('supplies.store') }}">
                    @csrf

                    <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">

                    <div class="row">

                        <div class="col-md-3">
                            <input type="text" name="name" class="form-control" placeholder="Insumo" required oninput="this.value = this.value.toUpperCase();">
                        </div>

                        <div class="col-md-2">
                            <input type="number" step="0.01" name="cost" class="form-control" placeholder="Costo" required>
                        </div>

                        <div class="col-md-2">
                            <input type="number" id="percentage_newton" name="percentage_newton" class="form-control"
                                placeholder="% Newton" required>
                        </div>

                        <div class="col-md-2">
                            <input type="number" id="percentage_clinic" name="percentage_clinic" class="form-control"
                                placeholder="% Consultorio" required>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-success">Guardar</button>
                            <a href="{{ route('consultations.index') }}" class="btn btn-secondary">
                                Atrás
                            </a>
                        </div>




                    </div>

                </form>

            </div>
        </div>

        <!-- LISTA -->
        <div class="card">
            <div class="card-body p-0">

                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Insumo</th>
                            <th>Costo</th>
                            <th>% Newton</th>
                            <th>% Consultorio</th>
                            <th>Newton</th>
                            <th>Consultorio</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($consultation->supplies as $s)
                            <tr>
                                <td>{{ $s->name }}</td>
                                <td>{{ $s->cost }}</td>
                                <td>{{ $s->percentage_newton }}%</td>
                                <td>{{ $s->percentage_clinic }}%</td>
                                <td>{{ $s->cost_newton }}</td>
                                <td>{{ $s->cost_clinic }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>

    </div>

    <script>
        const newton = document.getElementById('percentage_newton');
        const clinic = document.getElementById('percentage_clinic');

        newton.addEventListener('input', function () {
            let value = parseFloat(this.value);

            if (!isNaN(value)) {
                if (value > 100) value = 100;
                if (value < 0) value = 0;

                this.value = value;
                clinic.value = (100 - value).toFixed(2);
            } else {
                clinic.value = '';
            }
        });

        clinic.addEventListener('input', function () {
            let value = parseFloat(this.value);

            if (!isNaN(value)) {
                if (value > 100) value = 100;
                if (value < 0) value = 0;

                this.value = value;
                newton.value = (100 - value).toFixed(2);
            } else {
                newton.value = '';
            }
        });
    </script>
@endsection