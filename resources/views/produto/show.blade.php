@extends('layouts.main')

@section('head')
<link rel="stylesheet" href="{{ asset('css/produto_show.css') }}">
@endsection

@section('content')
<div class="container produto-detalhes-container">
    <h1>{{ $produto->nome }}</h1>

    @if($produto->imagem)
        <img src="{{ asset('storage/product_images/' . $produto->imagem) }}" alt="Imagem do produto {{ $produto->nome }}" class="produto-imagem">
    @endif

    <p><strong>Preço:</strong> R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
    <p><strong>Categoria:</strong> {{ $produto->categoria }}</p>

    <a href="{{ route('home') }}" class="btn btn-secondary">Voltar para a lista de produtos</a>

    <form action="{{ route('cart.add', $produto->id) }}" method="POST" style="display:inline-block; margin-left: 10px;">
        @csrf
        <button type="submit" class="btn btn-success">Adicionar ao Carrinho</button>
    </form>
</div>
@endsection
