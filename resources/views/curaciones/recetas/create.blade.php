@extends('layouts.app')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3>
                    <i class="bi bi-file-medical"></i>
                    Nueva Receta
                </h3>

                <small class="text-muted">
                    Curación {{ $curacion->codigo }}
                </small>

            </div>


            <a href="{{ route('curaciones.show', $curacion->id) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Volver
            </a>

        </div>


        @if($errors->any())

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <div class="card shadow">

            <div class="card-header">

                <strong>
                    Datos de la receta
                </strong>

            </div>


            <div class="card-body">

                <div class="row mb-4">

                    <div class="col-md-6">

                        <label class="form-label">
                            Paciente
                        </label>

                        <input type="text" class="form-control" readonly value="{{ optional(
        optional(
            $curacion->consultation->appointment
        )->patient
    )->nombre }}
                            {{ optional(
        optional(
            $curacion->consultation->appointment
        )->patient
    )->apellido }}">

                    </div>


                    <div class="col-md-6">

                        <label class="form-label">
                            Médico
                        </label>

                        <input type="text" class="form-control" readonly value="{{ optional(
        optional(
            $curacion->consultation->appointment
        )->doctor
    )->nombre }}
                            {{ optional(
        optional(
            $curacion->consultation->appointment
        )->doctor
    )->apellido }}">

                    </div>

                </div>


                <form action="{{ route(
        'curaciones.receta.store',
        $curacion->id
    ) }}" method="POST">

                    @csrf


                    <div class="mb-4">

                        <label class="form-label">
                            Indicaciones generales
                        </label>

                        <textarea name="indicaciones" class="form-control" rows="3"
                            placeholder="Indicaciones generales para el paciente..."></textarea>

                    </div>


                    <h5>
                        Medicamentos
                    </h5>


                    <div class="table-responsive">

                        <table class="table table-bordered">

                            <thead>

                                <tr>

                                    <th>Medicamento</th>
                                    <th>Dosis</th>
                                    <th>Frecuencia</th>
                                    <th>Duración</th>
                                    <th>Indicaciones</th>
                                    <th width="50"></th>

                                </tr>

                            </thead>


                            <tbody id="medicamentos">

                                <tr>

                                    <td>

                                        <input type="text" name="detalles[0][medicamento]" class="form-control" required>

                                    </td>


                                    <td>

                                        <input type="text" name="detalles[0][dosis]" class="form-control"
                                            placeholder="Ej. 500 mg">

                                    </td>


                                    <td>

                                        <input type="text" name="detalles[0][frecuencia]" class="form-control"
                                            placeholder="Ej. cada 8 horas">

                                    </td>


                                    <td>

                                        <input type="text" name="detalles[0][duracion]" class="form-control"
                                            placeholder="Ej. 5 días">

                                    </td>


                                    <td>

                                        <input type="text" name="detalles[0][indicaciones]" class="form-control">

                                    </td>


                                    <td>

                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="eliminarMedicamento(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </td>

                                </tr>

                            </tbody>

                        </table>

                    </div>


                    <button type="button" class="btn btn-outline-primary mb-4" onclick="agregarMedicamento()">

                        <i class="bi bi-plus-circle"></i>
                        Agregar medicamento

                    </button>


                    <div class="d-flex justify-content-end">

                        <button type="submit" class="btn btn-success">

                            <i class="bi bi-save"></i>
                            Registrar receta

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <script>

        let indiceMedicamento = 1;

        function agregarMedicamento() {
            const tbody =
                document.getElementById('medicamentos');

            const fila =
                document.createElement('tr');

            fila.innerHTML = `

            <td>
                <input
                    type="text"
                    name="detalles[${indiceMedicamento}][medicamento]"
                    class="form-control"
                    required
                >
            </td>

            <td>
                <input
                    type="text"
                    name="detalles[${indiceMedicamento}][dosis]"
                    class="form-control"
                >
            </td>

            <td>
                <input
                    type="text"
                    name="detalles[${indiceMedicamento}][frecuencia]"
                    class="form-control"
                >
            </td>

            <td>
                <input
                    type="text"
                    name="detalles[${indiceMedicamento}][duracion]"
                    class="form-control"
                >
            </td>

            <td>
                <input
                    type="text"
                    name="detalles[${indiceMedicamento}][indicaciones]"
                    class="form-control"
                >
            </td>

            <td>
                <button
                    type="button"
                    class="btn btn-danger btn-sm"
                    onclick="eliminarMedicamento(this)"
                >
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

            tbody.appendChild(fila);

            indiceMedicamento++;
        }


        function eliminarMedicamento(button) {
            const filas =
                document.querySelectorAll(
                    '#medicamentos tr'
                );

            if (filas.length <= 1) {

                alert(
                    'Debe existir al menos un medicamento.'
                );

                return;
            }

            button.closest('tr').remove();
        }

    </script>

@endsection