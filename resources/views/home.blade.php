<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Ingressos</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .button {
            border-radius: 9999px; /* Makes them pill-shaped */
            padding: 12px 24px; /* Adjust as needed */
            font-size: 16px;
            font-weight: bold; /* Based on "huruf tebal di wa" which implies bold text */
            cursor: pointer;
            display: inline-flex; /* Allows for centering text if needed */
            align-items: center;
            justify-content: center;
            text-decoration: none; /* If they are links */
            transition: background-color 0.3s ease, border-color 0.3s ease, color 0.3s ease;
        }
        .container {
            text-align: center;
        }
        .button {
            background-color: #EA4C89;
            border-radius: 8px;
            border-style: none;
            box-sizing: border-box;
            color: #FFFFFF;
            cursor: pointer;
            display: inline-block;
            font-family: "Haas Grot Text R Web", "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 14px;
            font-weight: 500;
            height: 40px;
            line-height: 20px;
            list-style: none;
            margin: 0;
            outline: none;
            padding: 10px 16px;
            position: relative;
            text-align: center;
            text-decoration: none;
            transition: color 100ms;
            vertical-align: baseline;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            }

            .button:hover,
            .button:focus {
            background-color: #F082AC;
            }
        .main-button {
            background-color: #2563eb;
            color: white;
            padding: 20px 80px;
            margin-bottom: 40px;
            border: none;
            border-radius: 12px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            transition: background-color 0.3s, transform 0.2s;
        }

        .main-button:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            max-width: 600px;
            margin: 0 auto;
        }

        .small-button {
            background-color: white;
            color: #374151;
            padding: 18px 40px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 22px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            transition: background-color 0.3s, transform 0.2s, border-color 0.3s;
        }

        .small-button:hover {
            background-color: #f9fafb;
            transform: translateY(-2px);
            border-color: #d1d5db;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="flex flex-col items-center space-y-6">
        
        <!-- Botão principal -->
        <a href="{{ route('home') }}">
            <button>
                Comprar Ingresso
            </button> 
        </a>

        <!-- Linha de botões secundários -->
        <div class="grid grid-cols-2 gap-6">

            <a href="{{ route('eventos.index') }}">
                <button> Evento</button>
            </a>
            
            <a href="{{ route('lotes.index') }}">
                <button>Lotes</button> 
            </a>

            <a href="{{ route('produtores.index') }}">
                <button>Produtores</button> 
            </a>

            <a href="{{ route('setores.index') }}">
                 <button>Setores</button>
            </a>
        </div>
    </div>

</body>
</html>
