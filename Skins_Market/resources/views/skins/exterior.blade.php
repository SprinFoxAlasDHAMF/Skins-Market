@extends('layouts.app')

@section('content')
<div class="container mt-5">
    @php
        $exterior = \App\Models\Exterior::find($exterior_id);
        $item = \App\Models\Item::find($item_id);
    @endphp

    <h2>{{ $item->nombre }} - Exterior: {{ $exterior->nombre }}</h2>

    @if($armas->count() == 0)
        <div class="alert alert-info mt-3">
            Todavía no hay armas con este exterior.
        </div>
    @else
        <div class="row">
            @foreach($armas as $arma)
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="{{ $arma->item->foto }}" class="card-img-top" alt="{{ $arma->item->nombre }}">
                        <div class="card-body">
                            <h5>{{ $arma->item->nombre }}</h5>
                            <p>Precio: ${{ $arma->item->precio }}</p>
                            <p>Categoría: {{ $arma->categoria->nombre }}</p>

                            @if($arma->pegatinas->count())
                                <p>Pegatinas:</p>
                                @foreach($arma->pegatinas as $pegatina)
                                    <span class="badge bg-warning">{{ $pegatina->modoPegatina->nombre }}</span>
                                @endforeach
                            @endif

                            <form action="#" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Comprar</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
