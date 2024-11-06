<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <title>Usuario</title>
    <style>
        .user-card {
            min-height: 400px; /* Ajusta la altura mínima del contenedor */
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2"> <!-- Cambiado a col-md-8 para un contenedor más grande -->
            <div class="card user-card">
                <div class="card-body">
                    <h4 class="card-title">Detalles del Usuario</h4>
                    <ol>
                        <li>Nombres: {{ $user->names }}</li>
                        <li>Apellidos: {{ $user->lastnames }}</li>
                        <li>Correo: {{ $user->email }}</li>
                        <li>Dirección: {{ $user->address }}</li>
                        <li>Password: {{ $user->password }}</li>
                    </ol>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Ir al index</a>
                    
                    @if($user->trashed())
                        <form action="{{ route('usuarios.restore', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">Restaurar Usuario</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
