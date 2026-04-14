<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login | Consultorio Newton</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootswatch Sketchy -->
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/sketchy/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/app.css'])
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="login-page">

    <div class="login-container">

        <div class="card shadow p-4">

            <div class="text-center mb-3">

                <div class="sidebar-logo-container">
                    <img src="{{ asset('img/logo.png') }}" class="logo">
                </div>

                <p class="text-muted">
                    Sistema de Gestión Médica
                </p>

            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">

                    <label class="form-label">Correo electrónico</label>

                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>

                    @error('email')
                        <div class="text-danger mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <!-- Password -->
                <div class="mb-3">

                    <label class="form-label">Contraseña</label>

                    <input type="password" name="password" class="form-control" required>

                    @error('password')
                        <div class="text-danger mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <!-- Remember -->
                <div class="form-check mb-3">

                    <input class="form-check-input" type="checkbox" name="remember">

                    <label class="form-check-label">
                        Recordarme
                    </label>

                </div>

                <!-- Botón login -->
                <div class="d-grid mb-3">

                    <button type="submit" class="btn btn-primary">
                        Iniciar Sesión
                    </button>

                </div>

                <!-- Forgot password -->
                @if (Route::has('password.request'))

                    <div class="text-center">

                        <a href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>

                    </div>

                @endif

            </form>

        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>