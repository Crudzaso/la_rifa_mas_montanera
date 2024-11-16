<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mi Aplicación')</title>

    <!-- CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @livewireStyles <!-- Estilos de Livewire -->
</head>
<body>
    <div class="min-h-screen bg-light">
        <header class="bg-dark text-white p-3">
            <div class="container">
                <nav class="d-flex justify-content-between">
                    <div>
                        <a href="/" class="text-white text-decoration-none me-3">Inicio</a>
                        @auth
                            <a href="{{ route('usuarios.index') }}" class="text-white text-decoration-none me-3">Ir a inicio</a>
                            <a href="{{ route('usuarios.index') }}" class="text-white text-decoration-none">Usuarios</a>
                        @endauth
                    </div>
                    <div>
                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Cerrar Sesión</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Iniciar Sesión</a>
                            <a href="{{ route('register') }}" class="btn btn-sm btn-secondary">Registrarse</a>
                        @endauth
                    </div>
                </nav>
            </div>
        </header>

        <main class="container mt-5">
            @yield('content') <!-- Aquí se insertará el contenido de las vistas -->
        </main>
    </div>

    @stack('modals') <!-- Modales opcionales -->
    @livewireScripts <!-- Scripts de Livewire -->

    <!-- JS de Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>