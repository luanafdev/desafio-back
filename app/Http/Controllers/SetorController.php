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
        $setor = Setor::with('evento')->findOrFail($id);

        return response()->json($setor);
    }

    public function update(Request $request, $id)
    {
        $setor = Setor::findOrFail($id);
        
         $request->validate([
            'evento_id' => 'required',
            'nome' => 'required',
            'descricao' => 'required',
        ]);

        $setor->update($request->all());

        return redirect()->route('setores.index')
                         ->with('success', 'Setor atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $setor = Setor::findOrFail($id);
        $setor->delete();

        return redirect()->route('setores.index')
                         ->with('success', 'Setor excluído com sucesso!');
    }
}
