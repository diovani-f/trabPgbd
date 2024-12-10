<?php
include_once __DIR__ . '/../conexao.php';
header('Content-Type: application/json');

function buscarCurso() {
    $sql = "SELECT * FROM curso";
    $conn = conectarBanco();
    $resultado = $conn->query($sql);

    $dados = [];
    if ($resultado && $resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $dados[] = $row;
        }
    }

    $conn->close();
    echo json_encode($dados);
}

function criarCurso($parametro) {
    $nome = $parametro['nome_curso'] ?? null;
    $id_coordenador = $parametro['id_coordenador'] ?? null;

    if (empty($nome) || empty($id_coordenador)) {
        header("Location: ../cadastrar_curso.php?status=erro&mensagem=Nome+do+curso+e+ID+do+coordenador+são+obrigatórios.");
        exit();
    }

    $conn = conectarBanco();

    // Verificar se o coordenador já está associado a outro curso
    $sqlVerifica = "SELECT id FROM curso WHERE id_coordenador = ?";
    $stmtVerifica = $conn->prepare($sqlVerifica);
    $stmtVerifica->bind_param("i", $id_coordenador);
    $stmtVerifica->execute();
    $stmtVerifica->store_result();

    if ($stmtVerifica->num_rows > 0) {
        $stmtVerifica->close();
        $conn->close();
        header("Location: ../cadastrar_curso.php?status=erro&mensagem=O+coordenador+já+está+associado+a+outro+curso.");
        exit();
    }

    $stmtVerifica->close();

    // Inserir o curso se o coordenador não estiver associado
    $sql = "INSERT INTO curso (nome, id_coordenador) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nome, $id_coordenador);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../cadastrar_curso.php?status=sucesso&mensagem=Curso+criado+com+sucesso.");
        exit();
    } else {
        $erro = $stmt->error;
        $stmt->close();
        $conn->close();
        header("Location: ../cadastrar_curso.php?status=erro&mensagem=Erro+ao+criar+curso%3A+" . urlencode($erro));
        exit();
    }
}


function editarCurso($parametro) {
    $id = $parametro["id_curso"] ?? null;
    $nome = $parametro["nome_curso"] ?? null;
    $id_coordenador = $parametro["id_coordenador"] ?? null;

    if (empty($id) || empty($nome) || empty($id_coordenador)) {
        header("Location: ../editar_curso.php?id=$id&status=erro&mensagem=ID%2C+nome+e+coordenador+são+obrigatórios.");
        exit();
    }

    $conn = conectarBanco();

    // Verificar se o coordenador já está associado a outro curso
    $sqlVerifica = "SELECT id FROM curso WHERE id_coordenador = ? AND id != ?";
    $stmtVerifica = $conn->prepare($sqlVerifica);
    $stmtVerifica->bind_param("ii", $id_coordenador, $id); // Ignora o próprio curso na comparação
    $stmtVerifica->execute();
    $stmtVerifica->store_result();

    if ($stmtVerifica->num_rows > 0) {
        // Coordenador já está associado a outro curso
        $stmtVerifica->close();
        $conn->close();
        header("Location: ../editar_curso.php?id=$id&status=erro&mensagem=O+coordenador+já+está+associado+a+outro+curso.");
        exit();
    }

    $stmtVerifica->close();

    // Atualizar o curso se o coordenador não estiver associado a outro curso
    $sql = "UPDATE curso SET nome = ?, id_coordenador = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $nome, $id_coordenador, $id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../editar_curso.php?id=$id&status=sucesso&mensagem=Curso+editado+com+sucesso.");
        exit();
    } else {
        $erro = $stmt->error;
        $stmt->close();
        $conn->close();
        header("Location: ../editar_curso.php?id=$id&status=erro&mensagem=Erro+ao+editar+curso%3A+" . urlencode($erro));
        exit();
    }
}


function excluirCurso($parametro) {
    $id = $parametro["id_curso"] ?? null;

    if (empty($id)) {
        echo json_encode(["erro" => "ID do curso é obrigatório"]);
        return;
    }

    $conn = conectarBanco();

    // Verificar se há disciplinas associadas ao curso
    $stmt = $conn->prepare("SELECT COUNT(*) FROM disciplina WHERE id_curso = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(["erro" => "Não é possível excluir o curso, pois há disciplinas associadas."]);
        $conn->close();
        return;
    }

    // Excluir curso
    $sql = "DELETE FROM curso WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["sucesso" => "Curso excluído com sucesso"]);
    } else {
        echo json_encode(["erro" => "Erro ao excluir curso: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
