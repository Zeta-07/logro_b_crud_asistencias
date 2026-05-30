<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <h1>Asistencias</h1>

    @foreach ($asistencias as $asistencia)
    <div>
        <p>Estudiante: {{ $asistencia->estudiante }}</p>
        <p>Materia: {{ $asistencia->materia }}</p>
        <p>Fecha: {{ $asistencia->fecha }}</p>
        <p>Estado: {{ $asistencia->estado }}</p>
        <p>Observaciones: {{ $asistencia->observaciones }}</p>
    </div>
        
    @endforeach

</body>
</html>