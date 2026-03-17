@extends('layouts.app')

@section('content')

    <div class="container">

        <h3 class="mb-4">📅 Nueva Cita Inteligente</h3>

        <form action="{{ route('appointments.store') }}" method="POST">

            @csrf

            <div class="row">

                <!-- PACIENTE -->
                <div class="col-md-6 mb-3">
                    <label>Paciente</label>
                    <select name="patient_id" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}">
                                {{ $p->nombres }} {{ $p->apellidos }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- DOCTOR -->
                <div class="col-md-6 mb-3">
                    <label>Doctor</label>
                    <select name="doctor_id" id="doctor" class="form-control" required>
                        <option value="">Seleccione</option>
                        @foreach($doctors as $d)
                            <option value="{{ $d->id }}">
                                {{ $d->nombres }} {{ $d->apellidos }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- FECHA -->
                <div class="col-md-6 mb-3">
                    <label>Fecha</label>
                    <input type="date" id="fecha" name="fecha" class="form-control" required>
                </div>

                <!-- HORAS -->
                <div class="col-md-12 mb-3">
                    <label>Seleccionar Hora</label>

                    <div id="horas-container" class="d-flex flex-wrap gap-2">
                        <span class="text-muted">Seleccione doctor y fecha</span>
                    </div>

                    <input type="hidden" name="hora" id="horaSeleccionada">
                </div>

                <!-- OBS -->
                <div class="col-md-12 mb-3">
                    <label>Observaciones</label>
                    <textarea name="observaciones" class="form-control"></textarea>
                </div>

            </div>

            <button class="btn btn-success">Guardar</button>

            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Cancelar</a>

        </form>

    </div>

    <script>
        document.getElementById('doctor').addEventListener('change', cargarHoras);
        document.getElementById('fecha').addEventListener('change', cargarHoras);

        function cargarHoras() {

            let doctor = document.getElementById('doctor').value;
            let fecha = document.getElementById('fecha').value;

            if (!doctor || !fecha) return;

            fetch(`/horarios-disponibles?doctor_id=${doctor}&fecha=${fecha}`)
                .then(res => res.json())
                .then(data => {

                    let container = document.getElementById('horas-container');
                    container.innerHTML = '';

                    data.forEach(item => {

                        let btn = document.createElement('button');
                        btn.type = 'button';
                        btn.innerText = item.hora;

                        btn.classList.add('btn', 'btn-sm');

                        if (item.ocupado) {
                            btn.classList.add('btn-danger');
                            btn.disabled = true;
                        } else {
                            btn.classList.add('btn-success');

                            btn.onclick = function () {
                                seleccionarHora(item.hora, btn);
                            };
                        }

                        container.appendChild(btn);
                    });

                });
        }

        // Selección visual
        function seleccionarHora(hora, boton) {

            document.getElementById('horaSeleccionada').value = hora;

            // reset
            document.querySelectorAll('#horas-container button').forEach(b => {
                if (!b.disabled) b.classList.remove('btn-primary');
                if (!b.disabled) b.classList.add('btn-success');
            });

            // marcar seleccionado
            boton.classList.remove('btn-success');
            boton.classList.add('btn-primary');
        }
    </script>

@endsection