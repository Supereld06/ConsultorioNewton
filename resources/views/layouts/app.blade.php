<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Consultorio Newton') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootswatch Sketchy -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/sketchy/bootstrap.min.css" rel="stylesheet">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="">

    <div class="d-flex">

        <!-- SIDEBAR -->

        <div class="sidebar p-3 d-flex flex-column">

            <!-- LOGO -->
            <div class="text-center mb-3">

                <img src="{{ asset('img/logo.png') }}" class="sidebar-logo">
                <br>
                <h5 class="mt-2">Usuario:</h5>

                <p class="user-name">
                    {{ Auth::user()->name }}
                </p>

            </div>

            <hr>

            <!-- MENÚ -->

            <ul class="nav flex-column flex-grow-1">

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/dashboard') }}">
                        🏠 Panel de Control
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('patients.index') }}">
                        👨 Pacientes
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('doctors.index') }}">
                        👨‍⚕️ Doctores
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('appointments.index') }}">
                        📅 Citas Médicas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('consultations.index') }}">
                        🩺 Consultas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        📊 Reportes
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        💲 Pago Consulta Medica
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        💲 Pago Laboratorio
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        💲 Pago Imagenologia
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        💲 Pago Otros
                    </a>
                </li>

            </ul>

            <!-- CERRAR SESIÓN -->

            <div class="mt-auto">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button class="btn btn-danger-soft w-100">
                        🚪 Cerrar sesión
                    </button>

                </form>

            </div>

        </div>

        <!-- CONTENIDO -->

        <div class="flex-grow-1">

            <main class="p-4 main-content">

                @yield('content')

            </main>

        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>