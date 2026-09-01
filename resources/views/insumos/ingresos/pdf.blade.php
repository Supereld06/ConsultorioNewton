
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <title>{{ $ingreso->codigo }}</title>

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


        /* ==========================================
           ENCABEZADO
        ========================================== */

        .header {
            text-align: center;
            border-bottom: 3px solid #0a2540;
            padding-bottom: 12px;
            margin-bottom: 18px;
            position: relative;
        }

        .logo {
            width: 100px;
            margin-bottom: 5px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 18px;
            color: #0a2540;
            letter-spacing: 1px;
        }

        .header p {
            margin: 3px 0;
            font-size: 11px;
            color: #4a5a6a;
        }


        /* ==========================================
           INFORMACIÓN
        ========================================== */

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


        /* ==========================================
           SECCIONES
        ========================================== */

        .section {
            margin-top: 12px;
            padding: 12px;
            border-radius: 12px;
            background: #ffffff;
            border: 1px solid #dce3ec;
            position: relative;
            overflow: visible;
            page-break-inside: avoid;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: #0a2540;
            font-size: 13px;
            letter-spacing: 1px;
        }


        /* ==========================================
           TABLA DE INSUMOS
        ========================================== */

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .table th {
            background: #0a2540;
            color: #ffffff;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }

        .table td {
            padding: 7px;
            border-bottom: 1px solid #dce3ec;
            font-size: 10px;
        }

        .table tr {
            page-break-inside: avoid;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }


        /* ==========================================
           RESUMEN
        ========================================== */

        .resumen {
            width: 100%;
            margin-top: 15px;
        }

        .resumen-box {
            width: 45%;
            margin-left: auto;
            border: 1px solid #dce3ec;
            border-radius: 10px;
            padding: 10px;
        }

        .resumen-row {
            width: 100%;
            padding: 5px 0;
        }

        .resumen-label {
            display: inline-block;
            width: 55%;
        }

        .resumen-value {
            display: inline-block;
            width: 40%;
            text-align: right;
            font-weight: bold;
        }

        .total {
            font-size: 14px;
            color: #0a2540;
        }

        .pagado {
            color: #198754;
        }

        .pendiente {
            color: #dc3545;
        }


        /* ==========================================
           OBSERVACIÓN
        ========================================== */

        .observacion {
            margin-top: 15px;
            padding: 10px;
            border-radius: 10px;
            background: #f4f7fb;
            border-left: 4px solid #0a2540;
        }

        .observacion-title {
            font-weight: bold;
            color: #0a2540;
            margin-bottom: 5px;
        }


        /* ==========================================
           MARCA DE AGUA
        ========================================== */

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
        }


        /* ==========================================
           DECORACIÓN
        ========================================== */

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


        /* ==========================================
           FOOTER
        ========================================== */

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
    </style>

</head>


<body>


    {{-- ==========================================
    MARCA DE AGUA
    ========================================== --}}

    <div class="watermark">

        <img src="{{ public_path('img/logo.jpeg') }}">

    </div>


    {{-- ==========================================
    ENCABEZADO
    ========================================== --}}

    <div class="header">

        <img src="{{ public_path('img/logo.jpeg') }}" class="logo">

        <h2>
            INGRESO DE INSUMOS
        </h2>

        <p>
            Documento de registro de ingreso
        </p>

    </div>


    {{-- ==========================================
    INFORMACIÓN DEL INGRESO
    ========================================== --}}

    <div class="info">

        <strong>Número de Ingreso:</strong>

        {{ $ingreso->codigo }}

        <br>


        <strong>Fecha de Ingreso:</strong>

        {{ $ingreso->fecha->format('d/m/Y') }}

        <br>


        <strong>Proveedor:</strong>

        {{ $ingreso->proveedor ?: 'Sin proveedor' }}

        <br>


        <strong>Registrado por:</strong>

        {{ $ingreso->usuario->name ?? 'N/A' }}

    </div>


    {{-- ==========================================
    DETALLE DE INSUMOS
    ========================================== --}}

    <div class="section">

        <div class="section-title">

            DETALLE DE INSUMOS

        </div>


        <table class="table">

            <thead>

                <tr>

                    <th class="text-center">
                        #
                    </th>

                    <th>
                        Código
                    </th>

                    <th>
                        Insumo
                    </th>

                    <th class="text-center">
                        Cant.
                    </th>

                    <th class="text-right">
                        P. Compra
                    </th>

                    <th class="text-right">
                        P. Venta
                    </th>

                    <th class="text-right">
                        Subtotal
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach($ingreso->detalles as $detalle)

                    <tr>

                        <td class="text-center">

                            {{ $loop->iteration }}

                        </td>


                        <td>

                            {{ $detalle->insumo->codigo ?? 'N/A' }}

                        </td>


                        <td>

                            {{ $detalle->insumo->nombre ?? 'Insumo eliminado' }}

                        </td>


                        <td class="text-center">

                            {{ number_format($detalle->cantidad, 2) }}

                        </td>


                        <td class="text-right">

                            Bs.
                            {{ number_format($detalle->precio_compra, 2) }}

                        </td>


                        <td class="text-right">

                            Bs.
                            {{ number_format($detalle->precio_venta, 2) }}

                        </td>


                        <td class="text-right">

                            <strong>

                                Bs.
                                {{ number_format($detalle->subtotal, 2) }}

                            </strong>

                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>


    {{-- ==========================================
    RESUMEN
    ========================================== --}}

    <div class="resumen">

        <div class="resumen-box">

            <div class="resumen-row">

                <span class="resumen-label">
                    Total:
                </span>

                <span class="resumen-value total">

                    Bs.
                    {{ number_format($ingreso->total, 2) }}

                </span>

            </div>


            <div class="resumen-row">

                <span class="resumen-label">
                    Monto pagado:
                </span>

                <span class="resumen-value pagado">

                    Bs.
                    {{ number_format($ingreso->monto_pagado, 2) }}

                </span>

            </div>


            <div class="resumen-row">

                <span class="resumen-label">
                    Saldo pendiente:
                </span>

                <span class="resumen-value pendiente">

                    Bs.
                    {{ number_format($ingreso->saldo_pendiente, 2) }}

                </span>

            </div>

        </div>

    </div>


    {{-- ==========================================
    OBSERVACIÓN
    ========================================== --}}

    @if($ingreso->observacion)

        <div class="observacion">

            <div class="observacion-title">

                OBSERVACIÓN

            </div>

            {{ $ingreso->observacion }}

        </div>

    @endif


    {{-- ==========================================
    FOOTER
    ========================================== --}}

    <div class="footer">

        <hr>

        <p>
            Direccion: M. Ricardo Terrazas #1067 entre Benjamín Blanco y Medizabal
        </p>

        <p>
            Telefono: 68574372
        </p>

        <p>
            TikTok: consultorio_mediconewton
        </p>

    </div>


</body>

</html>
