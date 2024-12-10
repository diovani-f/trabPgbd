<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'back/conexao.php';

if (isset($_GET['id'])) {
    $id_curso = (int)$_GET['id'];

    $conn = conectarBanco();

    // Verificar se o curso possui disciplinas associadas
    $sql_verificar_disciplinas = "SELECT COUNT(*) FROM disciplina WHERE id_curso = ?";
    $stmt_verificar = $conn->prepare($sql_verificar_disciplinas);
    $stmt_verificar->bind_param("i", $id_curso);
    $stmt_verificar->execute();
    $stmt_verificar->bind_result($total_disciplinas);
    $stmt_verificar->fetch();
    $stmt_verificar->close();

    // Se o curso tiver disciplinas associadas, não permitir a exclusão
    if ($total_disciplinas > 0) {
        header("Location: gerenciar_cursos.php?status=erro&mensagem=Não+é+possível+excluir+este+curso,+pois+ele+possui+disciplinas+associadas.");
        exit();
    }

    // Caso não tenha disciplinas associadas, pode excluir o curso
    $sql = "DELETE FROM curso WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_curso);

    if ($stmt->execute()) {
        header("Location: gerenciar_cursos.php?status=sucesso");
    } else {
        header("Location: gerenciar_cursos.php?status=erro");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: gerenciar_cursos.php?status=erro");
}
?>
