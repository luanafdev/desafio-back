<?php
namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Setor;
use App\Models\Lote;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show($id)
    {
        $evento = Evento::findOrFail($id);
        $setores = Setor::where('evento_id', $id)->get();
        $lotes = Lote::whereIn('setor_id', $setores->pluck('id'))
                      ->get();

        return view('checkout.index', compact('evento', 'setores', 'lotes'));
    }

    public function process(Request $request, $id)
    {
        $request->validate([
            'setor_id' => 'required|exists:setores,id',
            'lote_id' => 'required|exists:lotes,id',
            'quantidade' => 'required|integer|min:1',
        ]);

        // Aqui você pode criar a lógica de criação do pedido, geração de pagamento, etc.

        return back()->with('success', 'Compra realizada com sucesso!');
    }
}
