<!DOCTYPE html>

<html>

<head>

    
    <meta charset="utf-8">

    <title>Recibo {{ $salida->codigo }}</title>

    <style>
        @page {
            margin: 15px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #222;
            margin: 0;
        }

        .recibo {
            width: 100%;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 12px;
        }

        .logo {
            width: 80px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 17px;
        }

        .header p {
            margin: 3px 0;
        }

        .info {
            margin-bottom: 12px;
        }

        .info strong {
            display: inline-block;
            width: 90px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #eeeeee;
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
        }

        td {
            border: 1px solid #ccc;
            padding: 6px;
        }

        .right {
            text-align: right;
        }

        .total {
            margin-top: 15px;
            width: 50%;
            margin-left: auto;
        }

        .total td {
            border: none;
            padding: 5px;
        }

        .total-final {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #000 !important;
        }

        .footer {
            margin-top: 35px;
            text-align: center;
            font-size: 9px;
            border-top: 1px solid #aaa;
            padding-top: 8px;
        }
    </style>
    

</head>

<body>

    <div class="recibo">

        
        {{-- ENCABEZADO --}}

        <div class="header">

            <img src="{{ public_path('img/logo.jpeg') }}" class="logo">

            <h2>
                RECIBO
            </h2>

            <p>
                SALIDA DE INSUMOS
            </p>

        </div>


        {{-- INFORMACIÓN --}}

        <div class="info">

            <strong>N°:</strong>
            {{ $salida->codigo }}

            <br>

            <strong>Fecha:</strong>
            {{ $salida->fecha->format('d/m/Y') }}

            <br>

            <strong>Usuario:</strong>
            {{ $salida->usuario->name ?? 'N/A' }}

        </div>


        {{-- DETALLE --}}

        <table>

            <thead>

                <tr>

                    <th>
                        Cant.
                    </th>

                    <th>
                        Insumo
                    </th>

                    <th class="right">
                        Precio venta
                    </th>

                    <th class="right">
                        Total
                    </th>

                </tr>

            </thead>


            <tbody>

                @foreach($salida->detalles as $detalle)

                    <tr>

                        <td>
                            {{ number_format($detalle->cantidad, 2) }}
                        </td>

                        <td>
                            {{ $detalle->insumo->nombre ?? '-' }}
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


        {{-- TOTALES --}}

        <table class="total">

            <tr>

                <td>
                    <strong>Total:</strong>
                </td>

                <td class="right">

                    Bs.
                    {{ number_format($salida->total, 2) }}

                </td>

            </tr>


            <tr>

                <td class="total-final">
                    TOTAL PAGADO:
                </td>

                <td class="right total-final">

                    Bs.
                    {{ number_format($salida->monto_pagado, 2) }}

                </td>

            </tr>

        </table>


        {{-- FOOTER --}}

        <div class="footer">

            <p>
                Gracias por su compra
            </p>

            <p>
                Consultorio Médico Newton
            </p>

            <p>
                Teléfono: 68574372
            </p>

        </div>
        

    </div>

</body>

</html>