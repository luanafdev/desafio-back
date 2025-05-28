<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout de Ingresso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        select,
        input[type="number"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #218838;
        }
        .summary {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .summary p {
            margin: 5px 0;
            font-size: 15px;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 14px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Checkout</h1>

        {{-- Mensagens de sucesso --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Mensagens de erro --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Dados do Evento --}}
        <div class="summary">
            <p><strong>Evento:</strong> {{ $evento->nome }}</p>
            <p><strong>Local:</strong> {{ $evento->localizacao }}</p>
            <p><strong>Data:</strong> {{ $evento->data_evento }}</p>
        </div>

        {{-- Formulário de Checkout --}}
        <form action="{{ route('checkout.process', $evento->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="setor">Setor:</label>
                <select name="setor_id" id="setor" required>
                    <option value="">Selecione o setor</option>
                    @foreach ($setores as $setor)
                        <option value="{{ $setor->id }}">{{ $setor->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="lote">Lote:</label>
                <select name="lote_id" id="lote" required>
                    <option value="">Selecione o lote</option>
                    @foreach ($lotes as $lote)
                        <option value="{{ $lote->id }}">
                            {{ $lote->nome }} - R$ {{ number_format($lote->preco, 2, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" name="quantidade" id="quantidade" value="1" min="1" required>
            </div>

            <button action="{{ route('pedido.process', $evento->id) }}" type="submit">Finalizar Compra</button>
        </form>
        @if(session('pixQrCodeUrl'))
            <h3>Pagamento via PIX</h3>
            <p>Escaneie o QR Code abaixo para concluir o pagamento:</p>
            <img src="{{ session('pixQrCodeUrl') }}" alt="QR Code PIX" />
        @endif

    </div>
</body>
</html>
