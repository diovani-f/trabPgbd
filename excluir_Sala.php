<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'back/conexao.php';

if (isset($_GET['id'])) {
    $numero_sala = (int)$_GET['id'];

    $conn = conectarBanco();
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
