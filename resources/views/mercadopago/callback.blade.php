<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado del Pago</title>
</head>
<body>
<h1>Estado del Pago</h1>

<p><strong>Mensaje: </strong>{{ $statusMessage }}</p>

@if (isset($queryParams['status']))
    <h2>Detalles del Pago:</h2>
    <ul>
        <li><strong>ID de la Preferencia:</strong> {{ $queryParams['preference_id'] ?? 'No disponible' }}</li>
        <li><strong>Estado del Pago:</strong> {{ $queryParams['status'] }}</li>
        <li><strong>Payment Type:</strong> {{ $queryParams['payment_type'] ?? 'No disponible' }}</li>
    </ul>
@endif

<a href="{{ route('mercado_pago.payment_form') }}">Volver al formulario de pago</a>
</body>
</html>
