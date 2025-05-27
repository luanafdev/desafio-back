<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Setores</title>
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
        <h1>Gerenciamento de Setores</h1>

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

        <button id="btnNovoSetor" class="btn btn-primary">Novo Setor</button>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Evento</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($setores as $setor)
                    <tr>
                        <td>{{ $setor->id }}</td>
                        <td>{{ $setor->evento->nome ?? 'N/A' }}</td>
                        <td>{{ $setor->nome }}</td>
                        <td>{{ $setor->descricao }}</td>
                        <td>
                            <button onclick="editarSetor({{ $setor->id }})" class="btn btn-primary">Editar</button>
                            <form action="{{ route('setores.destroy', $setor->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Deseja excluir este setor?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Modal --}}
        <div id="setorModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 id="modalTitle">Novo Setor</h2>
                <form id="setorForm" method="POST">
                    @csrf
                    <input type="hidden" id="setor_id" name="setor_id">

                    <div class="form-group">
                        <label for="evento_id">Evento:</label>
                        <select name="evento_id" id="evento_id" required>
                            <option value="">Selecione</option>
                            @foreach($eventos as $evento)
                                <option value="{{ $evento->id }}">{{ $evento->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nome">Nome do Setor:</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>

                    <div class="form-group">
                        <label for="descricao">Descrição:</label>
                        <input type="text" id="descricao" name="descricao" required>
                    </div>

                    <button type="submit" class="btn btn-success">Salvar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById("setorModal");
        const btn = document.getElementById("btnNovoSetor");
        const span = document.getElementsByClassName("close")[0];
        const form = document.getElementById("setorForm");
        const modalTitle = document.getElementById("modalTitle");

        btn.onclick = function() {
            modal.style.display = "block";
            form.action = "{{ route('setores.store') }}";
            form.reset();
            document.getElementById("setor_id").value = "";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function editarSetor(id) {
            fetch(`/setores/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                document.getElementById("setor_id").value = data.id.toString();
                document.getElementById("evento_id").value = data.evento_id.toString();
                document.getElementById("nome").value = data.nome;
                document.getElementById("descricao").value = data.descricao;

                modalTitle.textContent = `Editar Setor: ${data.nome}`;
                form.action = `/setores/${data.id}`;
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
