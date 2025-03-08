<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Estudiantes</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .prediction-result {
            margin-top: 10px;
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 style="text-align: center">Reporte de Estudiantes</h1>
        <br>
        <!-- Formulario para enviar registros seleccionados en lote -->
        <form action="{{ route('reports.batchPredict') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success mb-3">Generar Reporte en PDF para registros seleccionados</button>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th><input type="checkbox" onClick="toggleSelectAll(this)"></th>
                        <th>ID Estudiante</th>
                        <th>Género</th>
                        <th>Tipo de Escuela</th>
                        <th>Registros de Desempeño</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td colspan="5">
                            <a href="{{ route('student.show', ['id' => $student->id]) }}">
                                <strong>Estudiante #{{ $student->id }}</strong>
                            </a>
                             – Género: {{ $student->gender->name }} – Escuela: {{ $student->schoolType->name }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Horas de Estudio</th>
                                        <th>Asistencia</th>
                                        <th>Puntuación Anterior</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->performanceRecords as $record)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="record_ids[]" value="{{ $record->id }}">
                                        </td>
                                        <td>{{ $record->hours_studied }}</td>
                                        <td>{{ $record->attendance }}</td>
                                        <td>{{ $record->previous_scores }}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary predict-btn" data-student="{{ $student->id }}" data-record="{{ $record->id }}">
                                                Predict
                                            </button>
                                            <!-- Enlace para ver detalles -->
                                            <a href="{{ route('reports.viewDetails', ['student' => $student->id, 'record' => $record->id]) }}" class="btn btn-info btn-sm">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                    <!-- Fila para mostrar el resultado individual de predicción -->
                                    <tr id="prediction-row-{{ $record->id }}" style="display:none;">
                                        <td colspan="6">
                                            <div class="prediction-result" id="prediction-result-{{ $record->id }}">
                                                <!-- Se insertará la tabla del resultado individual -->
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const predictButtons = document.querySelectorAll('.predict-btn');
        predictButtons.forEach(button => {
            button.addEventListener('click', function() {
                const studentId = this.getAttribute('data-student');
                const recordId = this.getAttribute('data-record');

                // Limpiar resultados individuales previos
                document.querySelectorAll('.prediction-result').forEach(div => {
                    div.innerHTML = '';
                });
                document.querySelectorAll('[id^="prediction-row-"]').forEach(row => {
                    row.style.display = 'none';
                });

                fetch("{{ route('reports.predictRecord') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        student_id: studentId,
                        record_id: recordId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    let html = '<table class="table table-sm table-bordered">';
                    html += '<thead><tr><th>Predicción</th><th>Tiempo (ms)</th></tr></thead>';
                    html += `<tbody><tr><td>${data.prediction}</td><td>${data.processing_time_ms}</td></tr></tbody>`;
                    html += '</table>';

                    document.getElementById(`prediction-result-${recordId}`).innerHTML = html;
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
