<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request; // Adicionei esta importação
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Exibe a página inicial
     */
   public function home()
    {
        $produtos = Produto::all() ?? []; // Garante que sempre será uma coleção
        
        $jsonPath = public_path('imagens/imagens.json');
        $imagens = []; // Valor padrão
        
        if (File::exists($jsonPath)) {
            $imagens = json_decode(File::get($jsonPath), true) ?? [];
        }

        return view('home', [
            'produtos' => $produtos,
            'imagens' => $imagens
        ]);
    }

    /**
     * Processa a busca de produtos
     */
    public function buscar(Request $request)
    {
        $termo = $request->input('termo', ''); // Valor padrão vazio
        
        $produtos = Produto::where('nome', 'LIKE', "%{$termo}%")
            ->get();

        return view('busca', compact('produtos', 'termo'));
    }

    /**
     * Área do painel administrativo
     */
    public function dashboard()
    {
        return view('dashboard');
    }

    /**
     * Exibe a página de perfil do usuário
     */
    public function perfil()
    {
        $user = Auth::user();
        $produtos = $user->produtos ?? collect();
        return view('perfil', compact('user', 'produtos'));
    }

    /**
     * Atualiza o perfil do usuário (nome e imagem)
     */
    public function updatePerfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048', // max 2MB
        ]);

        $user->name = $request->input('name');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($user->image) {
                \Illuminate\Support\Facades\Storage::delete('public/profile_images/' . $user->image);
            }
            $path = $request->file('image')->store('public/profile_images');
            $user->image = basename($path);
        }

        $user->save();

        return redirect()->route('perfil')->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Adiciona um novo produto
     */
    public function addProduto(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric',
            'categoria' => 'required|string|max:255',
            'imagem' => 'nullable|image|max:2048',
        ]);

        $produto = new Produto();
        $produto->nome = $request->input('nome');
        $produto->preco = $request->input('preco');
        $produto->categoria = $request->input('categoria');
        $produto->user_id = auth()->id();

        if ($request->hasFile('imagem')) {
            // Save product images in profile_images folder as well
            $path = $request->file('imagem')->store('public/profile_images');
            $produto->imagem = basename($path);
        }

        $produto->save();

        return redirect()->route('perfil')->with('success', 'Produto adicionado com sucesso!');
    }

    public function editProduto($id)
    {
        $produto = Produto::findOrFail($id);

        if ($produto->user_id !== auth()->id()) {
            abort(403, 'Acesso negado');
        }

        return view('produto.edit', compact('produto'));
    }

    public function updateProduto(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        if ($produto->user_id !== auth()->id()) {
            abort(403, 'Acesso negado');
        }

        $request->validate([
            'nome' => 'required|string|max:255',
            'preco' => 'required|numeric',
            'categoria' => 'required|string|max:255',
            'imagem' => 'nullable|image|max:2048',
        ]);

        $produto->nome = $request->input('nome');
        $produto->preco = $request->input('preco');
        $produto->categoria = $request->input('categoria');

        if ($request->hasFile('imagem')) {
            if ($produto->imagem) {
                \Illuminate\Support\Facades\Storage::delete('public/profile_images/' . $produto->imagem);
            }
            // Save product images in profile_images folder as well
            $path = $request->file('imagem')->store('public/profile_images');
            $produto->imagem = basename($path);
        }

        $produto->save();

        return redirect()->route('perfil')->with('success', 'Produto atualizado com sucesso!');
    }

    public function deleteProduto($id)
    {
        $produto = Produto::findOrFail($id);

        if ($produto->user_id !== auth()->id()) {
            abort(403, 'Acesso negado');
        }

        if ($produto->imagem) {
            \Illuminate\Support\Facades\Storage::delete('public/profile_images/' . $produto->imagem);
        }

        $produto->delete();

        return redirect()->route('perfil')->with('success', 'Produto deletado com sucesso!');
    }

    public function deleteAccount()
    {
        $user = auth()->user();

        // Delete user's products and their images
        foreach ($user->produtos as $produto) {
            if ($produto->imagem) {
                \Illuminate\Support\Facades\Storage::delete('public/product_images/' . $produto->imagem);
            }
            $produto->delete();
        }

        // Delete user profile image if exists
        if ($user->image) {
            \Illuminate\Support\Facades\Storage::delete('public/profile_images/' . $user->image);
        }

        // Delete the user account
        $user->delete();

        // Logout the user
        auth()->logout();

        return redirect('/')->with('success', 'Conta deletada com sucesso.');
    }

    /**
     * Exibe os detalhes de um produto
     */
    public function showProduto($id)
    {
        $produto = Produto::findOrFail($id);
        return view('produto.show', compact('produto'));
    }
}
