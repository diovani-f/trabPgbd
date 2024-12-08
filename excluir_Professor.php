<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'back/conexao.php';

if (isset($_GET['id'])) {
    $id_professor = (int)$_GET['id'];

    $conn = conectarBanco();
    $sql = "DELETE FROM professor WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_professor);

    if ($stmt->execute()) {
        header("Location: gerenciar_professores.php?status=sucesso");
    } else {
        header("Location: gerenciar_professores.php?status=erro");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: gerenciar_professores.php?status=erro");
}
