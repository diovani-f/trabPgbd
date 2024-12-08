<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'back/conexao.php';
$conn = conectarBanco();

$sql = "SELECT * FROM sala";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Salas</title>
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
        <h2>Gerenciar Salas</h2>
        <a href="painel_admin.php" class="back-link">Voltar ao Painel ADM</a>
        <a href="cadastrar_sala.php" class="button create">Criar Sala</a>
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
                            <td><?php echo $row['numero']; ?></td>
                            <td><?php echo $row['capacidade']; ?></td>
                            <td class="actions">
                                <button class="button edit" onclick="window.location.href='editar_sala.php?numero=<?php echo $row['numero']; ?>'">Editar</button>
                                <button class="button delete" onclick="window.location.href='excluir_sala.php?numero=<?php echo $row['numero']; ?>'">Excluir</button>
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
