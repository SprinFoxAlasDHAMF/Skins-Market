<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi aplicación Laravel</title>
    <!-- Aquí puedes agregar tus estilos CSS o frameworks -->
</head>
<body>
    <nav>
        <!-- Aquí va tu barra de navegación -->
    </nav>

    <div class="container">
        @yield('content')  <!-- Aquí se inyecta el contenido específico de cada vista -->
    </div>

    <!-- Aquí puedes agregar tus scripts -->
</body>
</html>
