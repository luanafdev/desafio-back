<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Produtor;
use App\Models\Usuario;
use App\Models\Setor;
use Illuminate\Http\Request;

class SetorController extends Controller
{
    public function index()
    {
        $setores = Setor::with('evento')->get();
        $eventos = Evento::all();
        return view('setores', compact('setores', 'eventos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'evento_id' => 'required',
            'nome' => 'required',
            'descricao' => 'required',
        ]);

        Setor::create($request->all());

        return redirect()->route('setores.index')
                         ->with('success', 'Setor criado com sucesso!');
    }

    public function edit($id)
    {
        $setor = Setor::with('setores.evento')->findOrFail($id);
        return response()->json($setor);
    }

    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);
        
        $request->validate([
            'produtor_id' => 'required|exists:produtores,id',
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'data_evento' => 'required|string',
            'banner_url' => 'nullable|url',
            'localizacao' => 'required|string|max:255'
        ]);

        $evento->update($request->all());

        return redirect()->route('eventos.index')
                         ->with('success', 'Evento atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $evento = Evento::findOrFail($id);
        $evento->delete();

        return redirect()->route('eventos.index')
                         ->with('success', 'Evento excluído com sucesso!');
    }
}
