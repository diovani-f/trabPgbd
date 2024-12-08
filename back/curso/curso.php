<?php
include_once __DIR__ . '/../conexao.php';
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
                $row[$key] = $value;
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

    // Verificação de campos obrigatórios
    if (empty($nome) || empty($id_coordenador)) {
        echo json_encode(["erro" => "Nome e coordenador são obrigatórios"]);
        return;
    }

    // Validação de tipos
    if (!is_string($nome) || !is_numeric($id_coordenador)) {
        echo json_encode(["erro" => "Dados inválidos para nome ou coordenador"]);
        return;
    }

    // Conexão com o banco
    $conn = conectarBanco();

    // Preparando a consulta SQL
    $sql = "INSERT INTO curso (nome, id_coordenador) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Ligando os parâmetros
    $stmt->bind_param("si", $nome, $id_coordenador);

    // Executando a consulta
    if ($stmt->execute()) {
        echo json_encode(["sucesso" => "Curso criado com sucesso"]);
    } else {
        echo json_encode(["erro" => "Erro ao criar curso: " . $stmt->error]);
    }

    // Fechando a conexão
    $stmt->close();
    $conn->close();
}


function editarCurso($parametro = 0) {
    $id = $parametro["id_curso"];
    $nome = $parametro["nome_curso"];
    $id_coordenador = $parametro["id_coordenador"];

    if (empty($id) || empty($nome) || empty($id_coordenador)) {
        echo json_encode(["erro" => "ID, nome e coordenador são obrigatórios"]);
        return;
    }

    $conn = conectarBanco();

    // Escapando os dados para evitar SQL Injection
    $id = (int) $id; // Garantir que o id seja um inteiro
    $id_coordenador = (int) $id_coordenador; // Garantir que o id_coordenador seja um inteiro
    $nome = $conn->real_escape_string($nome); // Escapar o nome

    // Construir a consulta SQL diretamente
    $sql = "UPDATE curso SET nome = '$nome', id_coordenador = $id_coordenador WHERE id = $id";

    if ($conn->query($sql)) {
        echo json_encode(["sucesso" => "Curso editado com sucesso"]);
    } else {
        echo json_encode(["erro" => "Erro ao editar curso: " . $conn->error]);
    }

    $conn->close();
}

function excluirCurso($parametro = 0) {
    // O curso não pode ter uma disciplina cadastrada
    $id = $parametro["id_curso"];

    if (empty($id)) {
        echo json_encode(["erro" => "ID do curso é obrigatório"]);
        return;
    }

    $conn = conectarBanco();

    // Verificar se há alguma disciplina associada ao curso
    $stmt = $conn->prepare("SELECT COUNT(*) FROM disciplina WHERE id_curso = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(["erro" => "Não é possível excluir o curso, pois há disciplinas cadastradas a ele"]);
        $conn->close();
        return;
    }

    // Preparar a query para excluir o curso
    $sql = "DELETE FROM curso WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // Executar a query
    if ($stmt->execute()) {
        echo json_encode(["sucesso" => "Curso excluído com sucesso"]);
    } else {
        echo json_encode(["erro" => "Erro ao excluir curso: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>