<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Predicciones en Lote</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Predicciones en Lote</h1>
    <h2>Estadísticas</h2>
    <p>Total de registros: {{ $data['stats']['count'] }}</p>
    <p>Predicción promedio: {{ $data['stats']['average_prediction'] }}</p>
    <p>Predicción mínima: {{ $data['stats']['min_prediction'] }}</p>
    <p>Predicción máxima: {{ $data['stats']['max_prediction'] }}</p>
    
    <h2>Resultados</h2>
    <table>
        <thead>
            <tr>
                <th>Índice</th>
                <th>Predicción</th>
                <th>Categoría</th>
                <th>Percentil</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['results'] as $result)
            <tr>
                <td>{{ $result['student_index'] }}</td>
                <td>{{ $result['prediction'] }}</td>
                <td>{{ $result['category'] ?? 'N/A' }}</td>
                <td>{{ $result['percentile'] ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
