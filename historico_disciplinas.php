<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'back/conexao.php';
$conn = conectarBanco();

$sql = "SELECT hd.id, hd.acao, hd.data_hora,
               hd.nome_disciplina, -- Pega diretamente do histórico
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
            margin: 0 auto;
            padding: 30px 0;
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

        table th:first-child, table td:first-child {
            width: 5%;
        }

        table tr:hover {
            background-color: #f2f2f2;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #333;
            text-decoration: none;
            font-size: 1.1em;
        }

        .back-link:hover {
            color: #4CAF50;
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
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nome_coordenador'] ?: 'N/A'; ?></td>
                    <td><?php echo $row['nome_disciplina'] ?: 'N/A'; ?></td>
                    <td><?php echo $row['nome_curso'] ?: 'N/A'; ?></td>
                    <td><?php echo $row['acao']; ?></td>
                    <td><?php echo $row['data_hora']; ?></td>
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
