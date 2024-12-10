<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'back/conexao.php';

if (isset($_GET['numero'])) {
    $numero_sala = (int)$_GET['numero'];

    $conn = conectarBanco();

    // Verificar se a sala está associada a alguma disciplina
    $sql_verificar_disciplina = "SELECT COUNT(*) FROM disciplina WHERE id_sala = ?";
    $stmt_verificar = $conn->prepare($sql_verificar_disciplina);
    $stmt_verificar->bind_param("i", $numero_sala);
    $stmt_verificar->execute();
    $stmt_verificar->bind_result($total_disciplinas);
    $stmt_verificar->fetch();
    $stmt_verificar->close();

    // Se a sala estiver associada a disciplinas, não permitir a exclusão
    if ($total_disciplinas > 0) {
        header("Location: gerenciar_salas.php?status=erro&mensagem=Não+é+possível+excluir+esta+sala,+pois+ela+está+associada+a+disciplinas.");
        exit();
    }

    // Caso não haja disciplinas associadas, pode excluir a sala
    $sql = "DELETE FROM sala WHERE numero = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $numero_sala);

    if ($stmt->execute()) {
        header("Location: gerenciar_salas.php?status=sucesso");
    } else {
        header("Location: gerenciar_salas.php?status=erro");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: gerenciar_salas.php?status=erro");
}
?>
