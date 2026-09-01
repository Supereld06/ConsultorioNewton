<!DOCTYPE html>

<html>

<head>

    
    <meta charset="utf-8">

    <title>{{ $salida->codigo }}</title>

    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 11px;
            color: #2c3e50;
            background: #ffffff;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #0a2540;
            padding-bottom: 12px;
            margin-bottom: 18px;
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
            margin: 4px 0;
            color: #4a5a6a;
        }

        .info {
            margin-bottom: 15px;
            padding: 10px;
            background: #f4f7fb;
            border-left: 4px solid #0a2540;
        }

        .info strong {
            display: inline-block;
            width: 130px;
            color: #0a2540;
        }

        .section {
            margin-top: 12px;
            padding: 12px;
            border-radius: 8px;
            background: #ffffff;
            border: 1px solid #dce3ec;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: #0a2540;
            font-size: 13px;
            letter-spacing: 1px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th {
            background: #0a2540;
            color: white;
            padding: 8px;
            text-align: left;
        }

        td {
            border-bottom: 1px solid #dce3ec;
            padding: 7px;
        }

        .text-right {
            text-align: right;
        }

        .totales {
            margin-top: 15px;
            width: 45%;
            margin-left: auto;
        }

        .totales table td {
            border: none;
            padding: 5px;
        }

        .total-final {
            font-size: 15px;
            font-weight: bold;
            color: #0a2540;
            border-top: 2px solid #0a2540 !important;
        }

        .firma {
            margin-top: 55px;
            text-align: center;
        }

        .linea {
            border-top: 1.5px solid #0a2540;
            width: 220px;
            margin: auto;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #4a5a6a;
        }

        .footer hr {
            border: none;
            border-top: 1px solid #0a2540;
            opacity: 0.4;
        }

        .watermark {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.04;
        }

        .watermark img {
            width: 340px;
        }
    </style>
    

</head>

<body>

    
    {{-- MARCA DE AGUA --}}

    <div class="watermark">

        <img src="{{ public_path('img/logo.jpeg') }}">

    </div>


    {{-- ENCABEZADO --}}

    <div class="header">

        <img src="{{ public_path('img/logo.jpeg') }}" class="logo">

        <h2>
            SALIDA DE INSUMOS
        </h2>

        <p>
            Documento de salida de insumos
        </p>

    </div>


    {{-- INFORMACIÓN --}}

    <div class="info">

        <strong>N° de Salida:</strong>
        {{ $salida->codigo }}

        <br>

        <strong>Fecha:</strong>
        {{ $salida->fecha->format('d/m/Y') }}

        <br>

        <strong>Motivo:</strong>
        {{ $salida->motivo ?: 'No especificado' }}

        <br>

        <strong>Usuario:</strong>
        {{ $salida->usuario->name ?? 'N/A' }}

    </div>


    {{-- DETALLE --}}

    <div class="section">

        <div class="section-title">
            DETALLE DE INSUMOS
        </div>


        <table>

            <thead>

                <tr>

                    <th>#</th>

                    <th>Código</th>

                    <th>Insumo</th>

                    <th>Cantidad</th>

                    <th>Precio Venta</th>

                    <th>Total</th>

                </tr>

            </thead>


            <tbody>

                @foreach($salida->detalles as $index => $detalle)

                    <tr>

                        <td>
                            {{ $index + 1 }}
                        </td>

                        <td>
                            {{ $detalle->insumo->codigo ?? '-' }}
                        </td>

                        <td>
                            {{ $detalle->insumo->nombre ?? '-' }}
                        </td>

                        <td>
                            {{ number_format($detalle->cantidad, 2) }}
                        </td>

                        <td>
                            Bs.
                            {{ number_format($detalle->precio_venta, 2) }}
                        </td>

                        <td>
                            Bs.
                            {{ number_format($detalle->subtotal, 2) }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>


    {{-- TOTALES --}}

    <div class="totales">

        <table>

            <tr>

                <td>
                    <strong>Total:</strong>
                </td>

                <td class="text-right">
                    Bs.
                    {{ number_format($salida->total, 2) }}
                </td>

            </tr>


            <tr>

                <td>
                    <strong>Monto pagado:</strong>
                </td>

                <td class="text-right">
                    Bs.
                    {{ number_format($salida->monto_pagado, 2) }}
                </td>

            </tr>


            <tr>

                <td class="total-final">
                    SALDO PENDIENTE:
                </td>

                <td class="text-right total-final">

                    Bs.
                    {{ number_format($salida->saldo_pendiente, 2) }}

                </td>

            </tr>

        </table>

    </div>


    {{-- OBSERVACIÓN --}}

    @if($salida->observacion)

        <div class="section">

            <div class="section-title">
                OBSERVACIÓN
            </div>

            <p>
                {{ $salida->observacion }}
            </p>

        </div>

    @endif


    {{-- FIRMA --}}

    <div class="firma">

        <div class="linea"></div>

        <div>
            Responsable
        </div>

    </div>


    {{-- FOOTER --}}

    <div class="footer">

        <hr>

        <p>
            Dirección: M. Ricardo Terrazas #1067 entre Benjamín Blanco y Medizabal
        </p>

        <p>
            Teléfono: 68574372
        </p>

        <p>
            TikTok: consultorio_mediconewton
        </p>

    </div>
    

</body>

</html>