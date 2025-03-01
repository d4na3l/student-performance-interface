<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Estudiante #{{ $student->id }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .prediction-box {
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            padding: 10px;
            margin-top: 5px;
        }
        .record-form {
            margin-top: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Perfil del Estudiante #{{ $student->id }}</h1>
        <p><strong>Género:</strong> {{ $student->gender->name }}</p>
        <p><strong>Tipo de Escuela:</strong> {{ $student->schoolType->name }}</p>
        
        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif

        <h2 class="mt-4">Registros de Desempeño</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Registro</th>
                    <th>Horas de Estudio</th>
                    <th>Asistencia</th>
                    <th>Puntuación Anterior</th>
                    <th>Exam Score</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($student->performanceRecords as $record)
                <tr>
                    <td>{{ $record->id }}</td>
                    <td>{{ $record->hours_studied }}</td>
                    <td>{{ $record->attendance }}</td>
                    <td>{{ $record->previous_scores }}</td>
                    <td>{{ $record->exam_score ?? 'Pendiente' }}</td>
                    <td>
                        @if(is_null($record->exam_score))
                            @if(isset($predictions[$record->id]))
                                @if(isset($predictions[$record->id]['error']))
                                    <span class="text-danger">{{ $predictions[$record->id]['error'] }}</span>
                                @else
                                    <div class="prediction-box">
                                        <p><strong>Predicción:</strong> {{ $predictions[$record->id]['prediction'] }}</p>
                                        <p><strong>Categoría:</strong> {{ $predictions[$record->id]['category'] }}</p>
                                        <p><strong>Percentil:</strong> {{ $predictions[$record->id]['percentile'] }}</p>
                                        <p><strong>Tiempo:</strong> {{ $predictions[$record->id]['processing_time_ms'] }} ms</p>
                                    </div>
                                @endif
                            @else
                                <span>No se obtuvo predicción.</span>
                            @endif

                            <!-- Formulario para actualizar el registro -->
                            <div class="record-form">
                                <form action="{{ route('student.updateRecord', $record->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="hours_studied_{{ $record->id }}">Horas de Estudio</label>
                                        <input type="number" step="0.1" name="hours_studied" id="hours_studied_{{ $record->id }}" class="form-control" value="{{ $record->hours_studied }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="attendance_{{ $record->id }}">Asistencia</label>
                                        <input type="number" step="0.1" name="attendance" id="attendance_{{ $record->id }}" class="form-control" value="{{ $record->attendance }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="sleep_hours_{{ $record->id }}">Horas de Sueño</label>
                                        <input type="number" step="0.1" name="sleep_hours" id="sleep_hours_{{ $record->id }}" class="form-control" value="{{ $record->sleep_hours }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="previous_scores_{{ $record->id }}">Puntuación Anterior</label>
                                        <input type="number" step="0.1" name="previous_scores" id="previous_scores_{{ $record->id }}" class="form-control" value="{{ $record->previous_scores }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tutoring_sessions_{{ $record->id }}">Sesiones de Tutoría</label>
                                        <input type="number" step="0.1" name="tutoring_sessions" id="tutoring_sessions_{{ $record->id }}" class="form-control" value="{{ $record->tutoring_sessions }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="physical_activity_{{ $record->id }}">Actividad Física</label>
                                        <input type="number" step="0.1" name="physical_activity" id="physical_activity_{{ $record->id }}" class="form-control" value="{{ $record->physical_activity }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-2">Actualizar Registro</button>
                                </form>
                            </div>

                            <!-- Botón para ver recomendaciones -->
                            <a href="{{ route('student.recommend', ['student' => $student->id, 'record' => $record->id]) }}" class="btn btn-warning btn-sm mt-2">
                                Ver Recomendaciones
                            </a>
                        @else
                            <span>Registro completo</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Formulario para agregar un nuevo registro -->
        <h3 class="mt-4">Agregar Nuevo Registro</h3>
        <form action="{{ route('student.storeRecord', $student->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="new_hours_studied">Horas de Estudio</label>
                <input type="number" step="0.1" name="hours_studied" id="new_hours_studied" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="new_attendance">Asistencia</label>
                <input type="number" step="0.1" name="attendance" id="new_attendance" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success mt-2">Agregar Nuevo Registro</button>
        </form>
        
        <a href="{{ route('reports.index') }}" class="btn btn-secondary mt-3">Volver al Reporte General</a>
    </div>
</body>
</html>
