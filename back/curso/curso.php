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
        echo json_encode(["erro" => "Nome e coordenador são obrigatórios"]);
        return;
    }

    $conn = conectarBanco();
    $sql = "INSERT INTO curso (nome, id_coordenador) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nome, $id_coordenador);

    if ($stmt->execute()) {
        echo json_encode(["sucesso" => "Curso criado com sucesso"]);
    } else {
        echo json_encode(["erro" => "Erro ao criar curso: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}

function editarCurso($parametro) {
    $id = $parametro["id_curso"] ?? null;
    $nome = $parametro["nome_curso"] ?? null;
    $id_coordenador = $parametro["id_coordenador"] ?? null;

    if (empty($id) || empty($nome) || empty($id_coordenador)) {
        echo json_encode(["erro" => "ID, nome e coordenador são obrigatórios"]);
        return;
    }

    $conn = conectarBanco();
    $sql = "UPDATE curso SET nome = ?, id_coordenador = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $nome, $id_coordenador, $id);

    if ($stmt->execute()) {
        echo json_encode(["sucesso" => "Curso editado com sucesso"]);
    } else {
        echo json_encode(["erro" => "Erro ao editar curso: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
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
