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

<body class="font-sans antialiased app-background">

    <div class="d-flex">

        <!-- SIDEBAR -->

        <div class="sidebar p-3 d-flex flex-column">

            <!-- LOGO -->
            <div class="text-center mb-3">

                <img src="{{ asset('img/logo.jpeg') }}" class="sidebar-logo">

                <h5 class="mt-2">Consultorio Newton</h5>

                <p class="user-name">
                    {{ Auth::user()->name }}
                </p>

            </div>

            <hr>

            <!-- MENÚ -->

            <ul class="nav flex-column flex-grow-1">

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/dashboard') }}">
                        🏠 Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        👨 Pacientes
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        👨‍⚕️ Doctores
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        📅 Citas Médicas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        🩺 Consultas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        📋 Historial Clínico
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        💊 Recetas
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        📊 Reportes
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        📄 Recibos
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

            <!-- NAVBAR SUPERIOR -->

            <nav class="navbar shadow-sm navbar-glass">

                <div class="container-fluid">

                    <span class="navbar-brand mb-0 h5">

                        {{ $header ?? 'Panel de control' }}

                    </span>

                </div>

            </nav>

            <!-- CONTENIDO PRINCIPAL -->

            <main class="p-4 main-content">

                {{ $slot }}

            </main>

        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>