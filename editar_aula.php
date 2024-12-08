au<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'back/conexao.php';
$conn = conectarBanco();

$id = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dia_da_semana = $_POST['dia_da_semana'];
    $horario_inicio = $_POST['horario_inicio'];
    $horario_fim = $_POST['horario_fim'];
    $data_inicio = $_POST['data_inicio'];
    $data_final = $_POST['data_final'];

    $sql = "UPDATE aula SET dia_da_semana = ?, horario_inicio = ?, horario_fim = ?, data_inicio = ?, data_final = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $dia_da_semana, $horario_inicio, $horario_fim, $data_inicio, $data_final, $id);

    if ($stmt->execute()) {
        header('Location: gerenciar_aulas.php');
        exit();
    } else {
        $erro = "Erro ao atualizar a aula.";
    }
}

$sql = "SELECT * FROM aula WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$aula = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Aula</title>
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
    <h2>Editar Aula</h2>
    <?php if (!empty($erro)): ?>
        <p style="color: red;"><?php echo $erro; ?></p>
    <?php endif; ?>
    <form method="POST">
        <label>Dia da Semana:</label>
        <select name="dia_da_semana" required>
            <?php
            $dias_semana = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
            foreach ($dias_semana as $dia) {
                $selected = $aula['dia_da_semana'] === $dia ? 'selected' : '';
                echo "<option value=\"$dia\" $selected>$dia</option>";
            }
            ?>
        </select>
        <label>Horário de Início:</label>
        <input type="time" name="horario_inicio" value="<?php echo $aula['horario_inicio']; ?>" required>
        <label>Horário de Fim:</label>
        <input type="time" name="horario_fim" value="<?php echo $aula['horario_fim']; ?>" required>
        <label>Data de Início:</label>
        <input type="date" name="data_inicio" value="<?php echo $aula['data_inicio']; ?>" required>
        <label>Data Final:</label>
        <input type="date" name="data_final" value="<?php echo $aula['data_final']; ?>" required>
        <button type="submit">Atualizar</button>
    </form>
    <a href="gerenciar_aulas.php">Voltar</a>
</div>
</body>
</html>
