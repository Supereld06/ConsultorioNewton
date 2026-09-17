<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Curación {{ $curacion->codigo }}
    </title>

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

        .recibo {
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
            width: 90px;
            color: #0a2540;
        }


        /* ==========================================
           SECCIONES
           ========================================== */

        .section-title {
            margin-top: 12px;
            margin-bottom: 7px;
            font-size: 11px;
            font-weight: bold;
            color: #0a2540;
            letter-spacing: 0.5px;
        }


        /* ==========================================
           DESCRIPCIÓN
           ========================================== */

        .descripcion {
            margin-top: 10px;
            margin-bottom: 12px;
            padding: 8px;
            background: #f4f7fb;
            border-left: 4px solid #0a2540;
        }

        .descripcion strong {
            color: #0a2540;
        }


        /* ==========================================
           DETALLES
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
            padding: 7px 5px;
            font-size: 9px;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }


        /* ==========================================
           COSTO CURACIÓN
           ========================================== */

        .costo-curacion {
            margin-top: 10px;
        }

        .costo-curacion td {
            padding: 7px 5px;
        }

        .costo-label {
            font-weight: bold;
        }


        /* ==========================================
           TOTALES
           ========================================== */

        .total {
            margin-top: 15px;
            width: 48%;
            margin-left: auto;
        }

        .total td {
            border: none;
            padding: 5px;
            font-size: 9px;
        }

        .total-final {
            border-top: 2px solid #0a2540 !important;
            font-size: 14px !important;
            font-weight: bold;
            color: #0a2540;
        }


        /* ==========================================
           RECETA
           ========================================== */

        .receta {
            margin-top: 20px;
            padding: 10px;
            background: #f4f7fb;
            border-left: 4px solid #0a2540;
            font-size: 9px;
        }

        .receta strong {
            color: #0a2540;
        }


        /* ==========================================
           MENSAJE
           ========================================== */

        .mensaje {
            margin-top: 25px;
            padding: 10px;
            text-align: center;
            background: #f4f7fb;
            border: 1px solid #dce3ec;
            font-size: 9px;
        }

        .mensaje strong {
            color: #0a2540;
        }


        /* ==========================================
           FIRMAS
           ========================================== */

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

    <div class="recibo">


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

            <div class="consultorio">

                CONSULTORIO MÉDICO NEWTON

            </div>

            <h2>

                COMPROBANTE DE CURACIÓN

            </h2>

            <p>

                ATENCIÓN Y PROCEDIMIENTO DE CURACIÓN

            </p>

        </div>


        {{-- ==========================================
        INFORMACIÓN
        ========================================== --}}

        <div class="info">

            <strong>
                Código:
            </strong>

            {{ $curacion->codigo }}

            <br>


            <strong>
                Fecha:
            </strong>

            {{ $curacion->fecha
    ? $curacion->fecha->format('d/m/Y')
    : '-'
        }}

            <br>


            <strong>
                Paciente:
            </strong>

            {{ optional(
    optional(
        $curacion->consultation
    )->appointment
)->patient?->nombres }}

            {{ optional(
    optional(
        $curacion->consultation
    )->appointment
)->patient?->apellidos }}

            <br>


            <strong>
                CI:
            </strong>

            {{ optional(
    optional(
        optional(
            $curacion->consultation
        )->appointment
    )->patient
)->ci ?? '-' }}

            <br>


            <strong>
                Médico:
            </strong>

            {{ optional(
    optional(
        $curacion->consultation
    )->appointment
)->doctor?->nombres }}

            {{ optional(
    optional(
        $curacion->consultation
    )->appointment
)->doctor?->apellidos }}

            <br>


            <strong>
                Especialidad:
            </strong>

            {{ optional(
    optional(
        optional(
            $curacion->consultation
        )->appointment
    )->doctor
)->especialidad ?? '-' }}

            <br>


            <strong>
                Consulta:
            </strong>

            #{{ $curacion->consultation_id }}

        </div>


        {{-- ==========================================
        DESCRIPCIÓN
        ========================================== --}}

        @if($curacion->descripcion)

            <div class="descripcion">

                <strong>
                    Descripción:
                </strong>

                {{ $curacion->descripcion }}

            </div>

        @endif


        {{-- ==========================================
        DETALLE DE INSUMOS
        ========================================== --}}

        <div class="section-title">

            DETALLE DE LA CURACIÓN

        </div>


        <table>

            <thead>

                <tr>

                    <th>
                        Elemento
                    </th>

                    <th class="center">
                        Cantidad
                    </th>

                    <th class="right">
                        Precio
                    </th>

                    <th class="right">
                        Subtotal
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($curacion->detalles as $detalle)

                                <tr>

                                    <td>

                                        {{ $detalle->nombre }}

                                    </td>

                                    <td class="center">

                                        {{ number_format(
                        $detalle->cantidad,
                        2
                    ) }}

                                    </td>

                                    <td class="right">

                                        Bs.
                                        {{ number_format(
                        $detalle->precio_unitario,
                        2
                    ) }}

                                    </td>

                                    <td class="right">

                                        Bs.
                                        {{ number_format(
                        $detalle->subtotal,
                        2
                    ) }}

                                    </td>

                                </tr>

                @empty

                    <tr>

                        <td colspan="4" class="center">

                            No se registraron insumos.

                        </td>

                    </tr>

                @endforelse


                {{-- COSTO DE CURACIÓN --}}

                <tr>

                    <td colspan="3">

                        <strong>
                            Costo de curación
                        </strong>

                    </td>

                    <td class="right">

                        <strong>

                            Bs.
                            {{ number_format(
    $curacion->costo_curacion,
    2
) }}

                        </strong>

                    </td>

                </tr>

            </tbody>

        </table>


        {{-- ==========================================
        TOTALES
        ========================================== --}}

        <table class="total">

            <tr>

                <td>

                    <strong>
                        Total insumos:
                    </strong>

                </td>

                <td class="right">

                    Bs.
                    {{ number_format(
    $curacion->total_insumos,
    2
) }}

                </td>

            </tr>


            <tr>

                <td>

                    <strong>
                        Curación:
                    </strong>

                </td>

                <td class="right">

                    Bs.
                    {{ number_format(
    $curacion->costo_curacion,
    2
) }}

                </td>

            </tr>


            <tr>

                <td class="total-final">

                    TOTAL PAGADO:

                </td>

                <td class="right total-final">

                    Bs.
                    {{ number_format(
    $curacion->total,
    2
) }}

                </td>

            </tr>

        </table>


        {{-- ==========================================
        RECETA
        ========================================== --}}

        @if($curacion->receta)

            <div class="receta">

                <strong>
                    RECETA:
                </strong>

                Receta médica registrada para esta curación.

            </div>

        @endif


        {{-- ==========================================
        MENSAJE
        ========================================== --}}

        <div class="mensaje">

            <strong>
                RECIBIDO POR CONCEPTO DE CURACIÓN
            </strong>

            <br>

            Este comprobante corresponde al pago
            realizado por el procedimiento de curación
            e insumos utilizados.

        </div>


        {{-- ==========================================
        FIRMAS
        ========================================== --}}

        <div class="firmas">

            <div class="firma">

                <div class="linea"></div>

                <div class="firma-titulo">

                    Paciente

                </div>

                <div class="firma-subtitulo">

                    Firma del paciente

                </div>

            </div>


            <div class="espacio">

                &nbsp;

            </div>


            <div class="firma">

                <div class="linea"></div>

                <div class="firma-titulo">

                    Médico

                </div>

                <div class="firma-subtitulo">

                    Profesional responsable

                </div>

            </div>

        </div>


        {{-- ==========================================
        FOOTER
        ========================================== --}}

        <div class="footer">

            <p>

                Dirección: M. Ricardo Terrazas #1067 entre
                Benjamín Blanco y Medizabal

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