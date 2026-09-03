<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Pago {{ $estudio->codigo }}
    </title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .titulo {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .subtitulo {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background: #f2f2f2;
        }

        .total {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>

</head>


<body>

    @php

        $paciente =
            $estudio->consultation?->appointment?->patient;

        $doctor =
            $estudio->consultation?->appointment?->doctor;

    @endphp


    <div class="titulo">
        CONSULTORIO NEWTON
    </div>

    <div class="subtitulo">
        COMPROBANTE DE PAGO A LABORATORIO
    </div>


    <strong>Registro:</strong>
    {{ $estudio->codigo }}

    <br>

    <strong>Fecha:</strong>
    {{ $estudio->fecha?->format('d/m/Y') }}

    <br><br>


    <strong>Paciente:</strong>

    @if($paciente)

        {{ $paciente->nombres }}
        {{ $paciente->apellidos }}

    @endif


    <br>

    <strong>Doctor:</strong>

    @if($doctor)

        Dr./Dra.
        {{ $doctor->nombres }}
        {{ $doctor->apellidos }}

    @endif


    <table>

        <thead>

            <tr>

                <th>Estudio</th>

                <th>Laboratorio</th>

                <th>Costo</th>

            </tr>

        </thead>


        <tbody>

            @foreach($estudio->detalles as $detalle)

                <tr>

                    <td>
                        {{ $detalle->nombre_estudio }}
                    </td>

                    <td>
                        {{ $detalle->laboratorio ?? '-' }}
                    </td>

                    <td>
                        Bs.
                        {{ number_format($detalle->precio_laboratorio, 2) }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>


    <div class="total">

        TOTAL A PAGAR AL LABORATORIO:

        Bs.
        {{ number_format($estudio->monto_pagado_laboratorio, 2) }}

    </div>


</body>

</html>