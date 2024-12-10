<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'back/conexao.php';

$conn = conectarBanco();

// Obtenção dos filtros
$nome_professor = isset($_GET['nome_professor']) ? $_GET['nome_professor'] : '';

// Query base para buscar professores
$sql = "SELECT * FROM professor WHERE 1=1";

// Adiciona filtros dinamicamente
if (!empty($nome_professor)) {
    $sql .= " AND nome LIKE '%" . $conn->real_escape_string($nome_professor) . "%'";
}

$resultado = $conn->query($sql);
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Professores</title>
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
    <h2>Gerenciar Professores</h2>
    <form class="filters" method="GET" action="">
        <input type="text" name="nome_professor" placeholder="Nome do professor" value="<?= htmlspecialchars($nome_professor); ?>">
        <button type="submit">Filtrar</button>
    </form>

    <a href="painel_admin.php" class="back-link">Voltar ao Painel ADM</a>
    <a href="cadastrar_professor.php" class="create">Criar Professor</a>

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
                    <td><?= htmlspecialchars($row['id']); ?></td>
                    <td><?= htmlspecialchars($row['nome']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td class="actions">
                        <button class="button edit" onclick="window.location.href='editar_professor.php?id=<?= $row['id']; ?>'">Editar</button>
                        <button class="button delete" onclick="window.location.href='excluir_professor.php?id=<?= $row['id']; ?>'">Excluir</button>
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
<script>
    // Função para obter os parâmetros da URL
    function getUrlParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // Verificar se há uma mensagem na URL
    const mensagem = getUrlParameter('mensagem');
    if (mensagem) {
        alert(decodeURIComponent(mensagem)); // Exibe o pop-up com a mensagem de erro
    }
</script>