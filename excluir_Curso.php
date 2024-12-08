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
