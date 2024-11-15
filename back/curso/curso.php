<?php
header('Content-Type: application/json');
function buscarCurso(){
        
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
function criarCurso() {
    $nome = 'Novo Curso'; // Substituir pelo valor enviado pelo JS
    $id_coordenador = 3; 

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
function editarCurso() {
    $id = 1;
    $nome = 'Curso Editado';
    $id_coordenador = 3;

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
function excluirCurso() {
    //curso nao pode ter uma disciplina cadastrada
    $id = 2; // Substituir pelo valor enviado pelo JS

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