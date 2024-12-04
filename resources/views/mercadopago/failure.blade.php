<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Fallido</title>

    <!-- Cargar Bootstrap 5 desde el CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8d7da;
        }
        .card {
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card text-center" style="max-width: 600px;">
            <h2 class="text-danger">Lo sentimos, hubo un problema con el pago</h2>
            <p class="lead">Por favor, intenta nuevamente más tarde.</p>
            <a href="{{ route('mercadopago.payment') }}" class="btn btn-danger">Intentar de nuevo</a>
        </div>
    </div>

    <!-- Cargar Bootstrap 5 JS desde el CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
