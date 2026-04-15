<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Recibo Médico</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #2c3e50;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0a2540;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .logo {
            width: 80px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            color: #0a2540;
        }

        .info {
            margin-bottom: 10px;
        }

        .info strong {
            display: inline-block;
            width: 140px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th {
            background: #0a2540;
            color: white;
            padding: 6px;
            border: 1px solid #ddd;
        }

        .table td {
            padding: 6px;
            border: 1px solid #ddd;
        }

        .total {
            text-align: right;
            margin-top: 15px;
            font-size: 14px;
        }

        .watermark {
            position: fixed;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.05;
        }

        .watermark img {
            width: 300px;
        }
    </style>
</head>

<body>

    <!-- 🔥 MARCA DE AGUA -->
    <div class="watermark">
        <img src="{{ public_path('img/logo.jpeg') }}">
    </div>

    <!-- 🔷 HEADER -->
    <div class="header">
        <img src="{{ public_path('img/logo.jpeg') }}" class="logo">
        <div class="title">RECIBO DE PAGO MÉDICO</div>
    </div>

    <!-- 🔷 INFO -->
    <div class="info">
        <strong>Recibo:</strong> {{ $details->first()->receipt_number }}<br>
        <strong>Médico:</strong>
        {{ $details->first()->doctor->nombres }}
        {{ $details->first()->doctor->apellidos }}<br>

        <strong>Fecha:</strong>
        {{ $details->first()->created_at->format('d/m/Y H:i') }}
    </div>

    <!-- 🔷 TABLA -->
    <table class="table">

        <thead>
            <tr>
                <th>Paciente</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Costo</th>
            </tr>
        </thead>

        <tbody>
            @foreach($details as $d)
                <tr>
                    <td>
                        {{ $d->patient->nombres }}
                        {{ $d->patient->apellidos }}
                    </td>
                    <td>{{ $d->date }}</td>
                    <td>{{ $d->time }}</td>
                    <td>{{ $d->cost_medico }}</td>
                </tr>
            @endforeach
        </tbody>

    </table>

    <!-- 🔷 TOTAL -->
    <div class="total">
        <strong>Total Pagado: {{ $details->first()->total }}</strong>
    </div>

</body>

</html>