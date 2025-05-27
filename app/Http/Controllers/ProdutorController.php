<?php

namespace App\Http\Controllers;

use App\Models\Produtor;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ProdutorController extends Controller
{
    public function index()
    {
        $produtores = Produtor::with(['usuario' => function($query) {
            $query->select('id', 'nome'); // Garante que traz apenas os campos necessários
        }])->get();

        $usuarios = Usuario::select('id', 'nome')->get(); // Seleciona apenas id e nome

        return view('produtores', compact('produtores', 'usuarios'));
    }

    public function edit($id)
    {
        $produtor = Produtor::with('usuario')->findOrFail($id);
        return response()->json([
            'id' => $produtor->id,
            'usuario_id' => $produtor->usuario_id,
            'nome_empresa' => $produtor->nome_empresa,
            'usuario_nome' => $produtor->usuario->nome // Adiciona o nome para exibição
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'nome_empresa' => 'required|string|max:255',
        ]);

        Produtor::create([
            'usuario_id' => $request->usuario_id,
            'nome_empresa' => $request->nome_empresa,
        ]);

        return redirect()->route('produtores.index')
                         ->with('success', 'Produtor criado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $produtor = Produtor::findOrFail($id);
        
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'nome_empresa' => 'required|string|max:255',
        ]);

        $produtor->update([
            'usuario_id' => $request->usuario_id,
            'nome_empresa' => $request->nome_empresa,
        ]);

        return redirect()->route('produtores')
                         ->with('success', 'Produtor atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $produtor = Produtor::findOrFail($id);
        $produtor->delete();

        return redirect()->route('produtores.index')
                         ->with('success', 'Produtor excluído com sucesso!');
    }
}