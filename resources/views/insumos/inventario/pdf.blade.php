<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <title>Inventario de Insumos</title>

    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 10px;
            color: #2c3e50;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #0a2540;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .logo {
            width: 90px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 18px;
            color: #0a2540;
        }

        .header p {
            margin: 3px 0;
            color: #4a5a6a;
        }

        .info {
            margin-bottom: 15px;
            padding: 10px;
            background: #f4f7fb;
            border-left: 4px solid #0a2540;
        }

        .info strong {
            color: #0a2540;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #0a2540;
            color: white;
            padding: 7px 5px;
            text-align: left;
        }

        td {
            border-bottom: 1px solid #dce3ec;
            padding: 6px 5px;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .badge {
            font-weight: bold;
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
            border-top: 2px solid #0a2540 !important;
            font-size: 13px;
            font-weight: bold;
            color: #0a2540;
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
            top: 42%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.04;
        }

        .watermark img {
            width: 330px;
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
            INVENTARIO DE INSUMOS
        </h2>

        <p>
            Control actual de existencias
        </p>

    </div>


    {{-- INFORMACIÓN --}}

    <div class="info">

        <strong>
            Fecha de generación:
        </strong>

        {{ now()->format('d/m/Y H:i') }}

        <br>

        <strong>
            Total de insumos:
        </strong>

        {{ $totalProductos }}

        <br>

        <strong>
            Productos agotados:
        </strong>

        {{ $productosAgotados }}

        <br>

        <strong>
            Productos con stock bajo:
        </strong>

        {{ $productosStockBajo }}

    </div>


    {{-- TABLA --}}

    <table>

        <thead>

            <tr>

                <th>
                    Código
                </th>

                <th>
                    Insumo
                </th>

                <th>
                    Tipo
                </th>

                <th class="center">
                    Stock
                </th>

                <th class="right">
                    P. Compra
                </th>

                <th class="right">
                    P. Venta
                </th>

                <th class="right">
                    Valor
                </th>

                <th class="center">
                    Estado
                </th>

            </tr>

        </thead>


        <tbody>

            @foreach($insumos as $insumo)

                @php

                    if ($insumo->stock <= 0) {

                        $estado = 'AGOTADO';

                    } elseif ($insumo->stock <= $insumo->stock_minimo) {

                        $estado = 'STOCK BAJO';

                    } else {

                        $estado = 'DISPONIBLE';

                    }

                    $valor = $insumo->stock * $insumo->precio_compra;

                @endphp


                <tr>

                    <td>
                        {{ $insumo->codigo }}
                    </td>

                    <td>
                        {{ $insumo->nombre }}
                    </td>

                    <td>
                        {{ $insumo->tipo }}
                    </td>

                    <td class="center">

                        {{ number_format($insumo->stock, 2) }}

                        {{ $insumo->unidad_medida }}

                    </td>

                    <td class="right">

                        Bs.
                        {{ number_format($insumo->precio_compra, 2) }}

                    </td>

                    <td class="right">

                        Bs.
                        {{ number_format($insumo->precio_venta, 2) }}

                    </td>

                    <td class="right">

                        Bs.
                        {{ number_format($valor, 2) }}

                    </td>

                    <td class="center">

                        {{ $estado }}

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>


    {{-- TOTAL --}}

    <div class="totales">

        <table>

            <tr>

                <td>
                    <strong>
                        Valor total del inventario:
                    </strong>
                </td>

                <td class="right total-final">

                    Bs.
                    {{ number_format($valorInventario, 2) }}

                </td>

            </tr>

        </table>

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