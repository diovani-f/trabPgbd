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
    <title>Painel ADM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            margin: 20px auto;
            text-align: center;
        }

        h2 {
            font-size: 2em;
            color: #333;
            margin-bottom: 30px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin: 20px 0;
        }

        .menu-item {
            width: 100%;
            max-width: 300px;
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            text-align: center;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-decoration: none;
        }

        .menu-item:hover {
            background-color: #45a049;
        }

        .logout-button {
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
        }

        .logout-button:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Gerenciamento Administrativo</h2>
        <div class="menu">
            <a href="gerenciar_disciplinas.php" class="menu-item">Gerenciar Disciplinas</a>
            <a href="gerenciar_professores.php" class="menu-item">Gerenciar Professores</a>
            <a href="gerenciar_cursos.php" class="menu-item">Gerenciar Cursos</a>
            <a href="gerenciar_coordenadores.php" class="menu-item">Gerenciar Coordenadores</a>
            <a href="gerenciar_salas.php" class="menu-item">Gerenciar Salas</a>
            <a href="gerenciar_aulas.php" class="menu-item">Gerenciar Aulas</a>
            <a href="historico_disciplinas.php" class="menu-item">Histórico de Disciplinas</a>
        </div>

        <!-- Botão de Sair -->
        <a href="logout.php" class="logout-button">Sair</a>
    </div>
</body>
</html>
