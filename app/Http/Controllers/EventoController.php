<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Produtor;
use App\Models\Usuario;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::with(['produtor.usuario'])->get();
        $produtores = Produtor::with('usuario')->get();
        $usuarios = Usuario::select('id', 'nome')->get();

        return view('eventos', compact('eventos', 'produtores', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produtor_id' => 'required|exists:produtores,id',
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'data_evento' => 'required|string',
            'banner_url' => 'nullable|url',
            'localizacao' => 'required|string|max:255'
        ]);

        Evento::create($request->all());

        return redirect()->route('eventos.index')
                         ->with('success', 'Evento criado com sucesso!');
    }

    public function edit($id)
    {
        $evento = Evento::with('produtor.usuario')->findOrFail($id);
        return response()->json($evento);
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
