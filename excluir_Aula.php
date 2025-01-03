<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'back/conexao.php';

if (isset($_GET['id'])) {
    $id_aula = (int)$_GET['id'];

    $conn = conectarBanco();
    $sql = "DELETE FROM aula WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_aula);

    if ($stmt->execute()) {
        header("Location: gerenciar_aulas.php?status=sucesso");
    } else {
        header("Location: gerenciar_aulas.php?status=erro");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: gerenciar_aulas.php?status=erro");
}
