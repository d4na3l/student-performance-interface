<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recomendaciones para Registro #{{ $record->id }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Recomendaciones para Registro #{{ $record->id }}</h1>
        <p><strong>Predicción:</strong> {{ $recommendations['prediction'] }}</p>
        <p><strong>Categoría:</strong> {{ $recommendations['category'] }}</p>
        <p><strong>Percentil:</strong> {{ $recommendations['percentile'] }}</p>
        <p><strong>Tiempo de Procesamiento:</strong> {{ $recommendations['processing_time_ms'] }} ms</p>
        
        <h2 class="mt-4">Recomendaciones Personalizadas</h2>
        <table>
            <thead>
                <tr>
                    <th>Área</th>
                    <th>Recomendación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recommendations['recommendations'] as $item)
                <tr>
                    <td>{{ $item['area'] }}</td>
                    <td>{{ $item['recommendation'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <a href="{{ route('student.show', $student->id) }}" class="btn btn-secondary mt-3">Volver al Perfil</a>
    </div>
</body>
</html>
