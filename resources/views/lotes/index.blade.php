<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Lotes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .btn {
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary { background-color: #007bff; color: white; border: none; }
        .btn-primary:hover { background-color: #0056b3; }
        .btn-danger { background-color: #dc3545; color: white; border: none; }
        .btn-danger:hover { background-color: #c82333; }
        .btn-success { background-color: #28a745; color: white; border: none; }
        .btn-success:hover { background-color: #218838; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        tr:hover { background-color: #f5f5f5; }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 14px;
        }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-danger { background-color: #f8d7da; color: #721c24; }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"],
        input[type="number"],
        input[type="datetime-local"],
        textarea,
        select {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: black;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gerenciamento de Lotes</h1>

        {{-- Mensagens --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button id="btnNovoLote" class="btn btn-primary">Novo Lote</button>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Setor</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Data Início</th>
                    <th>Data Fim</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lotes as $lote)
                    <tr>
                        <td>{{ $lote->id }}</td>
                        <td>{{ $lote->setor->nome ?? 'N/A' }}</td>
                        <td>{{ $lote->nome }}</td>
                        <td>R$ {{ number_format($lote->preco, 2, ',', '.') }}</td>
                        <td>{{ $lote->quantidade }}</td>
                        <td>{{ \Carbon\Carbon::parse($lote->data_inicio)->format('d/m/Y H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($lote->data_fim)->format('d/m/Y H:i') }}</td>
                        <td>
                            <button onclick="editarLote({{ $lote->id }})" class="btn btn-primary">Editar</button>
                            <form action="{{ route('lotes.destroy', $lote->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Deseja excluir este lote?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Modal --}}
        <div id="loteModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 id="modalTitle">Novo Lote</h2>
                <form id="loteForm" method="POST">
                    @csrf
                    <input type="hidden" id="lote_id" name="lote_id">

                    <div class="form-group">
                        <label for="setor_id">Setor:</label>
                        <select name="setor_id" id="setor_id" required>
                            <option value="">Selecione</option>
                            @foreach($setores as $setor) {{-- Pass $setores from controller --}}
                                <option value="{{ $setor->id }}">{{ $setor->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nome">Nome do Lote:</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>

                    <div class="form-group">
                        <label for="preco">Preço:</label>
                        <input type="number" step="0.01" id="preco" name="preco" required>
                    </div>

                    <div class="form-group">
                        <label for="quantidade">Quantidade:</label>
                        <input type="number" id="quantidade" name="quantidade" required>
                    </div>

                    <div class="form-group">
                        <label for="data_inicio">Data Início:</label>
                        <input type="datetime-local" id="data_inicio" name="data_inicio" required>
                    </div>

                    <div class="form-group">
                        <label for="data_fim">Data Fim:</label>
                        <input type="datetime-local" id="data_fim" name="data_fim" required>
                    </div>

                    <button type="submit" class="btn btn-success">Salvar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById("loteModal");
        const btn = document.getElementById("btnNovoLote");
        const span = document.getElementsByClassName("close")[0];
        const form = document.getElementById("loteForm");
        const modalTitle = document.getElementById("modalTitle");

        btn.onclick = function() {
            modal.style.display = "block";
            form.action = "{{ route('lotes.store') }}";
            form.reset();
            document.getElementById("lote_id").value = "";
            modalTitle.textContent = "Novo Lote";
            // Remove the _method input if it exists from a previous edit
            const existingMethodInput = form.querySelector('input[name="_method"]');
            if (existingMethodInput) {
                existingMethodInput.remove();
            }
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function editarLote(id) {
            fetch(`/lotes/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                document.getElementById("lote_id").value = data.id.toString();
                document.getElementById("setor_id").value = data.setor_id.toString();
                document.getElementById("nome").value = data.nome;
                document.getElementById("preco").value = parseFloat(data.preco).toFixed(2); // Ensure correct format for number input
                document.getElementById("quantidade").value = data.quantidade;

                // Format datetime for input[type="datetime-local"]
                // The backend typically returns ISO 8601 format (e.g., "YYYY-MM-DDTHH:MM:SS")
                // Use a helper function for consistent formatting
                document.getElementById("data_inicio").value = formatDateTimeLocal(data.data_inicio);
                document.getElementById("data_fim").value = formatDateTimeLocal(data.data_fim);

                document.getElementById("descricao").value = data.descricao;

                modalTitle.textContent = `Editar Lote: ${data.nome}`;
                form.action = `/lotes/${data.id}`;

                // Add or update the _method input for PUT request
                let methodInput = form.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement("input");
                    methodInput.type = "hidden";
                    methodInput.name = "_method";
                    form.appendChild(methodInput);
                }
                methodInput.value = "PUT";

                modal.style.display = "block";
            })
            .catch(error => console.error('Erro ao buscar dados do lote:', error));
        }

        // Helper function to format datetime strings for datetime-local input
        function formatDateTimeLocal(dateTimeString) {
            if (!dateTimeString) return '';
            const date = new Date(dateTimeString);
            const year = date.getFullYear();
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const day = date.getDate().toString().padStart(2, '0');
            const hours = date.getHours().toString().padStart(2, '0');
            const minutes = date.getMinutes().toString().padStart(2, '0');
            // datetime-local input does not support seconds
            return `${year}-${month}-${day}T${hours}:${minutes}`;
        }
    </script>
</body>
</html>