<?php
header('Content-Type: application/json');
include_once __DIR__ . '/../conexao.php';

function buscarSala($parametro = 0) {
    $conn = conectarBanco();
    $sql = "SELECT * FROM sala";
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

function criarSala($parametro = 0) {
    $numero = $parametro['numero_sala'];
    $capacidade = $parametro['capacidade_sala'];

    if (empty($numero) || empty($capacidade)) {
        echo json_encode(["erro" => "Número e capacidade da sala são obrigatórios"]);
        return;
    }

    $conn = conectarBanco();
    $sql = "INSERT INTO sala (numero, capacidade) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $numero, $capacidade);

    if ($stmt->execute()) {
        echo json_encode(["sucesso" => "Sala criada com sucesso"]);
    } else {
        echo json_encode(["erro" => "Erro ao criar sala: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}

function editarSala($parametro = 0) {
    $numero = $parametro['numero_sala'];
    $capacidade = $parametro['capacidade_sala'];

    if (empty($numero) || empty($capacidade)) {
        echo json_encode(["erro" => "Número e capacidade da sala são obrigatórios"]);
        return;
    }

    $conn = conectarBanco();
    $sql = "UPDATE sala SET capacidade = ? WHERE numero = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $capacidade, $numero);

    if ($stmt->execute()) {
        echo json_encode(["sucesso" => "Sala editada com sucesso"]);
    } else {
        echo json_encode(["erro" => "Erro ao editar sala: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}

function excluirSala($parametro = 0) {
    $numero = $parametro['numero_sala'];

    if (empty($numero)) {
        echo json_encode(["erro" => "Número da sala é obrigatório"]);
        return;
    }

    $conn = conectarBanco();
    $sql = "DELETE FROM sala WHERE numero = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $numero);

    if ($stmt->execute()) {
        echo json_encode(["sucesso" => "Sala excluída com sucesso"]);
    } else {
        echo json_encode(["erro" => "Erro ao excluir sala: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
