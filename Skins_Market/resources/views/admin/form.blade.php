@extends('layouts.app')

@section('content')
<div class="container">

    <h2>{{ $item->id ? 'Editar Item' : 'Subir Item' }}</h2>

    <form action="{{ route('admin.save') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $item->id ?? '' }}">

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ $item->nombre ?? '' }}">
        </div>

        <div class="mb-3">
            <label>Color</label>
            <input type="text" name="color" class="form-control" value="{{ $item->color ?? '' }}">
        </div>

        <div class="mb-3">
            <label>Precio</label>
            <input type="number" step="0.01" name="precio" class="form-control" value="{{ $item->precio ?? '' }}">
        </div>

        <div class="mb-3">
            <label>Tipo</label>
            <select name="tipo" class="form-control">
                <option value="arma" {{ ($item->tipo ?? '')=='arma' ? 'selected' : '' }}>Arma</option>
                <option value="guantes" {{ ($item->tipo ?? '')=='guantes' ? 'selected' : '' }}>Guantes</option>
                <option value="agente" {{ ($item->tipo ?? '')=='agente' ? 'selected' : '' }}>Agente</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Calidad</label>
            <select name="calidad_id" class="form-control">
                @foreach($calidades as $calidad)
                    <option value="{{ $calidad->id }}" {{ ($item->calidad_id ?? '')==$calidad->id ? 'selected' : '' }}>
                        {{ $calidad->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Categoría</label>
            <select name="categoria_id" class="form-control">
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}" {{ ($item->categoria_id ?? '')==$categoria->id ? 'selected' : '' }}>
                        {{ $categoria->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Exterior</label>
            <select name="exterior_id" class="form-control">
                @foreach($exteriores as $exterior)
                    <option value="{{ $exterior->id }}" {{ ($item->exterior_id ?? '')==$exterior->id ? 'selected' : '' }}>
                        {{ $exterior->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ $item->id ? 'Actualizar' : 'Subir' }}</button>
    </form>

    @if($item->id)
    <form action="{{ route('admin.destroy', $item->id) }}" method="POST" class="mt-2">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Eliminar</button>
    </form>
    @endif
</div>
@endsection
