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

    $sqlCoordenador = "SELECT COUNT(*) AS total FROM curso WHERE id_coordenador = ?";
    $stmtCoordenador = $conn->prepare($sqlCoordenador);
    $stmtCoordenador->bind_param("i", $id_professor);
    $stmtCoordenador->execute();
    $resultCoordenador = $stmtCoordenador->get_result();
    $coordenadorCount = $resultCoordenador->fetch_assoc()['total'];

    $sqlDisciplina = "SELECT COUNT(*) AS total FROM disciplina WHERE id_professor = ?";
    $stmtDisciplina = $conn->prepare($sqlDisciplina);
    $stmtDisciplina->bind_param("i", $id_professor);
    $stmtDisciplina->execute();
    $resultDisciplina = $stmtDisciplina->get_result();
    $disciplinaCount = $resultDisciplina->fetch_assoc()['total'];

    if ($coordenadorCount > 0) {
        header("Location: gerenciar_professores.php?status=erro&mensagem=" . urlencode("Professor é coordenador de um curso."));
    } elseif ($disciplinaCount > 0) {
        header("Location: gerenciar_professores.php?status=erro&mensagem=" . urlencode("Professor é responsável por uma ou mais disciplinas."));

    } else {
        $sqlDelete = "DELETE FROM professor WHERE id = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param("i", $id_professor);

        if ($stmtDelete->execute()) {
            header("Location: gerenciar_professores.php?status=sucesso");
        } else {
            header("Location: gerenciar_professores.php?status=erro&mensagem=Erro ao excluir o professor.");
        }

        $stmtDelete->close();
    }

    $stmtCoordenador->close();
    $stmtDisciplina->close();
    $conn->close();
} else {
    header("Location: gerenciar_professores.php?status=erro&mensagem=ID do professor não fornecido.");
}
