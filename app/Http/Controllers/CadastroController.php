<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CadastroController extends Controller
{
    // Mostra o formulário de cadastro
    public function showForm()
    {
        return view('cadastro'); // Assumindo que seu arquivo de view se chama 'cadastro.blade.php'
    }

    // Processa o formulário de cadastro
    public function processForm(Request $request)
    {
        // Validação dos dados
        $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'documento' => 'required|string|max:20',
            'senha' => 'required|string|min:8|confirmed',
        ]);

        // Aqui você processaria o cadastro (salvar no banco de dados, etc.)
        // Por enquanto, vamos apenas redirecionar com uma mensagem de sucesso

        return redirect()->route('cadastro.show')
                         ->with('success', 'Cadastro realizado com sucesso!');
    }
}