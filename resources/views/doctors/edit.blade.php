@extends('layouts.app')

@section('content')

    <div class="container mt-4">

        <div class="card shadow">

            <!-- HEADER -->
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">✏️ Editar Doctor</h4>
            </div>

            <div class="card-body">

                <!-- ERRORES -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Ups! Algo salió mal:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- FORM -->
                <form action="{{ route('doctors.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="row g-4">

                        <!-- FOTO ACTUAL -->
                        <div class="col-md-4 text-center">

                            <label class="form-label fw-bold">
                                Fotografía actual
                            </label>

                            <div class="mb-2">

                                @if($doctor->foto)
                                    <img src="{{ asset('storage/' . $doctor->foto) }}" width="140" height="140"
                                        style="object-fit:cover;border-radius:50%;border:3px solid #ccc;">
                                @else
                                    <div class="text-muted">
                                        Sin foto
                                    </div>
                                @endif

                            </div>

                        </div>

                        <!-- NUEVA FOTO -->
                        <div class="col-md-8">

                            <label class="form-label fw-bold">
                                Cambiar fotografía
                            </label>

                            <input type="file" name="foto" class="form-control" accept="image/*"
                                onchange="previewImage(event)">

                            <small class="text-muted">
                                Formatos permitidos: JPG, PNG (máx 2MB)
                            </small>

                            <div class="mt-3 text-center">

                                <img id="preview" width="140"
                                    style="display:none;border-radius:50%;border:2px dashed #aaa;">

                            </div>

                        </div>

                        <!-- APELLIDOS -->
                        <div class="col-md-6">

                            <label class="form-label">Apellidos</label>

                            <input type="text" name="apellidos" value="{{ old('apellidos', $doctor->apellidos) }}"
                                class="form-control" required>

                        </div>

                        <!-- NOMBRES -->
                        <div class="col-md-6">

                            <label class="form-label">Nombres</label>

                            <input type="text" name="nombres" value="{{ old('nombres', $doctor->nombres) }}"
                                class="form-control" required>

                        </div>

                        <!-- CI -->
                        <div class="col-md-4">

                            <label class="form-label">Carnet de Identidad</label>

                            <input type="text" name="ci" value="{{ old('ci', $doctor->ci) }}" class="form-control" required>

                        </div>

                        <!-- ESPECIALIDAD -->
                        <div class="col-md-4">

                            <label class="form-label">Especialidad</label>

                            <input type="text" name="especialidad" value="{{ old('especialidad', $doctor->especialidad) }}"
                                class="form-control" required>

                        </div>

                        <!-- TELÉFONO -->
                        <div class="col-md-4">

                            <label class="form-label">Teléfono</label>

                            <input type="text" name="telefono" value="{{ old('telefono', $doctor->telefono) }}"
                                class="form-control">

                        </div>

                        <!-- EMAIL -->
                        <div class="col-md-6">

                            <label class="form-label">Correo electrónico</label>

                            <input type="email" name="email" value="{{ old('email', $doctor->email) }}"
                                class="form-control">

                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Hora inicio</label>
                            <input type="time" name="hora_inicio" class="form-control"
                                value="{{ old('hora_inicio', $doctor->hora_inicio ?? '08:00') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Hora fin</label>
                            <input type="time" name="hora_fin" class="form-control"
                                value="{{ old('hora_fin', $doctor->hora_fin ?? '18:00') }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label>Duración cita (min)</label>
                            <input type="number" name="duracion_cita" class="form-control"
                                value="{{ old('duracion_cita', $doctor->duracion_cita ?? 30) }}">
                        </div>

                        <!-- BOTONES -->
                        <div class="col-12 mt-4">

                            <a href="{{ route('doctors.index') }}" class="btn btn-secondary">
                                ⬅ Volver
                            </a>

                            <button type="submit" class="btn btn-success">
                                💾 Actualizar Doctor
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <!-- SCRIPT PREVIEW -->
    <script>
        function previewImage(event) {
            const reader = new FileReader();

            reader.onload = function () {
                const output = document.getElementById('preview');
                output.src = reader.result;
                output.style.display = 'block';
            };

            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

@endsection