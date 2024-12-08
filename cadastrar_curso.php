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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Curso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 40%;
            margin: 0 auto;
            margin-top: 50px;
            padding: 30px;
            background-color: #fff;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            text-align: left;
            color: #555;
            font-weight: bold;
            font-size: 1em;
        }

        input[type="text"] {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        button:hover {
            background-color: #45a049;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #4CAF50;
            font-size: 1.1em;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .header {
            width: 100%;
            background-color: #4CAF50;
            padding: 10px 0;
            text-align: center;
            color: white;
            margin-bottom: 20px;
            font-size: 1.5em;
        }

        .footer {
            margin-top: 30px;
            font-size: 0.9em;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="header">
        Painel Administrativo - Cadastro de Cursos
    </div>

    <div class="container">
        <h2>Cadastrar Curso</h2>
        <form method="POST" action="back/controlador.php">
            <input type="hidden" name="criarCurso" value="criarCurso">

            <label for="nome">Nome do Curso:</label>
            <input type="text" id="nome" name="nome_curso" placeholder="Digite o nome do curso" required>

            <label for="coordenador">ID do Coordenador:</label>
            <input type="text" id="coordenador" name="id_coordenador" placeholder="Digite o ID do coordenador" required>

            <button type="submit">Cadastrar Curso</button>
        </form>

        <a href="painel_admin.php" class="back-link">Voltar ao Painel ADM</a>
    </div>

    <div class="footer">
        &copy; 2024 Sistema Acadêmico. Todos os direitos reservados.
    </div>

</body>
</html>
