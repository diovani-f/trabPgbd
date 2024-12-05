<?php
header('Content-Type: application/json');

function buscarCurso($parametro = 0){
    $sql = "SELECT * FROM curso";
    $conn = conectarBanco();    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $dados = [];

    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            foreach ($row as $key => $value) {
                $row[$key] = utf8_encode($value);
            }
            $dados[] = $row;
        }
    }

    $stmt->close();
    $conn->close();

    echo json_encode($dados);
}
function criarCurso($parametro = 0) {
    $nome = $parametro['nome_curso'];
    $id_coordenador = $parametro['id_coordenador'];

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

// Função para editar um curso
function editarCurso($parametro = 0) {
    $id = $parametro["id_curso"];
    $nome = $parametro["nome_curso"];
    $id_coordenador = $parametro["id_coordenador"];

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

// Função para excluir um curso
function excluirCurso($parametro = 0) {
    //curso nao pode ter uma disciplina cadastrada
    $id = $parametro["id_curso"];
    

    if (empty($id)) {
        echo json_encode(["erro" => "ID do curso é obrigatório"]);
        return;
    }

    $conn = conectarBanco();
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