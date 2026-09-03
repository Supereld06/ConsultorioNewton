<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Receta {{ $curacion->codigo }}
    </title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .titulo {
            font-size: 20px;
            font-weight: bold;
        }

        .subtitulo {
            font-size: 12px;
            color: #666;
        }

        .datos {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .datos td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
        }

        .tabla {
            width: 100%;
            border-collapse: collapse;
        }

        .tabla th,
        .tabla td {
            border: 1px solid #ccc;
            padding: 7px;
        }

        .tabla th {
            background: #f2f2f2;
        }

        .indicaciones {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
        }

        .firma {
            margin-top: 80px;
            text-align: center;
        }
    </style>

</head>


<body>

    <div class="header">

        <div class="titulo">
            RECETA MÉDICA
        </div>

        <div class="subtitulo">
            Consultorio Newton
        </div>

        <div>
            Curación: {{ $curacion->codigo }}
        </div>

    </div>


    <table class="datos">

        <tr>

            <td>
                <strong>Paciente:</strong>
            </td>

            <td>

                {{ optional(
    optional(
        $curacion->consultation->appointment
    )->patient
)->nombres }}

                {{ optional(
    optional(
        $curacion->consultation->appointment
    )->patient
)->apellidos }}

            </td>

        </tr>


        <tr>

            <td>
                <strong>Médico:</strong>
            </td>

            <td>

                {{ optional(
    optional(
        $curacion->consultation->appointment
    )->doctor
)->nombres }}

                {{ optional(
    optional(
        $curacion->consultation->appointment
    )->doctor
)->apellidos }}

            </td>

        </tr>


        <tr>

            <td>
                <strong>Fecha:</strong>
            </td>

            <td>
                {{ $curacion->fecha->format('d/m/Y') }}
            </td>

        </tr>

    </table>


    <table class="tabla">

        <thead>

            <tr>

                <th>Medicamento</th>
                <th>Dosis</th>
                <th>Frecuencia</th>
                <th>Duración</th>
                <th>Indicaciones</th>

            </tr>

        </thead>


        <tbody>

            @foreach($curacion->receta->detalles as $detalle)

                <tr>

                    <td>
                        {{ $detalle->medicamento }}
                    </td>

                    <td>
                        {{ $detalle->dosis }}
                    </td>

                    <td>
                        {{ $detalle->frecuencia }}
                    </td>

                    <td>
                        {{ $detalle->duracion }}
                    </td>

                    <td>
                        {{ $detalle->indicaciones }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>


    @if($curacion->receta->indicaciones)

        <div class="indicaciones">

            <strong>
                Indicaciones generales:
            </strong>

            <br><br>

            {{ $curacion->receta->indicaciones }}

        </div>

    @endif


    <div class="firma">

        _______________________________

        <br>

        Firma del médico

    </div>


</body>

</html>