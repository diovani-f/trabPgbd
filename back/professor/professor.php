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

        file_put_contents("yyyyyy.txt" ,json_encode($dados));
    
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
        // professor nao pode ser coordenador de um curso
        // nao pode estar em uma disciplina
        $id = $parametro["id_professor"]; 
    
        if (empty($id)) {
            echo json_encode(["erro" => "ID do professor é obrigatório"]);
            return;
        }

        $conn = conectarBanco();
        
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