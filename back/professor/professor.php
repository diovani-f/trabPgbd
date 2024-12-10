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
                    $row[$key] = ($value);
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
    
        if (empty($nome) || empty($email)) {
            echo json_encode(["erro" => "Nome e email são obrigatórios"]);
            return;
        }
    
    
        $conn = conectarBanco();
        
        // Escapando as variáveis para evitar SQL Injection
        $nome = $conn->real_escape_string($nome);
        $email = $conn->real_escape_string($email);
    
        // Construindo a consulta SQL diretamente
        $sql = "INSERT INTO professor (nome, email) VALUES ('$nome', '$email')";
    
        // Executando a consulta
        if ($conn->query($sql)) {
            echo json_encode(["sucesso" => "Professor criado com sucesso"]);
        } else {
            echo json_encode(["erro" => "Erro ao criar professor: " . $conn->error]);
        }
    
    
        $conn->close();
    }
    
    function excluirProfessor($parametro = 0) {
        $id = $parametro["id_professor"]; 
    
        if (empty($id)) {
            echo json_encode(["erro" => "ID do professor é obrigatório"]);
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
            echo json_encode(["sucesso" => "Professor excluído com sucesso"]);
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
    
        if (empty($id) || empty($nome) || empty($email)) {
            echo json_encode(["erro" => "ID, nome e email são obrigatórios"]);
            return;
        }
    
        $conn = conectarBanco();
    
        // Escapando as variáveis para evitar SQL Injection
        $nome = $conn->real_escape_string($nome);
        $email = $conn->real_escape_string($email);

        $id = (int) $id; // Convertendo id para inteiro
    
        // Construindo a consulta SQL diretamente
        $sql = "UPDATE professor SET nome = '$nome', email = '$email' WHERE id = $id";
    
        // Executando a consulta
        if ($conn->query($sql)) {
            echo json_encode(["sucesso" => "Professor editado com sucesso"]);
        } else {
            echo json_encode(["erro" => "Erro ao editar professor: " . $conn->error]);
        }
    
        $conn->close();
    }
    
?>