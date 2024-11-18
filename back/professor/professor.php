<?php
    
    header('Content-Type: application/json');
    
    function buscarProfessor($parametro = 0){
        $sql = "SELECT * FROM professor";
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
    function criarProfessor($parametro = 0) {
        
        $nome = $parametro["nome_professor"]; 
        $email = $parametro["email_professor"]; 
        $coordenador = $parametro["coordenador"];
    
        if (empty($nome) || empty($email)) {
            echo json_encode(["erro" => "Nome e email são obrigatórios"]);
            return;
        }
    
        $conn = conectarBanco();
        $sql = "INSERT INTO professor (nome, email, coordenador) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nome, $email, $coordenador);
    
        if ($stmt->execute()) {
            echo json_encode(["sucesso" => "Professor criado com sucesso"]);
        } else {
            echo json_encode(["erro" => "Erro ao criar professor: " . $stmt->error]);
        }
    
        $stmt->close();
        $conn->close();
    }

    function excluirProfessor($parametro = 0) {
        $id = $parametro["id_professor"]; 
    
        if (empty($id)) {
            echo json_encode(["erro" => "ID do professor � obrigat�rio"]);
            return;
        }
    
        $conn = conectarBanco();
    
        // Verificar se o professor é coordenador de algum curso
        $sqlCoordenador = "SELECT id FROM curso WHERE id_coordenador = ?";
        $stmtCoordenador = $conn->prepare($sqlCoordenador);
        $stmtCoordenador->bind_param("i", $id);
        $stmtCoordenador->execute();
        $resultCoordenador = $stmtCoordenador->get_result();
    
        if ($resultCoordenador->num_rows > 0) {
            echo json_encode(["erro" => "O professor não pode ser excluído porque é coordenador de um curso."]);
            file_put_contents('teste.txt',print_r(json_encode(["erro" => "O professor não pode ser excluido porque é coordenador de um curso."]), true) . PHP_EOL ,FILE_APPEND);
            $stmtCoordenador->close();
            $conn->close();
            return;
        }
    
        $stmtCoordenador->close();
    
        // Verificar se o professor est� associado a alguma disciplina
        $sqlDisciplina = "SELECT id FROM disciplina WHERE id_professor = ?";
        $stmtDisciplina = $conn->prepare($sqlDisciplina);
        $stmtDisciplina->bind_param("i", $id);
        $stmtDisciplina->execute();
        $resultDisciplina = $stmtDisciplina->get_result();
    
        if ($resultDisciplina->num_rows > 0) {
            echo json_encode(["erro" => "O professor não pode ser excluído porque está associado a uma disciplina."]);
            $stmtDisciplina->close();
            $conn->close();
            return;
        }
    
        $stmtDisciplina->close();
    
        $sql = "DELETE FROM professor WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
    
        if ($stmt->execute()) {
            echo json_encode(["sucesso" => "Professor exclu�do com sucesso"]);
        } else {
            echo json_encode(["erro" => "Erro ao excluir professor: " . $stmt->error]);
        }
    
        $stmt->close();
        $conn->close();
    }
    

    function editarProfessor($parametro = 0) {
    $id = $parametro["id_professor"]; 
    $nome = $parametro["nome_professor"]; 
    $email = $parametro["email_professor"]; 
    $coordenador = $parametro["coordenador"];

    if (empty($id) || empty($nome) || empty($email)) {
        echo json_encode(["erro" => "ID, nome e email são obrigatórios"]);
        return;
    }

    $conn = conectarBanco();
    $sql = "UPDATE professor SET nome = ?, email = ?, coordenador = ? WHERE id = ?";
    file_put_contents("sssss.txt", $sql );
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $nome, $email, $coordenador, $id);

    if ($stmt->execute()) {
        echo json_encode(["sucesso" => "Professor editado com sucesso"]);
    } else {
        echo json_encode(["erro" => "Erro ao editar professor: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>