<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'back/conexao.php';

$conn = conectarBanco();

// Obtenção dos filtros
$nome_disciplina = isset($_GET['nome_disciplina']) ? $_GET['nome_disciplina'] : '';
$id_curso = isset($_GET['id_curso']) ? (int)$_GET['id_curso'] : null;

// Query base para buscar disciplinas
$sql = "SELECT disciplina.*, curso.nome AS nome_curso, professor.nome AS nome_professor 
        FROM disciplina
        LEFT JOIN curso ON disciplina.id_curso = curso.id
        LEFT JOIN professor ON disciplina.id_professor = professor.id
        WHERE 1=1";

// Adiciona filtros dinamicamente
if (!empty($nome_disciplina)) {
    $sql .= " AND disciplina.nome LIKE '%" . $conn->real_escape_string($nome_disciplina) . "%'";
}
if ($id_curso) {
    $sql .= " AND disciplina.id_curso = $id_curso";
}

$resultado = $conn->query($sql);

// Obter os cursos para o filtro
$cursos = $conn->query("SELECT id, nome FROM curso")->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Disciplinas</title>
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
            background: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .filters {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .filters input, .filters select {
            padding: 8px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .filters button {
            padding: 10px 15px;
            font-size: 1rem;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .filters button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #4CAF50;
            color: white;
        }

        table tr:hover {
            background-color: #f2f2f2;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .button {
            padding: 5px 10px;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .button.edit {
            background-color: #2196F3;
        }

        .button.edit:hover {
            background-color: #1976D2;
        }

        .button.delete {
            background-color: #f44336;
        }

        .button.delete:hover {
            background-color: #d32f2f;
        }

        .create {
            display: inline-block;
            margin-bottom: 15px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .create:hover {
            background-color: #45a049;
        }

        .back-link {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .back-link:hover {
            background-color: #1976D2;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Gerenciar Disciplinas</h2>
    <form class="filters" method="GET" action="">
        <select name="id_curso">
            <option value="">Todos os cursos</option>
            <?php foreach ($cursos as $curso): ?>
                <option value="<?= htmlspecialchars($curso['id']); ?>" <?= isset($id_curso) && $id_curso == $curso['id'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($curso['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="nome_disciplina" placeholder="Nome da disciplina" value="<?= htmlspecialchars($nome_disciplina); ?>">
        <button type="submit">Filtrar</button>
    </form>

    <a href="painel_admin.php" class="back-link">Voltar ao Painel ADM</a>
    <a href="cadastrar_disciplina.php" class="create">Criar Disciplina</a>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Curso</th>
            <th>Professor</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($resultado && $resultado->num_rows > 0): ?>
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']); ?></td>
                    <td><?= htmlspecialchars($row['nome']); ?></td>
                    <td><?= htmlspecialchars($row['nome_curso'] ?: 'N/A'); ?></td>
                    <td><?= htmlspecialchars($row['nome_professor'] ?: 'N/A'); ?></td>
                    <td class="actions">
                        <button class="button edit" onclick="window.location.href='editar_disciplina.php?id=<?= $row['id']; ?>'">Editar</button>
                        <button class="button delete" onclick="window.location.href='excluir_disciplina.php?id=<?= $row['id']; ?>'">Excluir</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5" style="text-align:center;">Nenhuma disciplina encontrada.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
