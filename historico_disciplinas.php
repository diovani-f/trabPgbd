<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'back/conexao.php';
$conn = conectarBanco();

$sql = "SELECT hd.id, hd.acao, hd.data_hora,
               hd.nome_disciplina,
               p.nome AS nome_coordenador,
               c.nome AS nome_curso
        FROM historico_disciplinas hd
        LEFT JOIN professor p ON hd.id_coordenador = p.id
        LEFT JOIN curso c ON hd.id_curso = c.id
        ORDER BY hd.data_hora DESC";
        
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Disciplinas</title>
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
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 5px;
            overflow: hidden;
        }

        table thead {
            background-color: #4CAF50;
            color: #fff;
        }

        table th, table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        table tr:hover {
            background-color: #f2f2f2;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 15px;
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
    <h2>Histórico de Disciplinas</h2>
    <a href="painel_admin.php" class="back-link">Voltar ao Painel ADM</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Coordenador</th>
                <th>Disciplina</th>
                <th>Curso</th>
                <th>Ação</th>
                <th>Data/Hora</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($resultado && $resultado->num_rows > 0): ?>
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']); ?></td>
                    <td><?= htmlspecialchars($row['nome_coordenador'] ?: 'N/A'); ?></td>
                    <td><?= htmlspecialchars($row['nome_disciplina'] ?: 'N/A'); ?></td>
                    <td><?= htmlspecialchars($row['nome_curso'] ?: 'N/A'); ?></td>
                    <td><?= htmlspecialchars($row['acao']); ?></td>
                    <td><?= htmlspecialchars($row['data_hora']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" style="text-align:center;">Nenhum registro de histórico encontrado.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
