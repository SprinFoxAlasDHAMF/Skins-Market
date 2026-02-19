<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Mi perfil</h2>

    <img src="{{ asset('storage/' . $usuario->foto_perfil) }}" width="150">

    <form method="POST" enctype="multipart/form-data">
        @csrf

        <label>Nombre</label>
        <input type="text" name="nombre" value="{{ $usuario->nombre }}">

        <label>Email</label>
        <input type="email" value="{{ $usuario->email }}" disabled>

        <label>Foto de perfil</label>
        <input type="file" name="foto">

        <button>Guardar cambios</button>
    </form>

    <hr>

    <h3>Saldo</h3>
    <p>{{ $usuario->saldo }} €</p>
    <a href="/recargar">Recargar saldo</a>  
</body>
</html>

