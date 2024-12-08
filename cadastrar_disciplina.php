<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Obter dados dinâmicos para salas, professores e cursos
$conn = new mysqli("localhost", "root", "Bunda4542@", "oferta");
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$salas = $conn->query("SELECT numero FROM sala")->fetch_all(MYSQLI_ASSOC);
$professores = $conn->query("SELECT id, nome FROM professor")->fetch_all(MYSQLI_ASSOC);
$cursos = $conn->query("SELECT id, nome FROM curso")->fetch_all(MYSQLI_ASSOC);
$conn->close();

// Mensagem de feedback
$mensagem = "";
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'sucesso') {
        $mensagem = "Disciplina cadastrada com sucesso!";
    } elseif ($_GET['status'] === 'erro') {
        $mensagem = "Erro ao cadastrar a disciplina. Verifique os dados.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Disciplina</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 40%;
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
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="text"], input[type="number"], select {
            background-color: #f9f9f9;
        }
        button {
            padding: 12px;
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
        .feedback {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.2em;
        }
        .feedback.sucesso {
            color: green;
        }
        .feedback.erro {
            color: red;
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
            <div class="feedback <?= $_GET['status'] === 'sucesso' ? 'sucesso' : 'erro' ?>">
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
