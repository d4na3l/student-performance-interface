<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de Predicción para Registro #{{ $record->id }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container mt-5">
        <h1>Detalles de Predicción</h1>
        
        <h2>Estudiante #{{ $student->id }}</h2>
        <p><strong>Género:</strong> {{ $student->gender->name }}</p>
        <p><strong>Tipo de Escuela:</strong> {{ $student->schoolType->name }}</p>
        
        <h3>Registro de Desempeño #{{ $record->id }}</h3>
        <table class="table table-bordered">
            <tr>
                <th>Horas de Estudio</th>
                <td>{{ $record->hours_studied }}</td>
            </tr>
            <tr>
                <th>Asistencia</th>
                <td>{{ $record->attendance }}</td>
            </tr>
            <tr>
                <th>Horas de Sueño</th>
                <td>{{ $record->sleep_hours }}</td>
            </tr>
            <tr>
                <th>Puntuación Anterior</th>
                <td>{{ $record->previous_scores }}</td>
            </tr>
            <tr>
                <th>Sesiones de Tutoría</th>
                <td>{{ $record->tutoring_sessions }}</td>
            </tr>
            <tr>
                <th>Actividad Física</th>
                <td>{{ $record->physical_activity }}</td>
            </tr>
        </table>
        
        <h3>Explicación de la Predicción</h3>
        <table class="table table-bordered">
            <tr>
                <th>Predicción</th>
                <td>{{ $explanationData['prediction'] }}</td>
            </tr>
            <tr>
                <th>Categoría</th>
                <td>{{ $explanationData['category'] }}</td>
            </tr>
            <tr>
                <th>Percentil</th>
                <td>{{ $explanationData['percentile'] }}</td>
            </tr>
            <tr>
                <th>Tiempo de Procesamiento</th>
                <td>{{ $explanationData['processing_time_ms'] }} ms</td>
            </tr>
        </table>
        
        <h4>Resumen de la Explicación</h4>
        <p>{{ $explanationData['explanation']['summary'] }}</p>
        <p>{{ $explanationData['explanation']['percentile'] }}</p>
        
        <h4>Factores Clave</h4>
        <ul>
            @foreach($explanationData['explanation']['key_factors'] as $factor)
                <li>{{ $factor }}</li>
            @endforeach
        </ul>
        
        <h4>Características Principales</h4>
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>Característica</th>
                    <th>Coeficiente</th>
                    <th>Importancia</th>
                </tr>
            </thead>
            <tbody>
                @foreach($explanationData['top_features'] as $feature)
                    <tr>
                        <td>{{ $feature['feature'] }}</td>
                        <td>{{ $feature['coefficient'] }}</td>
                        <td>{{ $feature['importance'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <a href="{{ route('reports.index') }}" class="btn btn-secondary mt-3">Volver al Listado</a>
    </div>
</body>
</html>
