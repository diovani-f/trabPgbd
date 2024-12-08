<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Conexão com o banco de dados
include_once 'back/conexao.php';
$conn = conectarBanco();

// Obter o ID do curso a ser editado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID do curso não especificado.");
}

$id_curso = (int) $_GET['id'];

// Obter os dados do curso
$sql = "SELECT * FROM curso WHERE id = $id_curso";
$resultado = $conn->query($sql);
if ($resultado->num_rows == 0) {
    die("Curso não encontrado.");
}
$curso = $resultado->fetch_assoc();

// Obter os coordenadores para o dropdown
$coordenadores = $conn->query("SELECT id, nome FROM professor")->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Curso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin: 10px 0 5px;
            color: #555;
            font-weight: bold;
        }
        input, select, button {
            padding: 10px;
            font-size: 1rem;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="text"] {
            background-color: #f9f9f9;
        }
        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
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
        }
        .back-link:hover {
            color: #4CAF50;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Editar Curso</h2>
    <form action="back/controlador.php" method="POST">
        <input type="hidden" name="editarCurso" value="1">
        <input type="hidden" name="id_curso" value="<?= htmlspecialchars($id_curso); ?>">

        <label for="nome">Nome do Curso:</label>
        <input type="text" id="nome" name="nome_curso" value="<?= htmlspecialchars($curso['nome']); ?>" required>

        <label for="id_coordenador">Coordenador:</label>
        <select name="id_coordenador" id="id_coordenador" required>
            <option value="" disabled>Selecione um coordenador</option>
            <?php foreach ($coordenadores as $coordenador): ?>
                <option value="<?= htmlspecialchars($coordenador['id']); ?>" 
                    <?= $coordenador['id'] == $curso['id_coordenador'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($coordenador['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Salvar Alterações</button>
    </form>

    <a href="gerenciar_cursos.php" class="back-link">Voltar</a>
</div>

</body>
</html>
