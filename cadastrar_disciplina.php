<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Incluir o arquivo de conexão
require_once 'back/conexao.php'; // Ajuste o caminho conforme a estrutura do seu projeto

// Conexão com o banco de dados
$conn = conectarBanco();

// Obter dados dinâmicos para salas, professores e cursos
$salas = $conn->query("SELECT numero FROM sala")->fetch_all(MYSQLI_ASSOC);
$professores = $conn->query("SELECT id, nome FROM professor")->fetch_all(MYSQLI_ASSOC);
$cursos = $conn->query("SELECT id, nome FROM curso")->fetch_all(MYSQLI_ASSOC);
$conn->close();

// Mensagem de feedback
$mensagem = "";
$tipo_mensagem = "";
if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1) {
    $mensagem = "Disciplina cadastrada com sucesso!";
    $tipo_mensagem = "sucesso";
} elseif (isset($_GET['erro']) && $_GET['erro'] == 1) {
    $mensagem = "Erro ao cadastrar a disciplina. Verifique os dados.";
    $tipo_mensagem = "erro";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Disciplina</title>
    <style>
        /* Seus estilos existentes */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 40%;
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .feedback {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.2em;
            padding: 10px;
            border-radius: 5px;
        }
        .feedback.sucesso {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .feedback.erro {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        label {
            color: #555;
            font-weight: bold;
        }
        input, select, button {
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="text"], input[type="number"], select {
            background-color: #f9f9f9;
        }
        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            font-size: 1.1em;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #45a049;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #555;
            font-size: 1.1em;
        }
        .back-link:hover {
            color: #4CAF50;
        }
        .header {
            width: 100%;
            background-color: #4CAF50;
            padding: 10px 0;
            text-align: center;
            color: white;
            font-size: 1.5em;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        Painel Administrativo - Cadastro de Disciplina
    </div>

    <div class="container">
        <h2>Cadastrar Disciplina</h2>
        <?php if ($mensagem): ?>
            <div class="feedback <?= $tipo_mensagem ?>">
                <?= htmlspecialchars($mensagem); ?>
            </div>
        <?php endif; ?>
        <form action="back/controlador.php" method="POST">
            <input type="hidden" name="criarDisciplina" value="1">

            <label for="nome_disciplina">Nome da Disciplina:</label>
            <input type="text" name="nome_disciplina" id="nome_disciplina" placeholder="Digite o nome da disciplina" required>

            <label for="carga_horaria">Carga Horária:</label>
            <input type="number" name="carga_horaria" id="carga_horaria" placeholder="Ex.: 60" min="1" required>

            <label for="id_sala">Sala:</label>
            <select name="id_sala" id="id_sala" required>
                <option value="" disabled selected>Selecione uma sala</option>
                <?php foreach ($salas as $sala): ?>
                    <option value="<?= htmlspecialchars($sala['numero']); ?>">
                        Sala <?= htmlspecialchars($sala['numero']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="vagas_disponiveis">Vagas Disponíveis:</label>
            <input type="number" name="vagas_disponiveis" id="vagas_disponiveis" placeholder="Ex.: 30" min="1" required>

            <label for="id_professor">Professor:</label>
            <select name="id_professor" id="id_professor" required>
                <option value="" disabled selected>Selecione um professor</option>
                <?php foreach ($professores as $professor): ?>
                    <option value="<?= htmlspecialchars($professor['id']); ?>">
                        <?= htmlspecialchars($professor['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="id_curso">Curso:</label>
            <select name="id_curso" id="id_curso" required>
                <option value="" disabled selected>Selecione um curso</option>
                <?php foreach ($cursos as $curso): ?>
                    <option value="<?= htmlspecialchars($curso['id']); ?>">
                        <?= htmlspecialchars($curso['nome']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Cadastrar Disciplina</button>
        </form>

        <a href="painel_admin.php" class="back-link">Voltar ao Painel ADM</a>
    </div>

</body>
</html>
