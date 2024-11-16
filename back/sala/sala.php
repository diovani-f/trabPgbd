<?php

    header('Content-Type: application/json');

    function criarSala($parametro = 0) {
        $numero = $parametro['numero_sala']; 
        $capacidade = $parametro['capacidade'];

        if (empty($numero) || empty($capacidade)) {
            echo json_encode(["erro" => "Todos os campos obrigatórios devem ser preenchidos"]);
            return;
        }

        $conn = conectarBanco();

        $stmt = $conn->prepare("INSERT INTO sala (numero, capacidade) VALUES (?, ?)");
        $stmt->bind_param("ii", $numero, $capacidade);

        if ($stmt->execute()) {
            echo json_encode(["sucesso" => "Sala criada com sucesso"]);
        } else {
            echo json_encode(["erro" => "Erro ao criar a sala"]);
        }

        $stmt->close();
        $conn->close();
    }

    

function editarSala($parametro = 0) {
        $numero = $parametro['numero_sala']; 
        $capacidade = $parametro['capacidade'];

    if (empty($numero) || empty($capacidade)) {
        echo json_encode(["erro" => "Todos os campos obrigatórios devem ser preenchidos"]);
        return;
    }


    $conn = conectarBanco();

    $stmt = $conn->prepare("UPDATE sala SET capacidade = ? WHERE numero = ?");
    $stmt->bind_param("ii", $capacidade, $numero);


    if ($stmt->execute()) {
        echo json_encode(["sucesso" => "Sala editada com sucesso"]);
    } else {
        echo json_encode(["erro" => "Erro ao editar a sala"]);
    }


    $stmt->close();
    $conn->close();
}

function excluirSala($parametro = 0) {
    $numero = $parametro['numero_sala'];
    
    if (empty($numero)) {
        echo json_encode(["erro" => "Número da sala não fornecido"]);
        return;
    }

    // Conectar ao banco de dados
    $conn = conectarBanco();

    // Preparar a query para excluir a sala
    $stmt = $conn->prepare("DELETE FROM sala WHERE numero = ?");
    $stmt->bind_param("i", $numero);

    // Executar a query
    if ($stmt->execute()) {
        echo json_encode(["sucesso" => "Sala excluída com sucesso"]);
    } else {
        echo json_encode(["erro" => "Erro ao excluir a sala"]);
    }

    // Fechar a conexão
    $stmt->close();
    $conn->close();
}

function buscarSala($parametro = 0){
    $sql = "SELECT * FROM sala";
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
?>