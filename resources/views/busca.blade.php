@extends('layouts.main')

@section('content')
<div class="container">
    <h1>Resultados da Busca</h1>

    @if($produtos->isEmpty())
        <p>Nenhum produto encontrado para "{{ $termo }}"</p>
    @else
        <ul>
            @foreach($produtos as $produto)
                <li>
                    <a href="{{ route('produto.show', $produto->id) }}">{{ $produto->nome }}</a> - R$ {{ number_format($produto->preco, 2, ',', '.') }}
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
