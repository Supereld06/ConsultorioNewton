<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Consulta Médica</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            margin-bottom: 5px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            color: #0d6efd;
        }

        .info {
            margin-bottom: 15px;
        }

        .info strong {
            display: inline-block;
            width: 120px;
        }

        .section {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #0d6efd;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
        }

        .firma {
            margin-top: 50px;
        }

        .linea {
            border-top: 1px solid #000;
            width: 200px;
            margin: auto;
        }
    </style>
</head>

<body>

    {{-- 🔷 ENCABEZADO --}}
    <div class="header">

        {{-- 🔥 CAMBIA ESTA RUTA POR TU LOGO --}}
        <img src="img/logo.jpeg" class="logo">

        <div class="title">CONSULTORIO NEWTON</div>
        <div>Consulta Médica</div>

    </div>

    {{-- 🔷 INFORMACIÓN --}}
    <div class="info">
        <strong>Paciente:</strong>
        {{ $consultation->appointment->patient->nombres }} {{ $consultation->appointment->patient->apellidos }}<br>

        <strong>Doctor:</strong>
        {{ $consultation->appointment->doctor->nombres }} {{ $consultation->appointment->doctor->apellidos }}<br>

        <strong>Fecha:</strong>
        {{ $consultation->appointment->fecha }}<br>

        <strong>Hora:</strong>
        {{ $consultation->appointment->hora }}
    </div>

    {{-- 🔷 DIAGNÓSTICO --}}
    <div class="section">
        <div class="section-title">Diagnóstico</div>
        {{ $consultation->diagnostico }}
    </div>

    {{-- 🔷 TRATAMIENTO --}}
    <div class="section">
        <div class="section-title">Tratamiento</div>
        {{ $consultation->tratamiento }}
    </div>

    {{-- 🔷 RECETA --}}
    <div class="section">
        <div class="section-title">Receta Médica</div>
        {{ $consultation->receta }}
    </div>

    {{-- 🔷 OBSERVACIONES --}}
    <div class="section">
        <div class="section-title">Observaciones</div>
        {{ $consultation->observaciones }}
    </div>

    {{-- 🔷 FIRMA --}}
    <div class="footer">
        <div class="firma">
            <div class="linea"></div>
            <div>Firma del Doctor</div>
        </div>
    </div>

</body>

</html>