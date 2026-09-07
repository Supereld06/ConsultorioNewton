<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <title>{{ $ingreso->codigo }}</title>

    <style>
        @page {
            margin: 15px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #2c3e50;
            margin: 0;
            background: #ffffff;
        }

        .documento {
            width: 100%;
        }


        /* ==========================================
           MARCA DE AGUA
           ========================================== */

        .watermark {
            position: fixed;
            top: 42%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.04;
        }

        .watermark img {
            width: 300px;
        }


        /* ==========================================
           ENCABEZADO
           ========================================== */

        .header {
            text-align: center;
            border-bottom: 2px solid #0a2540;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .logo {
            width: 85px;
            margin-bottom: 3px;
        }

        .consultorio {
            font-size: 11px;
            font-weight: bold;
            color: #0a2540;
            margin: 2px 0;
        }

        .header h2 {
            margin: 5px 0 2px;
            font-size: 17px;
            color: #0a2540;
            letter-spacing: 1px;
        }

        .header p {
            margin: 2px 0;
            color: #4a5a6a;
            font-size: 8px;
        }


        /* ==========================================
           INFORMACIÓN
           ========================================== */

        .info {
            margin-bottom: 12px;
            padding: 8px;
            background: #f4f7fb;
            border-left: 4px solid #0a2540;
        }

        .info strong {
            display: inline-block;
            width: 105px;
            color: #0a2540;
        }


        /* ==========================================
           SECCIÓN
           ========================================== */

        .section {
            margin-top: 12px;
            padding: 8px;
            border: 1px solid #dce3ec;
            background: #ffffff;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: #0a2540;
            font-size: 11px;
            letter-spacing: 1px;
        }


        /* ==========================================
           TABLA DE DETALLE
           ========================================== */

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #0a2540;
            color: white;
            border: 1px solid #0a2540;
            padding: 6px 5px;
            text-align: left;
            font-size: 9px;
        }

        td {
            border-bottom: 1px solid #dce3ec;
            padding: 6px 5px;
            font-size: 9px;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }


        /* ==========================================
           RESUMEN
           ========================================== */

        .resumen {
            margin-top: 12px;
            width: 48%;
            margin-left: auto;
        }

        .resumen td {
            border: none;
            padding: 4px;
            font-size: 9px;
        }

        .total-final {
            border-top: 2px solid #0a2540 !important;
            font-size: 13px !important;
            font-weight: bold;
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
            margin-top: 12px;
            padding: 8px;
            background: #f4f7fb;
            border-left: 4px solid #0a2540;
            font-size: 9px;
        }

        .observacion strong {
            color: #0a2540;
        }

        .firmas {
            width: 100%;
            margin-top: 45px;
        }

        .firma {
            width: 44%;
            display: inline-block;
            text-align: center;
        }

        .espacio {
            width: 9%;
            display: inline-block;
        }

        .linea {
            border-top: 1px solid #0a2540;
            width: 150px;
            margin: auto;
        }

        .firma-titulo {
            margin-top: 4px;
            font-weight: bold;
            color: #0a2540;
            font-size: 9px;
        }

        .firma-subtitulo {
            margin-top: 2px;
            font-size: 8px;
            color: #4a5a6a;
        }

        /* ==========================================
           FOOTER
           ========================================== */

        .footer {
            margin-top: 25px;
            text-align: center;
            font-size: 8px;
            color: #4a5a6a;
            border-top: 1px solid #0a2540;
            padding-top: 6px;
        }

        .footer p {
            margin: 2px 0;
        }
    </style>

</head>


<body>

    <div class="documento">


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
        INFORMACIÓN
        ========================================== --}}

        <div class="info">

            <strong>
                N° de Ingreso:
            </strong>

            {{ $ingreso->codigo }}

            <br>

            <strong>
                Fecha:
            </strong>

            {{ $ingreso->fecha->format('d/m/Y') }}

            <br>

            <strong>
                Proveedor:
            </strong>

            {{ $ingreso->proveedor ?: 'Sin proveedor' }}

            <br>

            <strong>
                Registrado por:
            </strong>

            {{ $ingreso->usuario->name ?? 'N/A' }}

        </div>


        {{-- ==========================================
        DETALLE
        ========================================== --}}

        <div class="section">

            <div class="section-title">
                DETALLE DE INSUMOS
            </div>

            <table>

                <thead>

                    <tr>

                        <th class="center">
                            #
                        </th>

                        <th>
                            Código
                        </th>

                        <th>
                            Insumo
                        </th>

                        <th class="center">
                            Cant.
                        </th>

                        <th class="right">
                            P. Compra
                        </th>

                        <th class="right">
                            P. Venta
                        </th>

                        <th class="right">
                            Subtotal
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($ingreso->detalles as $index => $detalle)

                        <tr>

                            <td class="center">
                                {{ $index + 1 }}
                            </td>

                            <td>
                                {{ $detalle->insumo->codigo ?? '-' }}
                            </td>

                            <td>
                                {{ $detalle->insumo->nombre ?? '-' }}
                            </td>

                            <td class="center">
                                {{ number_format($detalle->cantidad, 2) }}
                            </td>

                            <td class="right">
                                Bs.
                                {{ number_format($detalle->precio_compra, 2) }}
                            </td>

                            <td class="right">
                                Bs.
                                {{ number_format($detalle->precio_venta, 2) }}
                            </td>

                            <td class="right">
                                Bs.
                                {{ number_format($detalle->subtotal, 2) }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- ==========================================
        TOTALES
        ========================================== --}}

        <table class="resumen">

            <tr>

                <td>
                    <strong>
                        Total:
                    </strong>
                </td>

                <td class="right">
                    Bs.
                    {{ number_format($ingreso->total, 2) }}
                </td>

            </tr>


            <tr>

                <td class="pagado">
                    <strong>
                        Monto pagado:
                    </strong>
                </td>

                <td class="right pagado">
                    Bs.
                    {{ number_format($ingreso->monto_pagado, 2) }}
                </td>

            </tr>


            <tr>

                <td class="total-final">
                    SALDO PENDIENTE:
                </td>

                <td class="right total-final pendiente">
                    Bs.
                    {{ number_format($ingreso->saldo_pendiente, 2) }}
                </td>

            </tr>

        </table>


        {{-- ==========================================
        OBSERVACIÓN
        ========================================== --}}

        @if($ingreso->observacion)

            <div class="observacion">

                <strong>
                    Observación:
                </strong>

                {{ $ingreso->observacion }}

            </div>

        @endif


        {{-- ==========================================
        FIRMAS
        ========================================== --}}

        <div class="firmas">

            <div class="firma">

                <div class="linea"></div>

                <div class="firma-titulo">
                    Entregado por
                </div>

                <div class="firma-subtitulo">
                    Responsable de la entrega
                </div>

            </div>


            <div class="espacio">
                &nbsp;
            </div>


            <div class="firma">

                <div class="linea"></div>

                <div class="firma-titulo">
                    Recibido por
                </div>

                <div class="firma-subtitulo">
                    Responsable de la recepción
                </div>

            </div>

        </div>


        {{-- ==========================================
        FOOTER
        ========================================== --}}

        <div class="footer">

            <p>
                Dirección: M. Ricardo Terrazas #1067 entre Benjamín Blanco y Medizabal
            </p>

            <p>
                Teléfono: 68574372
            </p>

            <p>
                TikTok: @consultorio_mediconewton
            </p>

            <p>
                Gracias por su preferencia
            </p>

        </div>


    </div>

</body>

</html>