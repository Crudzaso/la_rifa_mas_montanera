<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Lista de Usuarios</title>
</head>
<body>
<div class="container">
    <h2 class="mt-4">Lista de Usuarios</h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            <strong>Bien hecho!</strong> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <strong>Error!</strong> {{ session('error') }}
        </div>
    @endif

    <!-- Botón de Logout -->
    <form action="{{ route('logout') }}" method="POST" class="mb-3">
        @csrf
        <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
    </form>

    <div class="mb-3">
        <a href="{{ route('usuarios.trashed') }}" class="btn btn-warning">Ver usuarios eliminados</a>
    </div>

    <div class="mb-3">
        <a href="{{ route('usuarios.create') }}" class="btn btn-success">Agregar Usuario</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>NOMBRES</th>
                <th>APELLIDOS</th>
                <th>CORREO</th>
                <th>DIRECCION</th>
                <th>ACCIONES</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
               <tr>
                   <td>{{ $user->id }}</td>
                   <td>{{ $user->names }}</td>
                   <td>{{ $user->lastnames }}</td>
                   <td>{{ $user->email }}</td>
                   <td>{{ $user->address }}</td>
                   <td>
                       <form action="{{ route('usuarios.destroy', $user->id) }}" method="post">
                           @method('DELETE')
                           @csrf
                           <a href="{{ route('usuarios.show', $user->id) }}" class="btn btn-sm btn-info">Detalles</a>
                           <a href="{{ route('usuarios.edit', $user->id) }}" class="btn btn-sm btn-warning">Editar</a>
                           <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                       </form>
                   </td>
               </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $users->links() }}
</div>
</body>
</html>
