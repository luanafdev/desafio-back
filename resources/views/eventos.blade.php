<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Eventos</title>
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
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
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
        .btn-primary {
            background-color: #007bff;
            color: white;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-success {
            background-color: #28a745;
            color: white;
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
        }
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
        tr:hover {
            background-color: #f5f5f5;
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
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"],
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
            overflow: auto;
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
        .actions {
            display: flex;
            gap: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gerenciamento de Eventos</h1>
    
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

        <button id="btnNovoEvento" class="btn btn-primary">Novo Evento</button>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produtor</th>
                    <th>Nome</th>
                    <th>Localização</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eventos as $evento)
                
                <tr>
                    <td>{{ $evento->id }}</td>
                    <td>{{ $evento->produtor->usuario->nome ?? 'N/A' }}</td>
                    <td>{{ $evento->nome }}</td>
                    <td>{{ $evento->localizacao }}</td>
                    <td>{{ $evento->descricao }}</td>
                    <td>
                        <button onclick="editarEvento({{ $evento->id }})" class="btn btn-primary">Editar</button>
                        <form action="{{ route('eventos.destroy', $evento->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Deseja excluir este evento?')">Excluir</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal -->
        <div id="eventoModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 id="modalTitle">Novo Evento</h2>
                <form id="eventoForm" method="POST">
                    @csrf
                    <input type="hidden" id="evento_id" name="evento_id">

                    <div class="form-group">
                        <label for="produtor_id">Produtor:</label>
                        <select name="produtor_id" id="produtor_id" required>
                            @foreach($produtores as $produtor)
                                <option value="{{ $produtor->id }}">{{ $produtor->usuario->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nome">Nome do Evento:</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>

                    <div class="form-group">
                        <label for="nome">Data do Evento:</label>
                        <input type="date" id="data_evento" name="data_evento" required>
                    </div>

                    <div class="form-group">
                        <label for="descricao">Descrição:</label>
                        <input type="text" id="descricao" name="descricao" required>
                    </div>

                    <div class="form-group">
                        <label for="banner_url">URL do Banner:</label>
                        <input type="text" id="banner_url" name="banner_url">
                    </div>

                    <div class="form-group">
                        <label for="localizacao">Localização:</label>
                        <input type="text" id="localizacao" name="localizacao" required>
                    </div>

                    <button type="submit" class="btn btn-success">Salvar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById("eventoModal");
        const btn = document.getElementById("btnNovoEvento");
        const span = document.getElementsByClassName("close")[0];
        const form = document.getElementById("eventoForm");
        const modalTitle = document.getElementById("modalTitle");

        btn.onclick = function() {
            modal.style.display = "block";
            form.action = "{{ route('eventos.store') }}";
            form.reset();
            document.getElementById("evento_id").value = "";
            document.getElementById("produtor_id").value = "";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function editarEvento(id) {
            fetch(`/eventos/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                document.getElementById("evento_id").value = data.id;
                document.getElementById("produtor_id").value = data.produtor_id;
                document.getElementById("nome").value = data.nome;
                document.getElementById("descricao").value = data.descricao;
                document.getElementById("banner_url").value = data.banner_url;
                document.getElementById("localizacao").value = data.localizacao;
                document.getElementById("data_evento").value = data.data_evento;

                modalTitle.textContent = `Editar Evento: ${data.nome}`;
                form.action = `/eventos/${data.id}`;
                if (!form.querySelector('input[name="_method"]')) {
                    const methodInput = document.createElement("input");
                    methodInput.type = "hidden";
                    methodInput.name = "_method";
                    form.appendChild(methodInput);
                }
                form.querySelector('input[name="_method"]').value = "PUT";

                modal.style.display = "block";
            });
        }
    </script>
</body>
</html>
