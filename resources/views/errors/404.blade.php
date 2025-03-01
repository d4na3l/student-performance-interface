<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página no encontrada - Error 404</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .error-container {
            margin-top: 100px;
        }
    </style>
</head>
<body>
    <div class="container error-container text-center">
        <h1>Error 404</h1>
        <p>La página que buscas no se encontró.</p>
        <a href="{{ route('reports.index') }}" class="btn btn-primary mt-3">Volver a la Página Principal</a>
    </div>
</body>
</html>
