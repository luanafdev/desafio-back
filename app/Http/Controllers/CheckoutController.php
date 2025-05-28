<?php
namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Setor;
use App\Models\Lote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    // Dados do pedido
    $quantidade = $request->quantidade;
    $valorUnitario = 1000; // Exemplo: R$10,00 em centavos
    $valorTotal = $quantidade * $valorUnitario;

     $companyId = env('PAGGUE_COMPANY_ID');
    $signature = env('PAGGUE_SIGNATURE');

    $response = Http::withHeaders([
        'X-Company-ID' => $companyId,
        'Signature' => $signature,
    ])->post('https://ms.paggue.io/cashin/api/billing_order', [
        'payer_name' => 'Compra de ingresso',
        'amount' => $valorUnitario * $quantidade, // valor em centavos, R$1,00 = 100
        'external_id' => 'referencia-unica-123abc',
        'description' => 'Compra de ingresso',
        'meta' => [
            'extra_data' => 'qualquer informação',
        ],
    ]);

    if ($response->successful()) {
        $data = $response->json();

        return response()->json([
            'success' => true,
            'qrcode' => $data['payload'] ?? null,
            'image' => $data['qr_code'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Erro ao gerar PIX',
        'error' => $response->body(),
    ], 500);
    
    return back()->with('error', 'Erro ao criar o pedido na Paggue.');
}

}