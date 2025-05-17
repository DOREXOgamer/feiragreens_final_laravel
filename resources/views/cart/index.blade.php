@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Carrinho de Compras</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(count($cart) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($cart as $id => $item)
                    @php $itemTotal = $item['preco'] * $item['quantity']; $total += $itemTotal; @endphp
                    <tr>
                        <td>
                            @if($item['imagem'])
                                <img src="{{ asset('storage/product_images/' . $item['imagem']) }}" alt="{{ $item['nome'] }}" width="50" class="me-2">
                            @endif
                            {{ $item['nome'] }}
                        </td>
                        <td>R$ {{ number_format($item['preco'], 2, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('cart.update', $id) }}" method="POST" class="d-inline-flex align-items-center">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control form-control-sm me-2" style="width: 70px;">
                                <button type="submit" class="btn btn-primary btn-sm">Atualizar</button>
                            </form>
                        </td>
                        <td>R$ {{ number_format($itemTotal, 2, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td colspan="2"><strong>R$ {{ number_format($total, 2, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    @else
        <p>Seu carrinho está vazio.</p>
    @endif
</div>
@endsection
