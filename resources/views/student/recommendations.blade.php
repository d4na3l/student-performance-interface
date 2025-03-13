<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recomendaciones para Registro #{{ $record->id }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border: none; box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); }
        .card-header { background-color: #007bff; color: white; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .btn-secondary { background-color: #6c757d; border: none; }
        .btn-secondary:hover { background-color: #5a6268; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Recomendaciones para Registro #{{ $record->id }}</h1>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <p><strong>Predicción:</strong> {{ $recommendations['prediction'] }}</p>
                    <p><strong>Categoría:</strong> {{ $recommendations['category'] }}</p>
                    <p><strong>Percentil:</strong> {{ $recommendations['percentile'] }}</p>
                    <p><strong>Tiempo de Procesamiento:</strong> {{ $recommendations['processing_time_ms'] }} ms</p>
                </div>

                <h2 class="mb-3">Recomendaciones Personalizadas</h2>
                <table class="table table-bordered">
                    <thead class="thead-light">
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

                <div class="text-center mt-4">
                    <a href="{{ route('student.show', $student->id) }}" class="btn btn-secondary">Volver al Perfil</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
