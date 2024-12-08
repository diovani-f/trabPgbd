<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'back/conexao.php';
$conn = conectarBanco();

// Obtenção dos filtros
$numero_sala = isset($_GET['numero_sala']) ? (int)$_GET['numero_sala'] : null;
$capacidade = isset($_GET['capacidade']) ? (int)$_GET['capacidade'] : null;

// Query base para buscar salas
$sql = "SELECT * FROM sala WHERE 1=1";

// Adiciona filtros dinamicamente
if (!empty($numero_sala)) {
    $sql .= " AND numero = $numero_sala";
}
if (!empty($capacidade)) {
    $sql .= " AND capacidade >= $capacidade";
}

$resultado = $conn->query($sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Salas</title>
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

        .filters input {
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
    <h2>Gerenciar Salas</h2>
    <form class="filters" method="GET" action="">
        <input type="number" name="numero_sala" placeholder="Número da sala" value="<?= htmlspecialchars($numero_sala); ?>">
        <input type="number" name="capacidade" placeholder="Capacidade mínima" value="<?= htmlspecialchars($capacidade); ?>">
        <button type="submit">Filtrar</button>
    </form>

    <a href="painel_admin.php" class="back-link">Voltar ao Painel ADM</a>
    <a href="cadastrar_sala.php" class="create">Criar Sala</a>

    <table>
        <thead>
        <tr>
            <th>Número</th>
            <th>Capacidade</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($resultado && $resultado->num_rows > 0): ?>
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['numero']); ?></td>
                    <td><?= htmlspecialchars($row['capacidade']); ?></td>
                    <td class="actions">
                        <button class="button edit" onclick="window.location.href='editar_sala.php?numero=<?= $row['numero']; ?>'">Editar</button>
                        <button class="button delete" onclick="window.location.href='excluir_sala.php?numero=<?= $row['numero']; ?>'">Excluir</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3" style="text-align:center;">Nenhuma sala encontrada.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
