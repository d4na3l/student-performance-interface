<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Estudiante #{{ $student->id }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .prediction-result {
            margin-top: 10px;
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Detalle del Estudiante #{{ $student->id }}</h1>
        <p><strong>Género:</strong> {{ $student->gender->name }}</p>
        <p><strong>Tipo de Escuela:</strong> {{ $student->schoolType->name }}</p>
        
        <h2>Registros de Desempeño</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Registro</th>
                    <th>Horas de Estudio</th>
                    <th>Asistencia</th>
                    <th>Puntuación Anterior</th>
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
                    <td>
                        <!-- Botón para invocar la predicción -->
                        <button class="btn btn-primary predict-btn" data-student="{{ $student->id }}" data-record="{{ $record->id }}">
                            Predict
                        </button>
                    </td>
                </tr>
                <!-- Fila para mostrar el resultado de la predicción -->
                <tr id="prediction-row-{{ $record->id }}" style="display:none;">
                    <td colspan="5">
                        <div class="prediction-result" id="prediction-result-{{ $record->id }}">
                            <!-- Aquí se insertará la tabla con el resultado de la predicción -->
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const predictButtons = document.querySelectorAll('.predict-btn');
        predictButtons.forEach(button => {
            button.addEventListener('click', function() {
                const studentId = this.getAttribute('data-student');
                const recordId = this.getAttribute('data-record');

                // Limpiar cualquier resultado de predicción previo
                document.querySelectorAll('.prediction-result').forEach(div => {
                    div.innerHTML = '';
                });
                document.querySelectorAll('[id^="prediction-row-"]').forEach(row => {
                    row.style.display = 'none';
                });

                // Enviar la petición POST vía fetch a nuestro endpoint de predicción
                fetch(`/reports/${studentId}/performance/${recordId}/predict`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    // Construir la tabla con el resultado obtenido
                    let html = '<table class="table table-sm table-bordered"><thead><tr><th>Predicción</th><th>Tiempo (ms)</th></tr></thead><tbody>';
                    html += `<tr><td>${data.prediction}</td><td>${data.processing_time_ms}</td></tr>`;
                    html += '</tbody></table>';
                    
                    // Insertar el resultado y mostrar la fila
                    const resultDiv = document.getElementById(`prediction-result-${recordId}`);
                    resultDiv.innerHTML = html;
                    document.getElementById(`prediction-row-${recordId}`).style.display = '';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al obtener la predicción');
                });
            });
        });
    });
    </script>
</body>
</html>
