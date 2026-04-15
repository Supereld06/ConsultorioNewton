<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Consulta Médica</title>

    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 11px;
            color: #2c3e50;
            background: #ffffff;
            page-break-after: auto;
        }

        /* 🔷 ENCABEZADO */
        .header {
            text-align: center;
            border-bottom: 3px solid #0a2540;
            padding-bottom: 12px;
            margin-bottom: 18px;
            position: relative;
        }

        .logo {
            width: 100px;
            /* 🔥 más grande */
            margin-bottom: 5px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 18px;
            color: #0a2540;
            letter-spacing: 1px;
        }

        /* 🔷 INFORMACIÓN */
        .info {
            margin-bottom: 12px;
            padding: 10px;
            border-radius: 10px;
            background: #f4f7fb;
            border-left: 4px solid #0a2540;
        }

        .info strong {
            display: inline-block;
            width: 150px;
            color: #0a2540;
        }

        /* 🔷 SECCIONES */
        .section {
            margin-top: 12px;
            padding: 12px;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid #dce3ec;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        /* 🔥 detalle decorativo curvo */
        .section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 60px;
            height: 60px;
            background: #0a2540;
            border-bottom-right-radius: 60px;
            opacity: 0.08;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 6px;
            color: #0a2540;
            font-size: 13px;
            letter-spacing: 1px;
        }

        /* 🔥 MARCA DE AGUA */
        .watermark {
            position: fixed;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.05;
            z-index: -1;
        }

        .watermark img {
            width: 340px;
            /* 🔥 más grande */
        }

        /* 🔷 FIRMA */
        .firma {
            margin-top: 50px;
            text-align: center;
        }

        .linea {
            border-top: 1.5px solid #0a2540;
            width: 220px;
            margin: auto;
        }

        .firma div:last-child {
            margin-top: 5px;
            font-size: 11px;
            color: #0a2540;
        }

        /* 🔷 FOOTER */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #4a5a6a;
        }

        .footer hr {
            border: none;
            border-top: 1px solid #0a2540;
            margin-bottom: 6px;
            opacity: 0.4;
        }

        .footer p {
            margin: 2px 0;
        }

        /* 🔥 líneas decorativas de página */
        body::before {
            content: "";
            position: fixed;
            top: -50px;
            left: -50px;
            width: 200px;
            height: 200px;
            border: 3px solid #0a2540;
            border-radius: 50%;
            opacity: 0.05;
        }

        body::after {
            content: "";
            position: fixed;
            bottom: -60px;
            right: -60px;
            width: 220px;
            height: 220px;
            border: 3px solid #0a2540;
            border-radius: 50%;
            opacity: 0.05;
        }

        .section {
    margin-top: 12px;
    padding: 12px;
    border-radius: 12px;
    background: #ffffff;
    border: 1px solid #dce3ec;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    position: relative;
    page-break-inside: auto;


    /* 🔥 CLAVE */
    page-break-inside: avoid;
    overflow: visible;
    word-wrap: break-word;
}

.section p {
    margin: 0;
    text-align: justify;

    /* 🔥 evita que se corte */
    white-space: normal;
    word-break: break-word;
}
    </style>
</head>

<body>

    {{-- 🔥 MARCA DE AGUA --}}
    <div class="watermark">
        <img src="{{ public_path('img/logo.jpeg') }}">
    </div>

    {{-- 🔷 ENCABEZADO --}}
    <div class="header">
        <img src="{{ public_path('img/logo.jpeg') }}" class="logo">

        <h2>Receta Medica</h2>
    </div>

    {{-- 🔷 INFORMACIÓN --}}
    <div class="info">
        <strong>Paciente:</strong>
        {{ $consultation->appointment->patient->nombres }}
        {{ $consultation->appointment->patient->apellidos }}<br>

        <strong>Doctor:</strong>
        {{ $consultation->appointment->doctor->nombres }}
        {{ $consultation->appointment->doctor->apellidos }}<br>

        <strong>Fecha de Consulta:</strong>
        {{ $consultation->appointment->fecha }}<br>

        <strong>Hora de Consulta:</strong>
        {{ $consultation->appointment->hora }}
    </div>

    {{-- 🔷 TRATAMIENTO --}}
    <div class="section">
        <div class="section-title">TRATAMIENTO</div>
        <p>{{ $consultation->tratamiento }}</p>
    </div>

    {{-- 🔷 RECETA --}}
    <div class="section">
        <div class="section-title">RECETA MÉDICA</div>
        <p>{{ $consultation->receta }}</p>
    </div>

    {{-- 🔷 OBSERVACIONES --}}
    <div class="section">
        <div class="section-title">OBSERVACIONES</div>
        <p>{{ $consultation->observaciones }}</p>
    </div>

    {{-- 🔷 FIRMA --}}
    <div class="firma">
        <div class="linea"></div>
        <div>Firma del Doctor</div>
    </div>

    {{-- 🔷 FOOTER --}}
    <div class="footer">
        <hr>
        <p>Direccion: M. Ricardo Terrazas #1067 entre Benjamín Blanco y Medizabal</p>
        <p>Telefono: 68574372</p>
        <p>TikTok: consultorio_mediconewton</p>
    </div>

</body>

</html>