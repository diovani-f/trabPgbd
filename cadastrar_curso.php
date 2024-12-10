<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Curso</title>
    <style>
        /* Reset básico para remover margens e paddings padrão */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Estilização do corpo da página */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ece9e6, #ffffff);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Container centralizado com sombra e bordas arredondadas */
        .container {
            background-color: #ffffff;
            padding: 40px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
        }

        /* Título estilizado */
        .container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333333;
            font-size: 1.8em;
        }

        /* Estilização do formulário */
        .container form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Estilização dos labels */
        .container label {
            font-size: 1em;
            color: #555555;
            margin-bottom: 5px;
            text-align: left;
        }

        /* Estilização dos inputs */
        .container input[type="text"],
        .container input[type="number"],
        .container select {
            padding: 12px 15px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        /* Mudança de borda ao focar nos inputs */
        .container input[type="text"]:focus,
        .container input[type="number"]:focus,
        .container select:focus {
            border-color: #4CAF50;
            outline: none;
        }

        /* Estilização do botão */
        .container button {
            padding: 15px;
            background-color: #4CAF50;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        /* Efeito de hover no botão */
        .container button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        /* Link de volta estilizado como botão secundário */
        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #4CAF50;
            font-size: 1em;
            border: 2px solid #4CAF50;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        /* Efeito de hover no link */
        .back-link:hover {
            background-color: #4CAF50;
            color: #ffffff;
        }

        /* Mensagem de erro estilizada */
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Mensagem de sucesso estilizada */
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Responsividade para dispositivos menores */
        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
            }

            .container h2 {
                font-size: 1.5em;
            }

            .container button,
            .back-link {
                font-size: 1em;
                padding: 12px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cadastrar Curso</h2>

        <form method="POST" action="back/controlador.php">
            <input type="hidden" name="criarCurso" value="criarCurso">

            <div>
                <label for="nome">Nome do Curso:</label>
                <input type="text" id="nome" name="nome_curso" placeholder="Digite o nome do curso" required>
            </div>

            <div>
                <label for="coordenador">ID do Coordenador:</label>
                <input type="number" id="coordenador" name="id_coordenador" placeholder="Digite o ID do coordenador" required>
            </div>

            <button type="submit">Cadastrar Curso</button>
        </form>

        <a href="painel_admin.php" class="back-link">Voltar ao Painel ADM</a>
    </div>
</body>
</html>

<script>
        // Função para exibir o alert com a mensagem da URL
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const mensagem = urlParams.get('mensagem');

            if (status && mensagem) {
                if (status === 'sucesso') {
                    alert('Sucesso: ' + decodeURIComponent(mensagem));
                } else if (status === 'erro') {
                    alert('Erro: ' + decodeURIComponent(mensagem));
                }
            }
        }
    </script>
