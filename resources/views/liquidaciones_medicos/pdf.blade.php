<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <title>
        Liquidación {{ $liquidacion->numero }}
    </title>

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

        .origen-atencion {
            font-weight: bold;
        }

        .origen-curacion {
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

        .saldo {
            font-size: 12px;
            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | FIRMAS
        |--------------------------------------------------------------------------
        */

        .firmas {
            margin-top: 45px;
            width: 100%;
        }

        .firma {
            width: 45%;
            text-align: center;
            vertical-align: top;
        }

        .espacio-firma {
            height: 45px;
        }

        .linea-firma {
            border-top: 1px solid #2c3e50;
            padding-top: 6px;
            font-weight: bold;
        }

        .firma-subtitulo {
            font-size: 9px;
            color: #4a5a6a;
            margin-top: 3px;
        }

        /*
        |--------------------------------------------------------------------------
        | RECIBO
        |--------------------------------------------------------------------------
        */

        .recibo {
            margin-top: 30px;
            border: 1px solid #cfd8e3;
            padding: 12px;
            background: #f8fafc;
        }

        .recibo-titulo {
            text-align: center;
            color: #0a2540;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .recibo-texto {
            text-align: center;
            font-size: 10px;
            margin-bottom: 5px;
        }

        .recibo-monto {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            color: #0a2540;
            margin: 8px 0;
        }

        /*
        |--------------------------------------------------------------------------
        | PIE
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | MARCA DE AGUA
        |--------------------------------------------------------------------------
        */

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
            LIQUIDACIÓN MÉDICA
        </h2>

        <p>
            Detalle de ingresos generados
        </p>

    </div>


    {{-- INFORMACIÓN GENERAL --}}

    <div class="info">

        <strong>
            Número de liquidación:
        </strong>

        {{ $liquidacion->numero }}

        <br>


        <strong>
            Médico:
        </strong>

        {{ $liquidacion->doctor->apellidos ?? '' }}
        {{ $liquidacion->doctor->nombres ?? '' }}

        <br>


        <strong>
            Fecha de liquidación:
        </strong>

        {{ $liquidacion->fecha?->format('d/m/Y') }}

        <br>


        <strong>
            Estado:
        </strong>

        {{ strtoupper($liquidacion->estado) }}

        <br>


        <strong>
            Observación:
        </strong>

        {{ $liquidacion->observacion ?: 'Sin observaciones' }}

    </div>


    {{-- DETALLE --}}

    <table>

        <thead>

            <tr>

                <th class="center">
                    #
                </th>

                <th>
                    Paciente
                </th>

                <th class="center">
                    Fecha
                </th>

                <th class="center">
                    Hora
                </th>

                <th>
                    Origen
                </th>

                <th>
                    Concepto
                </th>

                <th class="right">
                    Monto
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($liquidacion->detalles as $detalle)

                <tr>

                    {{-- NÚMERO --}}

                    <td class="center">

                        {{ $loop->iteration }}

                    </td>


                    {{-- PACIENTE --}}

                    <td>

                        <strong>
                            {{ $detalle->paciente_nombre ?? 'Sin paciente' }}
                        </strong>

                    </td>


                    {{-- FECHA --}}

                    <td class="center">

                        {{ $detalle->fecha_atencion?->format('d/m/Y') ?? '--' }}

                    </td>


                    {{-- HORA --}}

                    <td class="center">

                        @if($detalle->hora_atencion)

                                        {{ \Carbon\Carbon::parse(
                                $detalle->hora_atencion
                            )->format('H:i') }}

                        @else

                            --

                        @endif

                    </td>


                    {{-- ORIGEN --}}

                    <td>

                        @if($detalle->tipo_origen === 'pago_medico')

                            <span class="origen-atencion">
                                Atención médica
                            </span>

                        @elseif($detalle->tipo_origen === 'curacion')

                            <span class="origen-curacion">
                                Curación
                            </span>

                        @else

                            {{ $detalle->tipo_origen }}

                        @endif

                    </td>


                    {{-- CONCEPTO --}}

                    <td>

                        {{ $detalle->concepto }}

                    </td>


                    {{-- MONTO --}}

                    <td class="right">

                        Bs.
                        {{ number_format($detalle->monto, 2) }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="7" class="center" style="padding: 15px;">

                        No existen detalles de ingresos.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    {{-- TOTALES --}}

    <div class="totales">

        <table>

            <tr>

                <td>

                    <strong>
                        Total generado:
                    </strong>

                </td>

                <td class="right total-final">

                    Bs.
                    {{ number_format(
    $liquidacion->total_generado,
    2
) }}

                </td>

            </tr>


            <tr>

                <td>

                    <strong>
                        Total pagado:
                    </strong>

                </td>

                <td class="right">

                    Bs.
                    {{ number_format(
    $liquidacion->total_pagado,
    2
) }}

                </td>

            </tr>


            <tr>

                <td>

                    <strong>
                        Saldo pendiente:
                    </strong>

                </td>

                <td class="right saldo">

                    Bs.
                    {{ number_format(
    $liquidacion->saldo,
    2
) }}

                </td>

            </tr>

        </table>

    </div>


    {{-- RECIBO --}}

    <div class="recibo">

        <div class="recibo-titulo">

            RECIBO DE LIQUIDACIÓN MÉDICA

        </div>


        <div class="recibo-texto">

            Por medio del presente documento se deja constancia
            de la liquidación correspondiente al médico:

        </div>


        <div class="recibo-texto">

            <strong>

                {{ $liquidacion->doctor->apellidos ?? '' }}
                {{ $liquidacion->doctor->nombres ?? '' }}

            </strong>

        </div>


        <div class="recibo-texto">

            Liquidación N.º:

            <strong>
                {{ $liquidacion->numero }}
            </strong>

        </div>


        <div class="recibo-monto">

            MONTO PAGADO

            <br>

            Bs.
            {{ number_format(
    $liquidacion->total_pagado,
    2
) }}

        </div>


        <div class="recibo-texto">

            Fecha:

            {{ $liquidacion->fecha?->format('d/m/Y') }}

        </div>

    </div>


    {{-- FIRMAS --}}

    <table class="firmas">

        <tr>

            <td class="firma">

                <div class="espacio-firma"></div>

                <div class="linea-firma">

                    RECIBÍ CONFORME

                </div>

                <div class="firma-subtitulo">

                    Médico

                </div>

                <div class="firma-subtitulo">

                    {{ $liquidacion->doctor->apellidos ?? '' }}
                    {{ $liquidacion->doctor->nombres ?? '' }}

                </div>

            </td>


            <td width="10%"></td>


            <td class="firma">

                <div class="espacio-firma"></div>

                <div class="linea-firma">

                    ENTREGUÉ CONFORME

                </div>

                <div class="firma-subtitulo">

                    Administración

                </div>

            </td>

        </tr>

    </table>


    {{-- PIE --}}

    <div class="footer">

        <hr>

        Documento generado por el sistema
        de Consultorio Newton.

        &nbsp;&nbsp;|&nbsp;&nbsp;

        Liquidación {{ $liquidacion->numero }}

    </div>


</body>

</html>