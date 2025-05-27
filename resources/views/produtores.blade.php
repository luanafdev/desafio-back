<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Produtores</title>
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
        <h1>Gerenciamento de Produtores</h1>

        {{-- Mensagens de sucesso --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Mensagens de erro --}}
        @if ($errors->any()))
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="margin-bottom: 20px;">
            <button id="btnNovoProdutor" class="btn btn-primary">Novo Produtor</button>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Nome da Empresa</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produtores as $produtor)
                <tr>
                    <td>{{ $produtor->id }}</td>
                    <td>{{ $produtor->usuario->nome ?? 'N/A' }}</td>
                    <td>{{ $produtor->nome_empresa }}</td>
                    <td class="actions">
                        <button onclick="editarProdutor({{ $produtor->id }})" class="btn btn-primary">Editar</button>
                        <form action="{{ route('produtores.destroy', $produtor->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este produtor?')">Excluir</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal para criar/editar produtor -->
        <div id="produtorModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 id="modalTitle">Novo Produtor</h2>
                <form id="produtorForm" method="POST">
                    @csrf
                    <input type="hidden" id="produtor_id" name="produtor_id">
                    
                    <div class="form-group">
                        <label for="usuario_id">Usuário:</label>
                        <select name="usuario_id" required class="form-control">
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}">{{ $usuario->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nome_empresa">Nome da Empresa:</label>
                        <input type="text" id="nome_empresa" name="nome_empresa" required>
                    </div>

                    <button type="submit" class="btn btn-success">Salvar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Modal handling
        const modal = document.getElementById("produtorModal");
        const btn = document.getElementById("btnNovoProdutor");
        const span = document.getElementsByClassName("close")[0];
        const form = document.getElementById("produtorForm");
        const modalTitle = document.getElementById("modalTitle");

        btn.onclick = function() {
            modal.style.display = "block";
            form.action = "{{ route('produtores.store') }}";
            form.method = "POST";
            modalTitle.textContent = "Novo Produtor";
            form.reset();
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

        function editarProdutor(id) {
    fetch(`/produtores/edit/` + id)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro ao carregar dados');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById("produtor_id").value = data.id;
            document.getElementById("usuario_id").value = data.usuario_id;
            document.getElementById("nome_empresa").value = data.nome_empresa;
            
            // Atualiza o título do modal com o nome do usuário
            document.getElementById("modalTitle").textContent = 
                `Editar Produtor: ${data.usuario_name || 'N/A'}`;
            
            // Configura o form
            const form = document.getElementById("produtorForm");
            form.action = `/produtores/${data.id}`;
            
            // Garante que o método PUT está configurado
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement("input");
                methodInput.type = "hidden";
                methodInput.name = "_method";
                form.appendChild(methodInput);
            }
            methodInput.value = "PUT";
            
            // Exibe o modal
            document.getElementById("produtorModal").style.display = "block";
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao carregar dados do produtor');
        });
    }
    </script>
</body>
</html>