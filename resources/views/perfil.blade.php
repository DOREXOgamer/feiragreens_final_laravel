@extends('layouts.main')

@section('head')
<link rel="stylesheet" href="{{ asset('css/perfil_custom.css') }}">
@endsection

@section('content')
<div class="container perfil-container">
    <h1 class="mb-4">Perfil do Usuário</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="perfil-section mb-5 p-4 border rounded shadow-sm bg-light">
        <h2 class="mb-3">Informações do Perfil</h2>
        <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="name" class="form-label">Nome:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="form-control">
                @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="image" class="form-label">Imagem de Perfil:</label><br>
                @if($user->image)
                    <img src="{{ asset('storage/profile_images/' . $user->image) }}" alt="Imagem de Perfil" class="profile-image-preview rounded mb-3">
                @else
                    <p>Sem imagem de perfil.</p>
                @endif
                <input type="file" id="image" name="image" accept="image/*" class="form-control">
                @error('image')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Atualizar Perfil</button>
        </form>
    </div>

    <hr>

    <div class="produto-section mb-5 p-4 border rounded shadow-sm bg-light">
        <h2 class="mb-3">Adicionar Produto</h2>
        <form action="{{ route('produto.add') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-3">
                <label for="nome" class="form-label">Nome do Produto:</label>
                <input type="text" id="nome" name="nome" value="{{ old('nome') }}" required class="form-control">
                @error('nome')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="preco" class="form-label">Preço:</label>
                <input type="number" step="0.01" id="preco" name="preco" value="{{ old('preco') }}" required class="form-control">
                @error('preco')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="categoria" class="form-label">Categoria:</label>
                <select id="categoria" name="categoria" required class="form-control">
                    <option value="">Selecione uma categoria</option>
                    <option value="Frutas" {{ old('categoria') == 'Frutas' ? 'selected' : '' }}>Frutas</option>
                    <option value="Verduras" {{ old('categoria') == 'Verduras' ? 'selected' : '' }}>Verduras</option>
                    <option value="Hortaliças" {{ old('categoria') == 'Hortaliças' ? 'selected' : '' }}>Hortaliças</option>
                    <option value="Legumes" {{ old('categoria') == 'Legumes' ? 'selected' : '' }}>Legumes</option>
                    <option value="Outros" {{ old('categoria') == 'Outros' ? 'selected' : '' }}>Outros</option>
                </select>
                @error('categoria')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="imagem" class="form-label">Imagem do Produto:</label>
                <input type="file" id="imagem" name="imagem" accept="image/*" class="form-control">
                @error('imagem')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Adicionar Produto</button>
        </form>
    </div>

    <hr>

    <div class="produto-list-section mb-5 p-4 border rounded shadow-sm bg-light">
        <h2 class="mb-3">Meus Produtos</h2>
        @if($produtos->isEmpty())
            <p>Você não possui produtos cadastrados.</p>
        @else
            <div class="list-group">
                @foreach($produtos as $produto)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5>{{ $produto->nome }}</h5>
                            <p>Preço: R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                            <p>Categoria: {{ $produto->categoria }}</p>
                        </div>
                        <div>
                            <a href="{{ route('produto.edit', $produto->id) }}" class="btn btn-primary btn-sm me-2">Editar</a>
                            <form action="{{ route('produto.delete', $produto->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Tem certeza que deseja deletar este produto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Deletar</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <hr>

    <div class="logout-section text-center">
        <form action="{{ route('logout') }}" method="POST" style="display:inline-block; margin-right: 10px;">
            @csrf
            <button type="submit" class="btn btn-danger px-5">Sair</button>
        </form>

        <form action="{{ route('perfil.delete') }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Tem certeza que deseja deletar sua conta? Esta ação não pode ser desfeita.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger px-5">Deletar Conta</button>
        </form>
    </div>
</div>
@endsection
