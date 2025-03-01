<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Estudiantes</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .prediction-result {
            margin-top: 10px;
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
    <script>
    // Función para seleccionar o deseleccionar todos los checkboxes
    function toggleSelectAll(source) {
        let checkboxes = document.getElementsByName('record_ids[]');
        for(let i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = source.checked;
        }
    }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1>Reporte de Estudiantes</h1>
        <!-- Formulario para enviar registros seleccionados en lote -->
        <form action="{{ route('reports.batchPredict') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success mb-3">Generar Reporte en PDF para registros seleccionados</button>
            <table class="table table-bordered">
                <thead>
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
                            <a href="{{ route('student.show', ['id' => $student->id]) }}"">
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
