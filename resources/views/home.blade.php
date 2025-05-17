@extends('layouts.main')

@section('content')

<section id="home" class="d-flex flex-column align-items-center">
    @if (session('error'))
        <div class="alert alert-danger w-100 text-center">{{ session('error') }}</div>
    @endif

    {{-- Carrossel de imagens --}}
    @isset($imagens)
        @include('partials.carousel', ['imagens' => $imagens])
    @else
        <div class="alert alert-warning w-100 text-center">Nenhuma imagem disponível</div>
    @endisset
</section>

<hr class="my-4 w-100">

<section class="promocao-header text-center mb-3">
    <h2 class="promocao-header--title mb-1">PROMOÇÕES</h2>
    <p>Produtos</p>
</section>

<div class="container container-principal d-flex flex-wrap justify-content-center gap-3">
    @forelse($produtos ?? [] as $produto)
        <div class="produto-container-principal card p-3" style="width: 18rem;">
            <a href="{{ route('produto.show', $produto->id) }}" aria-label="Ver detalhes do produto {{ $produto->nome }}">
                <img src="{{ asset('storage/profile_images/' . $produto->imagem) }}" 
                     alt="{{ $produto->nome }}" 
                     class="card-img-top img-fluid" 
                     onerror="this.onerror=null; this.src='{{ asset('images/default-product.jpg') }}';" />
            </a>
            <div class="card-body text-center">
                <h5 class="card-title">{{ $produto->nome }}</h5>
                <p class="card-text">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                <p class="card-text text-muted">{{ $produto->categoria }}</p>
            </div>
        </div>
    @empty
        <div class="produto-container-principal w-100 text-center">
            <p>Nenhum produto encontrado.</p>
        </div>
    @endforelse
</div>

@endsection
