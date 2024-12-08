<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'back/conexao.php';
$conn = conectarBanco();

$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $carga_horaria = $_POST['carga_horaria'];
    $id_sala = $_POST['id_sala'];
    $vagas_disponiveis = $_POST['vagas_disponiveis'];
    $id_professor = $_POST['id_professor'];
    $id_curso = $_POST['id_curso'];

    $sql = "UPDATE disciplina SET nome = ?, carga_horaria = ?, id_sala = ?, vagas_disponiveis = ?, id_professor = ?, id_curso = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siisiii", $nome, $carga_horaria, $id_sala, $vagas_disponiveis, $id_professor, $id_curso, $id);

    if ($stmt->execute()) {
        header('Location: gerenciar_disciplinas.php');
        exit();
    } else {
        $erro = "Erro ao atualizar a disciplina.";
    }
}

$sql = "SELECT * FROM disciplina WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$disciplina = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Disciplina</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; }
        .container { width: 90%; margin: 20px auto; }
        form { display: flex; flex-direction: column; gap: 15px; }
        input, select, button { padding: 10px; font-size: 16px; border-radius: 5px; }
        button { background: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
    </style>
</head>
<body>
<div class="container">
    <h2>Editar Disciplina</h2>
    <?php if (!empty($erro)): ?>
        <p style="color: red;"><?php echo $erro; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Nome:</label>
        <input type="text" name="nome" value="<?php echo $disciplina['nome']; ?>" required>
        <label>Carga Horária:</label>
        <input type="number" name="carga_horaria" value="<?php echo $disciplina['carga_horaria']; ?>" required>
        <label>ID da Sala:</label>
        <input type="number" name="id_sala" value="<?php echo $disciplina['id_sala']; ?>" required>
        <label>Vagas Disponíveis:</label>
        <input type="number" name="vagas_disponiveis" value="<?php echo $disciplina['vagas_disponiveis']; ?>" required>
        <label>ID do Professor:</label>
        <input type="number" name="id_professor" value="<?php echo $disciplina['id_professor']; ?>" required>
        <label>ID do Curso:</label>
        <input type="number" name="id_curso" value="<?php echo $disciplina['id_curso']; ?>" required>
        <button type="submit">Atualizar</button>
    </form>
    <a href="gerenciar_disciplinas.php">Voltar</a>
</div>
</body>
</html>
