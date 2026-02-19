<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skins de CS:GO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Skins de CS:GO</h1>

    <!-- BOTÓN SUBIR ITEM PARA ADMIN -->
    @auth
        @if(auth()->user()->role === 'admin')
            <div class="mb-4">
            <a href="{{ route('admin.form') }}" class="btn btn-success">Subir Skin / Item</a>
            </div>
        @endif
    @endauth

    <div class="row mb-4">
        <!-- ==========================
             FILTROS
        ========================== -->
        <div class="col-md-4">
            <h4>Filtros</h4>
            <form method="GET">
                <!-- Filtros existentes -->
                <div class="mb-3">
                    <label for="calidad_id" class="form-label">Calidad</label>
                    <select id="calidad_id" name="calidad_id" class="form-control">
                        <option value="">Seleccionar calidad</option>
                        @foreach($calidades as $calidad)
                            <option value="{{ $calidad->id }}" {{ request('calidad_id') == $calidad->id ? 'selected' : '' }}>
                                {{ $calidad->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <select id="tipo" name="tipo" class="form-control">
                        <option value="">Seleccionar tipo</option>
                        <option value="arma" {{ request('tipo') == 'arma' ? 'selected' : '' }}>Arma</option>
                        <option value="guantes" {{ request('tipo') == 'guantes' ? 'selected' : '' }}>Guantes</option>
                        <option value="agente" {{ request('tipo') == 'agente' ? 'selected' : '' }}>Agente</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="categoria_id" class="form-label">Categoría</label>
                    <select id="categoria_id" name="categoria_id" class="form-control">
                        <option value="">Seleccionar categoría</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exterior_id" class="form-label">Exterior</label>
                    <select id="exterior_id" name="exterior_id" class="form-control">
                        <option value="">Seleccionar exterior</option>
                        @foreach($exteriores as $exterior)
                            <option value="{{ $exterior->id }}" {{ request('exterior_id') == $exterior->id ? 'selected' : '' }}>
                                {{ $exterior->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="color" class="form-label">Color</label>
                    <input type="text" id="color" name="color" class="form-control" value="{{ request('color') }}" placeholder="Ej: Rojo, Azul">
                </div>
                <div class="mb-3">
                    <label for="precio_min" class="form-label">Precio mínimo</label>
                    <input type="number" id="precio_min" name="precio_min" class="form-control" value="{{ request('precio_min') }}">
                </div>
                <div class="mb-3">
                    <label for="precio_max" class="form-label">Precio máximo</label>
                    <input type="number" id="precio_max" name="precio_max" class="form-control" value="{{ request('precio_max') }}">
                </div>
                <button type="submit" class="btn btn-primary">Aplicar filtros</button>
            </form>
        </div>

        <!-- ==========================
             RESULTADOS
        ========================== -->
        <div class="col-md-8">
            <h4>Skins Disponibles</h4>
            <div class="row">
                @forelse($items as $skin)
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <img src="{{ $skin['foto'] ?? 'https://via.placeholder.com/150' }}" class="card-img-top" alt="{{ $skin['nombre'] }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $skin['nombre'] }}</h5>
                                <p>Precio: ${{ number_format($skin['precio'], 2) }}</p>
                                <p>Calidad: {{ $skin['calidad'] }}</p>
                                <p>Categoría: {{ $skin['categoria'] }}</p>
                                <p>Exterior: {{ $skin['exterior'] }}</p>
                                <p>Color: {{ $skin['color'] }}</p>
                                <a href="{{ route('skins.show', $skin['id']) }}" class="btn btn-primary mt-2">Ver detalles</a>

                                <!-- BOTONES DE ADMIN -->
                                @auth
                                    @if(auth()->user()->role === 'admin')
                                        <div class="mt-2">
                                        <a href="{{ route('admin.form', $skin['id']) }}" class="btn btn-warning btn-sm">Editar</a>
                                            <form action="{{ route('admin.destroy', $skin['id']) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm">Eliminar</button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center">No se encontraron skins con los filtros aplicados.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
</body>
</html>
