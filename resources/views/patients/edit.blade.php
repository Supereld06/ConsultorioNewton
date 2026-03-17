@extends('layouts.app')

@section('content')

    <div class="container">

        <h3 class="mb-4">Editar Paciente</h3>

        <form action="{{ route('patients.update', $patient->id) }}" method="POST" enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Apellidos</label>
                    <input type="text" name="apellidos" class="form-control" value="{{ $patient->apellidos }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Nombres</label>
                    <input type="text" name="nombres" class="form-control" value="{{ $patient->nombres }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label>Fecha nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control"
                        value="{{ $patient->fecha_nacimiento }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Carnet identidad</label>
                    <input type="text" name="ci" class="form-control" value="{{ $patient->ci }}">
                </div>

                <div class="col-md-4 mb-3">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ $patient->telefono }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Fotografía</label>
                    <input type="file" name="fotografia" class="form-control">
                </div>

                <div class="col-md-6 mb-3">

                    @if($patient->fotografia)

                        <img src="{{ asset('storage/' . $patient->fotografia) }}" width="100" class="rounded">

                    @endif

                </div>

            </div>

            <button class="btn btn-success">
                Actualizar Paciente
            </button>

            <a href="{{ route('patients.index') }}" class="btn btn-secondary">
                Cancelar
            </a>

        </form>

    </div>

@endsection