<?php

namespace App\Http\Controllers;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


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
    $request->validate([
        'nome' => 'required|string|max:255',
        'email' => 'required|string|max:255',
        'telefone' => 'required|string|max:20',
        'cpf/cnpj' => 'required|string|max:20',
        'senha' => 'required|string',
    ]);

    Usuario::create([
        'nome' => $request->input('nome'),
        'email' => $request->input('email'),
        'telefone' => $request->input('telefone'),
        'cpf/cnpj' => $request->input('documento'),
        'senha' => Hash::make($request->input('senha')),
    ]);

    return redirect()->route('cadastro.show')
                     ->with('success', 'Cadastro realizado com sucesso!');
}
}