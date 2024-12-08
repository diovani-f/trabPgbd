<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'back/conexao.php';
$conn = conectarBanco();

$sql = "SELECT * FROM professor";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Professores</title>
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
        .create { display: inline-block; margin: 10px 0; padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; font-size: 16px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .create:hover { background-color: #45a049; }
        .back-link { display: inline-block; margin-top: 20px; text-decoration: none; color: #333; }
        .back-link:hover { color: #4CAF50; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Gerenciar Professores</h2>
        <a href="painel_admin.php" class="back-link">Voltar ao Painel ADM</a>
        <a href="cadastrar_professor.php" class="button create">Criar Professor</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($row = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nome']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td class="actions">
                                <button class="button edit" onclick="window.location.href='editar_professor.php?id=<?php echo $row['id']; ?>'">Editar</button>
                                <button class="button delete" onclick="window.location.href='excluir_professor.php?id=<?php echo $row['id']; ?>'">Excluir</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">Nenhum professor encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
