<?php
header('Content-Type: application/json');
include_once 'conexao.php';

function criarSala($parametro = 0) {
    $numero = $parametro['numero_sala']; 
    $capacidade = $parametro['capacidade'];

    if (empty($numero) || empty($capacidade)) {
        echo json_encode(["erro" => "Todos os campos obrigatórios devem ser preenchidos"]);
        return;
    }

    $conn = conectarBanco();

    // Convertendo os valores para inteiros, caso necessário
    $numero = (int) $numero;
    $capacidade = (int) $capacidade;

    // Construindo a consulta SQL diretamente
    $sql = "INSERT INTO sala (numero, capacidade) VALUES ($numero, $capacidade)";

    // Executando a consulta
    if ($conn->query($sql)) {
        echo json_encode(["sucesso" => "Sala criada com sucesso"]);
    } else {
        echo json_encode(["erro" => "Erro ao criar a sala: " . $conn->error]);
    }

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

    // Escapando as variáveis para evitar SQL Injection
    $numero = (int) $numero; // Garantir que o número seja um inteiro
    $capacidade = (int) $capacidade; // Garantir que a capacidade seja um inteiro

    // Construindo a consulta SQL diretamente
    $sql = "UPDATE sala SET capacidade = $capacidade WHERE numero = $numero";

    // Executando a consulta
    if ($conn->query($sql)) {
        echo json_encode(["sucesso" => "Sala editada com sucesso"]);
    } else {
        echo json_encode(["erro" => "Erro ao editar a sala: " . $conn->error]);
    }

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

    // Verificar se há alguma disciplina associada à sala
    $stmt = $conn->prepare("SELECT COUNT(*) FROM disciplina WHERE id_sala = ?");
    $stmt->bind_param("i", $numero);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(["erro" => "Não é possível excluir a sala, pois há disciplinas cadastradas nela"]);
        $conn->close();
        return;
    }

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
                $row[$key] = ($value);
            }
            $dados[] = $row;
        }
    }

    $stmt->close();
    $conn->close();

    echo json_encode($dados);
}
?>