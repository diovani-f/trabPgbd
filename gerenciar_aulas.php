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
$dia_da_semana = isset($_GET['dia_da_semana']) ? $_GET['dia_da_semana'] : '';
$data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
$data_final = isset($_GET['data_final']) ? $_GET['data_final'] : '';

// Query base para buscar aulas
$sql = "SELECT a.id, a.dia_da_semana, a.horario_inicio, a.horario_fim, a.data_inicio, a.data_final,
               d.nome AS nome_disciplina, 
               s.numero AS numero_sala
        FROM aula a
        LEFT JOIN disciplina d ON a.id_disciplina = d.id
        LEFT JOIN sala s ON d.id_sala = s.numero
        WHERE 1=1";

// Adiciona filtros dinamicamente
if (!empty($nome_disciplina)) {
    $sql .= " AND d.nome LIKE '%" . $conn->real_escape_string($nome_disciplina) . "%'";
}
if (!empty($dia_da_semana)) {
    $sql .= " AND a.dia_da_semana = '" . $conn->real_escape_string($dia_da_semana) . "'";
}
if (!empty($data_inicio)) {
    $sql .= " AND a.data_inicio >= '" . $conn->real_escape_string($data_inicio) . "'";
}
if (!empty($data_final)) {
    $sql .= " AND a.data_final <= '" . $conn->real_escape_string($data_final) . "'";
}

$resultado = $conn->query($sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Aulas</title>
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
            margin-bottom: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .back-link:hover {
            background-color: #1976D2;
        }
    </style>
</head>
<div class="container">
    <h2>Gerenciar Aulas</h2>
    <form class="filters" method="GET" action="">
        <input type="text" name="nome_disciplina" placeholder="Nome da disciplina" value="<?= htmlspecialchars($nome_disciplina); ?>">
        <select name="dia_da_semana">
            <option value="">Dia da semana</option>
            <option value="Segunda" <?= $dia_da_semana === 'Segunda' ? 'selected' : ''; ?>>Segunda</option>
            <option value="Terça" <?= $dia_da_semana === 'Terça' ? 'selected' : ''; ?>>Terça</option>
            <option value="Quarta" <?= $dia_da_semana === 'Quarta' ? 'selected' : ''; ?>>Quarta</option>
            <option value="Quinta" <?= $dia_da_semana === 'Quinta' ? 'selected' : ''; ?>>Quinta</option>
            <option value="Sexta" <?= $dia_da_semana === 'Sexta' ? 'selected' : ''; ?>>Sexta</option>
            <option value="Sábado" <?= $dia_da_semana === 'Sábado' ? 'selected' : ''; ?>>Sábado</option>
            <option value="Domingo" <?= $dia_da_semana === 'Domingo' ? 'selected' : ''; ?>>Domingo</option>
        </select>
        <input type="date" name="data_inicio" value="<?= htmlspecialchars($data_inicio); ?>" placeholder="Data de início">
        <input type="date" name="data_final" value="<?= htmlspecialchars($data_final); ?>" placeholder="Data final">
        <button type="submit">Filtrar</button>
    </form>

    <a href="painel_admin.php" class="back-link">Voltar ao Painel ADM</a>
    <a href="cadastrar_aula.php" class="create">Criar Aula</a>

    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Disciplina</th>
            <th>Sala</th>
            <th>Dia da Semana</th>
            <th>Horário</th>
            <th>Data de Início</th>
            <th>Data Final</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($resultado && $resultado->num_rows > 0): ?>
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']); ?></td>
                    <td><?= htmlspecialchars($row['nome_disciplina']); ?></td>
                    <td><?= htmlspecialchars($row['numero_sala'] ?: 'N/A'); ?></td>
                    <td><?= htmlspecialchars($row['dia_da_semana']); ?></td>
                    <td><?= htmlspecialchars($row['horario_inicio'] . ' - ' . $row['horario_fim']); ?></td>
                    <td><?= htmlspecialchars($row['data_inicio']); ?></td>
                    <td><?= htmlspecialchars($row['data_final']); ?></td>
                    <td class="actions">
                        <button class="button edit" onclick="window.location.href='editar_aula.php?id=<?= $row['id']; ?>'">Editar</button>
                        <button class="button delete" onclick="window.location.href='excluir_aula.php?id=<?= $row['id']; ?>'">Excluir</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" style="text-align:center;">Nenhuma aula encontrada.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
