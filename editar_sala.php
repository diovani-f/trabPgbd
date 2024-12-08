<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'back/conexao.php';
$conn = conectarBanco();

$numero = $_GET['numero'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $capacidade = $_POST['capacidade'];

    $sql = "UPDATE sala SET capacidade = ? WHERE numero = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $capacidade, $numero);

    if ($stmt->execute()) {
        header('Location: gerenciar_salas.php');
        exit();
    } else {
        $erro = "Erro ao atualizar a sala.";
    }
}

$sql = "SELECT * FROM sala WHERE numero = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $numero);
$stmt->execute();
$resultado = $stmt->get_result();
$sala = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Sala</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; }
        .container { width: 90%; margin: 20px auto; }
        form { display: flex; flex-direction: column; gap: 15px; }
        input, button { padding: 10px; font-size: 16px; border-radius: 5px; }
        button { background: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
    </style>
</head>
<body>
<div class="container">
    <h2>Editar Sala</h2>
    <?php if (!empty($erro)): ?>
        <p style="color: red;"><?php echo $erro; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Número da Sala:</label>
        <input type="text" value="<?php echo $sala['numero']; ?>" disabled>
        <label>Capacidade:</label>
        <input type="number" name="capacidade" value="<?php echo $sala['capacidade']; ?>" required>
        <button type="submit">Atualizar</button>
    </form>
    <a href="gerenciar_salas.php">Voltar</a>
</div>
</body>
</html>
