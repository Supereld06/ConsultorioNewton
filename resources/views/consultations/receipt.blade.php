<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Recibo</title>

    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 11px;
            color: #2c3e50;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0a2540;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .logo {
            width: 90px;
        }

        h2 {
            color: #0a2540;
            margin: 5px 0;
        }

        .info {
            background: #f4f7fb;
            padding: 10px;
            border-left: 4px solid #0a2540;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .info strong {
            display: inline-block;
            width: 140px;
        }

        .section {
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 10px;
        }

        .section-title {
            font-weight: bold;
            color: #0a2540;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 5px;
            text-align: center;
        }

        .total {
            text-align: right;
            margin-top: 5px;
            font-weight: bold;
        }

        .watermark {
            position: fixed;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.05;
            z-index: -1;
        }

        .watermark img {
            width: 300px;
        }
    </style>
</head>

<body>

    <!-- MARCA DE AGUA -->
    <div class="watermark">
        <img src="{{ public_path('img/logo.jpeg') }}">
    </div>

    <!-- ENCABEZADO -->
    <div class="header">
        <img src="{{ public_path('img/logo.jpeg') }}" class="logo">
        <h2>RECIBO DE ATENCIÓN</h2>
    </div>
    <div class="info">
        <strong>N° Recibo:</strong> {{ $consultation->receipt_number }}<br>
        <strong>Fecha Emisión:</strong> {{ $issueDate }}
    </div>

    <!-- DATOS -->
    <div class="info">
        <strong>Paciente:</strong>
        {{ $consultation->appointment->patient->nombres }}
        {{ $consultation->appointment->patient->apellidos }}<br>

        <strong>Doctor:</strong>
        {{ $consultation->appointment->doctor->nombres }}
        {{ $consultation->appointment->doctor->apellidos }}<br>
    </div>

    <!-- INSUMOS -->
    @if($consultation->supplies->count() > 0)
        <div class="section">
            <div class="section-title">INSUMOS UTILIZADOS</div>

            <table>
                <thead>
                    <tr>
                        <th>Insumo</th>
                        <th>Costo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($consultation->supplies as $s)
                        <tr>
                            <td>{{ $s->name }}</td>
                            <td>{{ $s->cost }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total">
                Total Insumos: {{ $totalSupplies }} Bs
            </div>
        </div>
    @endif

    <!-- ATENCIÓN MÉDICA -->
    <div class="section">
        <div class="section-title">ATENCIÓN MÉDICA</div>

        <div class="total">
            Total Atención: {{ $totalMedical }} Bs
        </div>
    </div>

    <!-- TOTAL GENERAL -->
    <div class="section">
        <div class="section-title">TOTAL GENERAL</div>

        <div class="total">
            {{ $totalSupplies + $totalMedical }} Bs
        </div>
    </div>

</body>

</html>