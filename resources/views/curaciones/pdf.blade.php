<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Curación {{ $curacion->codigo }}
    </title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .titulo {
            font-size: 20px;
            font-weight: bold;
        }

        .datos {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .datos td {
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }

        table.detalles {
            width: 100%;
            border-collapse: collapse;
        }

        table.detalles th,
        table.detalles td {
            border: 1px solid #ccc;
            padding: 6px;
        }

        table.detalles th {
            background: #f2f2f2;
        }

        .totales {
            width: 40%;
            margin-left: auto;
            margin-top: 20px;
        }

        .totales td {
            padding: 5px;
        }

        .firma {
            margin-top: 60px;
        }
    </style>

</head>


<body>

    <div class="header">

        <div class="titulo">
            CONSULTORIO NEWTON
        </div>

        <div>
            COMPROBANTE DE CURACIÓN
        </div>

        <strong>
            {{ $curacion->codigo }}
        </strong>

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
)->nombre }}

                {{ optional(
    optional(
        $curacion->consultation->appointment
    )->patient
)->apellido }}

            </td>


            <td>
                <strong>Fecha:</strong>
            </td>

            <td>
                {{ $curacion->fecha->format('d/m/Y') }}
            </td>

        </tr>


        <tr>

            <td>
                <strong>Médico:</strong>
            </td>

            <td colspan="3">

                {{ optional(
    optional(
        $curacion->consultation->appointment
    )->doctor
)->nombre }}

                {{ optional(
    optional(
        $curacion->consultation->appointment
    )->doctor
)->apellido }}

            </td>

        </tr>

    </table>


    @if($curacion->descripcion)

        <p>

            <strong>Descripción:</strong>

            {{ $curacion->descripcion }}

        </p>

    @endif


    <table class="detalles">

        <thead>

            <tr>

                <th>Elemento</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>

            </tr>

        </thead>


        <tbody>

            @foreach($curacion->detalles as $detalle)

                        <tr>

                            <td>
                                {{ $detalle->nombre }}
                            </td>

                            <td>
                                {{ number_format($detalle->cantidad, 2) }}
                            </td>

                            <td>
                                Bs.
                                {{ number_format(
                    $detalle->precio_unitario,
                    2
                ) }}
                            </td>

                            <td>
                                Bs.
                                {{ number_format(
                    $detalle->subtotal,
                    2
                ) }}
                            </td>

                        </tr>

            @endforeach


            <tr>

                <td colspan="3">
                    <strong>Costo de curación</strong>
                </td>

                <td>
                    Bs.
                    {{ number_format(
    $curacion->costo_curacion,
    2
) }}
                </td>

            </tr>

        </tbody>

    </table>


    <table class="totales">

        <tr>

            <td>
                <strong>Total insumos:</strong>
            </td>

            <td>
                Bs.
                {{ number_format(
    $curacion->total_insumos,
    2
) }}
            </td>

        </tr>


        <tr>

            <td>
                <strong>Curación:</strong>
            </td>

            <td>
                Bs.
                {{ number_format(
    $curacion->costo_curacion,
    2
) }}
            </td>

        </tr>


        <tr>

            <td>
                <strong>TOTAL:</strong>
            </td>

            <td>
                <strong>
                    Bs.
                    {{ number_format(
    $curacion->total,
    2
) }}
                </strong>
            </td>

        </tr>

    </table>


    <h4>
        Distribución del costo de curación
    </h4>


    <table class="detalles">

        <thead>

            <tr>

                <th>Concepto</th>
                <th>Porcentaje</th>
                <th>Monto</th>

            </tr>

        </thead>


        <tbody>

            @foreach($curacion->distribuciones as $distribucion)

                        <tr>

                            <td>
                                {{ $distribucion->concepto }}
                            </td>

                            <td>
                                {{ number_format(
                    $distribucion->porcentaje,
                    2
                ) }} %
                            </td>

                            <td>
                                Bs.
                                {{ number_format(
                    $distribucion->monto,
                    2
                ) }}
                            </td>

                        </tr>

            @endforeach

        </tbody>

    </table>


    @if($curacion->receta)

        <p style="margin-top:20px;">

            <strong>
                Receta:
            </strong>

            Receta médica registrada.

        </p>

    @endif


    <div class="firma">

        Recibido por: _______________________________

        <br><br>

        Entregado por: _______________________________

    </div>


</body>

</html>