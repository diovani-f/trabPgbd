<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

include_once 'back/conexao.php';

if (isset($_GET['id'])) {
    $id_disciplina = (int)$_GET['id'];

    $conn = conectarBanco();
    $sql = "DELETE FROM disciplina WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_disciplina);

    if ($stmt->execute()) {
        header("Location: gerenciar_disciplinas.php?status=sucesso");
    } else {
        header("Location: gerenciar_disciplinas.php?status=erro");
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: gerenciar_disciplinas.php?status=erro");
}
