<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'back/conexao.php';
$conn = conectarBanco();

$sql = "SELECT a.id, a.dia_da_semana, a.horario_inicio, a.horario_fim, a.data_inicio, a.data_final,
               d.nome AS nome_disciplina, 
               s.numero AS numero_sala
        FROM aula a
        LEFT JOIN disciplina d ON a.id_disciplina = d.id
        LEFT JOIN sala s ON d.id_sala = s.numero";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Aulas</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; }
        .container { width: 90%; margin: 20px auto; }
        h2 { text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
        table th, table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background: #4CAF50; color: #fff; }
        .actions { display: flex; gap: 10px; }
        .button { padding: 5px 10px; color: #fff; border: none; cursor: pointer; border-radius: 5px; }
        .edit { background: #2196F3; }
        .delete { background: #f44336; }
        .back-link { display: inline-block; margin-top: 20px; text-decoration: none; color: #333; }
        .back-link:hover { color: #4CAF50; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Gerenciar Aulas</h2>
        <a href="painel_admin.php" class="back-link">Voltar ao Painel ADM</a>
        <a href="cadastrar_aula.php" class="button create">Criar Aula</a>
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
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nome_disciplina']; ?></td>
                            <td><?php echo $row['numero_sala'] ?: 'N/A'; ?></td>
                            <td><?php echo $row['dia_da_semana']; ?></td>
                            <td><?php echo $row['horario_inicio'] . ' - ' . $row['horario_fim']; ?></td>
                            <td><?php echo $row['data_inicio']; ?></td>
                            <td><?php echo $row['data_final']; ?></td>
                            <td class="actions">
                                <button class="button edit" onclick="window.location.href='editar_aula.php?id=<?php echo $row['id']; ?>'">Editar</button>
                                <button class="button delete" onclick="window.location.href='excluir_aula.php?id=<?php echo $row['id']; ?>'">Excluir</button>
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
