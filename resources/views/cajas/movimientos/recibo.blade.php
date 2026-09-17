<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <title>
        Recibo de Movimiento #{{ $movimiento->id }}
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
       TIPO DE MOVIMIENTO
       ========================================== */

        .tipo-movimiento {
            text-align: center;
            margin: 10px 0;
        }

        .tipo-badge {
            display: inline-block;
            padding: 6px 25px;
            border: 2px solid #0a2540;
            font-weight: bold;
            font-size: 11px;
            letter-spacing: 1px;
        }

        .tipo-ingreso {
            color: #198754;
            border-color: #198754;
        }

        .tipo-egreso {
            color: #dc3545;
            border-color: #dc3545;
        }

        .tipo-transferencia {
            color: #b8860b;
            border-color: #b8860b;
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
            width: 110px;
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
       TABLAS
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
       MONTO
       ========================================== */

        .monto-box {
            margin-top: 12px;
            padding: 12px;
            border: 2px solid #0a2540;
            text-align: center;
        }

        .monto-label {
            font-size: 9px;
            color: #4a5a6a;
            margin-bottom: 5px;
        }

        .monto {
            font-size: 19px;
            font-weight: bold;
            color: #0a2540;
        }

        .monto-ingreso {
            color: #198754;
        }

        .monto-egreso {
            color: #dc3545;
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
            font-size: 12px !important;
            font-weight: bold;
            color: #0a2540;
        }

        .saldo-nuevo {
            color: #0a2540;
        }

        /* ==========================================
       TRANSFERENCIA
       ========================================== */

        .transferencia {
            margin-top: 12px;
            padding: 8px;
            background: #f4f7fb;
            border-left: 4px solid #b8860b;
        }

        .transferencia-title {
            font-weight: bold;
            color: #0a2540;
            font-size: 11px;
            margin-bottom: 8px;
        }

        .transferencia table td {
            border-bottom: 1px solid #dce3ec;
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
                RECIBO DE MOVIMIENTO DE CAJA
            </h2>

            <p>
                Documento de registro de movimiento
            </p>

        </div>


        {{-- ==========================================
        TIPO DE MOVIMIENTO
        ========================================== --}}

        <div class="tipo-movimiento">

            @if($movimiento->transferencia_id)

                <span class="tipo-badge tipo-transferencia">
                    TRANSFERENCIA
                </span>

            @elseif($movimiento->tipo === 'ingreso')

                <span class="tipo-badge tipo-ingreso">
                    INGRESO
                </span>

            @else

                <span class="tipo-badge tipo-egreso">
                    EGRESO
                </span>

            @endif

        </div>


        {{-- ==========================================
        INFORMACIÓN
        ========================================== --}}

        <div class="info">

            <strong>
                N° de Movimiento:
            </strong>

            #{{ str_pad($movimiento->id, 6, '0', STR_PAD_LEFT) }}

            <br>

            <strong>
                Fecha:
            </strong>

            {{ $movimiento->fecha
    ? $movimiento->fecha->format('d/m/Y H:i')
    : '-' }}

            <br>

            <strong>
                Caja:
            </strong>

            {{ $movimiento->caja->nombre ?? 'N/A' }}

            <br>

            <strong>
                Registrado por:
            </strong>

            {{ $movimiento->usuario->name ?? 'N/A' }}

            <br>

            @if($movimiento->transferencia_id)

                <strong>
                    N° Transferencia:
                </strong>

                #{{ $movimiento->transferencia_id }}

            @endif

        </div>


        {{-- ==========================================
        DETALLE DEL MOVIMIENTO
        ========================================== --}}

        <div class="section">

            <div class="section-title">
                DETALLE DEL MOVIMIENTO
            </div>

            <table>

                <thead>

                    <tr>

                        <th>
                            Concepto
                        </th>

                        <th class="center">
                            Tipo
                        </th>

                        <th class="right">
                            Monto
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <td>
                            {{ $movimiento->concepto }}
                        </td>

                        <td class="center">

                            @if($movimiento->transferencia_id)

                                TRANSFERENCIA

                            @else

                                {{ strtoupper($movimiento->tipo) }}

                            @endif

                        </td>

                        <td class="right">

                            Bs.
                            {{ number_format($movimiento->monto, 2) }}

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>


        {{-- ==========================================
        MONTO PRINCIPAL
        ========================================== --}}

        <div class="monto-box">

            <div class="monto-label">
                MONTO DEL MOVIMIENTO
            </div>

            <div class="monto
        @if($movimiento->tipo === 'ingreso')
            monto-ingreso
        @elseif($movimiento->tipo === 'egreso')
            monto-egreso
        @endif
    ">

                @if($movimiento->tipo === 'ingreso')

                    + Bs. {{ number_format($movimiento->monto, 2) }}

                @else

                    - Bs. {{ number_format($movimiento->monto, 2) }}

                @endif

            </div>

        </div>


        {{-- ==========================================
        INFORMACIÓN DE TRANSFERENCIA
        ========================================== --}}

        @if($movimiento->transferencia_id && $transferencia)

            <div class="transferencia">

                <div class="transferencia-title">
                    INFORMACIÓN DE LA TRANSFERENCIA
                </div>

                <table>

                    <thead>

                        <tr>

                            <th>
                                Tipo
                            </th>

                            <th>
                                Caja
                            </th>

                            <th class="right">
                                Monto
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($transferencia as $item)

                            <tr>

                                <td>

                                    {{ strtoupper($item->tipo) }}

                                </td>

                                <td>

                                    {{ $item->caja->nombre ?? '-' }}

                                </td>

                                <td class="right">

                                    Bs.
                                    {{ number_format($item->monto, 2) }}

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif


        {{-- ==========================================
        RESUMEN
        ========================================== --}}

        <table class="resumen">

            <tr>

                <td>

                    <strong>
                        Saldo anterior:
                    </strong>

                </td>

                <td class="right">

                    Bs.
                    {{ number_format($movimiento->saldo_anterior, 2) }}

                </td>

            </tr>

            <tr>

                <td class="total-final">

                    SALDO NUEVO:

                </td>

                <td class="right total-final saldo-nuevo">

                    Bs.
                    {{ number_format($movimiento->saldo_nuevo, 2) }}

                </td>

            </tr>

        </table>


        {{-- ==========================================
        OBSERVACIÓN
        ========================================== --}}

        @if($movimiento->observacion)

            <div class="observacion">

                <strong>
                    Observación:
                </strong>

                {{ $movimiento->observacion }}

            </div>

        @endif


        {{-- ==========================================
        FIRMAS
        ========================================== --}}

        <div class="firmas">

            <div class="firma">

                <div class="linea"></div>

                <div class="firma-titulo">
                    Responsable
                </div>

                <div class="firma-subtitulo">
                    {{ $movimiento->usuario->name ?? 'Responsable del registro' }}
                </div>

            </div>


            <div class="espacio">
                &nbsp;
            </div>


            <div class="firma">

                <div class="linea"></div>

                <div class="firma-titulo">
                    Recibido / Verificado
                </div>

                <div class="firma-subtitulo">
                    Firma y conformidad
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