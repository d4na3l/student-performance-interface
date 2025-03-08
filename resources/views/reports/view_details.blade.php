<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de Predicción para Registro #{{ $record->id }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .prediction-result {
            margin-top: 10px;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .table-sm th, .table-sm td {
            padding: 0.3rem;
        }
    </style>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4" style="text-align: center">Detalles de Predicción</h1>

        <div class="card border-success mb-3">
            <div class="card-header">
                <h2>Estudiante #{{ $student->id }}</h2>
            </div>
            <div class="card-body">
                <p><strong>Género:</strong> {{ $student->gender->name }}</p>
                <p><strong>Tipo de Escuela:</strong> {{ $student->schoolType->name }}</p>
            </div>
        </div>

        <div class="card border-success mb-3">
            <div class="card-header">
                <h3>Registro de Desempeño #{{ $record->id }}</h3>
            </div>
            <div class="card-body">
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
            </div>
        </div>

        <div class="card border-success mb-3">
            <div class="card-header">
                <h3>Explicación de la Predicción</h3>
            </div>
            <div class="card-body">
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
                <div class="card bg-light mb-3" style="max-width: 24rem;" >
                    <div class="card-header">
                        <h4>Resumen de la Explicación</h4>
                    </div>
                    <div class="card-body">

                <p>{{ $explanationData['explanation']['summary'] }}</p>
                <p>{{ $explanationData['explanation']['percentile'] }}</p>
                </div>
                </div>
                <div class="card bg-light mb-3" style="max-width: 64rem;">
                    <div class="card-header">
                        <h4>Factores Clave</h4>
                    </div>
                    <div class="card-body">
                      <ul>
                        @foreach($explanationData['explanation']['key_factors'] as $factor)
                            <li>{{ $factor }}</li>
                        @endforeach
                    </ul>
                    </div>
                  </div>

                  <br>
                  <div class="card mb-4">
                    <div class="card-header">
                <h4>Características Principales</h4>
                <br>
                <table class="table table-bordered">
                    <thead class="">
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
            </div>
        </div>

        <a href="{{ route('reports.index') }}" class="btn btn-danger">Volver al Listado</a>
    </div>
</div>
</body>
</html>
