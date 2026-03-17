<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Historial Clínico</title>

    <style>
        body {
            font-family: Arial;
            font-size: 12px;
        }

        h2 {
            text-align: center;
        }

        .section {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <h2>Historial Clínico</h2>

    <p><strong>Paciente:</strong> {{ $patient->nombres }} {{ $patient->apellidos }}</p>
    <p><strong>CI:</strong> {{ $patient->ci }}</p>

    <hr>

    @foreach($patient->appointments as $a)

        @if($a->consultation && $a->consultation->atendido)

            <div class="section">
                <strong>Fecha:</strong> {{ $a->fecha }} - {{ $a->hora }} <br>
                <strong>Doctor:</strong> {{ $a->doctor->nombres }} <br>

                <strong>Diagnóstico:</strong><br>
                {{ $a->consultation->diagnostico }} <br>

                <strong>Tratamiento:</strong><br>
                {{ $a->consultation->tratamiento }} <br>

                <strong>Receta:</strong><br>
                {{ $a->consultation->receta }} <br>

                <strong>Observaciones:</strong><br>
                {{ $a->consultation->observaciones }}

                <hr>
            </div>

        @endif

    @endforeach

</body>

</html>