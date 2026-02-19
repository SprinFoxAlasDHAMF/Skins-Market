@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>{{ $item->nombre }}</h2>
    <h4>Precio: ${{ $item->precio }}</h4>
    <p>Calidad: {{ $item->calidad->nombre }}</p>

    <!-- Botones por exterior -->
    <div class="mb-3">
        <h5>Filtrar por Exterior:</h5>
        @foreach($exteriores as $exterior)
            <a href="{{ route('skins.filtrarExterior', ['item_id' => $item->id, 'exterior_id' => $exterior->id]) }}"
               class="btn btn-outline-primary mb-1">{{ $exterior->nombre }}</a>
        @endforeach
    </div>

    <!-- Pegatinas de la primera arma -->
    @if($item->armas->count())
        @php $firstArma = $item->armas->first(); @endphp
        @if($firstArma->pegatinas->count())
            <h5>Pegatinas:</h5>
            @foreach($firstArma->pegatinas as $pegatina)
                <span class="badge bg-warning">{{ $pegatina->modoPegatina->nombre }}</span>
            @endforeach
        @endif
    @endif

    <!-- Botón comprar -->
    <form action="#" method="POST" class="mt-4">
        @csrf
        <button type="submit" class="btn btn-success btn-lg">Comprar</button>
    </form>
</div>
@endsection
