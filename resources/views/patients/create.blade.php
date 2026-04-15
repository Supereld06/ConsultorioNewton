@extends('layouts.app')

@section('content')

    <div class="container">

        <h3>Registrar Paciente</h3>

        <form method="POST" action="{{ route('patients.store') }}" enctype="multipart/form-data">

            @csrf

            <div class="row">

                <div class="col-md-6">
                    <label>Apellidos</label>
                    <input type="text" name="apellidos" class="form-control" oninput="this.value = this.value.toUpperCase();" >
                </div>

                <div class="col-md-6">
                    <label>Nombres</label>
                    <input type="text" name="nombres" class="form-control" oninput="this.value = this.value.toUpperCase();" >
                </div>

                <div class="col-md-4 mt-3">
                    <label>Fecha nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control">
                </div>

                <div class="col-md-4 mt-3">
                    <label>Carnet identidad</label>
                    <input type="text" name="ci" class="form-control" oninput="this.value = this.value.toUpperCase();" >
                </div>

                <div class="col-md-4 mt-3">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" class="form-control" oninput="this.value = this.value.toUpperCase();" >
                </div>

                <div class="col-md-6 mt-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                </div>

                <div class="col-md-6 mt-3">
                    <label>Fotografía</label>
                    <input type="file" name="fotografia" class="form-control">
                </div>

            </div>

            <button class="btn btn-success mt-3">
                Guardar
            </button>

            <button type="button" class="btn btn-secondary mt-3" onclick="window.history.back()">
                Cancelar
            </button>

        </form>

    </div>

@endsection