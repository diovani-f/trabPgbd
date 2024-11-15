<?php
header('Content-Type: application/json');


function buscarDisciplina() {
    // Isso vem tudo do js
    // -Obrigatorio-
    $id_curso = 1; 
    // -Opcional-
    $nome_disciplina = 0;
    $id_disciplina = 0;
    $professor = 0;
    

    $sql = "select d.id as id_disciplina, d.nome as disciplina, p.nome as professor, a.dia_da_semana, a.horario_inicio, a.horario_fim , s.numero AS sala
            from disciplina d 
            join professor p ON p.id = d.id_professor
            join aula a ON a.id_disciplina = d.id
            join sala s ON s.numero = d.id_sala
            join curso c ON c.id = d.id_curso";

    $parametros = " where c.id = " .$id_curso;

    if($professor){
        $parametros .= " and p.nome like '" . utf8_decode($professor) . "%'";
    }

    if($id_disciplina){
        $parametros .= " and d.iddisciplina = " . $id_disciplina;
    }

    if($nome_disciplina){
        $parametros .= " and d.nome like '" . utf8_decode($nome_disciplina) . "%'";
    }

    $sql .= $parametros;    

    $conn = conectarBanco();
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->get_result();


    // Inicializa um array para armazenar os dados
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

//precisa adicionar umas trigger pra dar uns delete em cascade, principalmente em aula
function excluirDisciplina() {
    $id_disciplina = 1;

    if (empty($id_disciplina)) {
        echo json_encode(["erro" => "ID da disciplina não fornecido"]);
        return;
    }

    // Monta a consulta SQL de exclusão
    $sql = "DELETE FROM disciplina WHERE id = ?";

    $conn = conectarBanco();
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $id_disciplina);

    if ($stmt->execute()) {
        echo json_encode(["sucesso" => "Disciplina excluída com sucesso"]);
    } else {
        echo json_encode(["erro" => "Erro ao excluir a disciplina"]);
    }

    // Fecha a conexão
    $stmt->close();
    $conn->close();
}


function criarDisciplina() {
    // isso aqui vem do js
    $nome = 'aaaa';
    $carga_horaria = 20; 
    $id_sala = 101;
    $vagas_disponiveis = 20;
    $id_professor = 1;
    $id_curso = 1;
    
    if (empty($nome) || empty($carga_horaria) || empty($vagas_disponiveis) || empty($id_professor) || empty($id_curso)) {
        echo json_encode(["erro" => "Todos os campos obrigatórios devem ser preenchidos"]);
        return;
    }

   $conn = conectarBanco();

   //para depuração
   $sql = "INSERT INTO disciplina (nome, carga_horaria, id_sala, vagas_disponiveis, id_professor, id_curso) 
           VALUES ('$nome', $carga_horaria, $id_sala, $vagas_disponiveis, $id_professor, $id_curso)";

   $stmt = $conn->prepare("INSERT INTO disciplina (nome, carga_horaria, id_sala, vagas_disponiveis, id_professor, id_curso) 
                           VALUES (?, ?, ?, ?, ?, ?)");

   $stmt->bind_param("siisii", $nome, $carga_horaria, $id_sala, $vagas_disponiveis, $id_professor, $id_curso);

   if ($stmt->execute()) {
       echo json_encode(["sucesso" => "Disciplina criada com sucesso"]);
   } else {
       echo json_encode(["erro" => "Erro ao criar a disciplina"]);
   }

   $stmt->close();
   $conn->close();
}

function editarDisciplina() {
$nome = 'aaaa';
    $carga_horaria = 20; 
    $id_sala = 101;
    $vagas_disponiveis = 20;
    $id_professor = 1;
    $id_curso = 1;
    $id_disciplina = 1;



    if (empty($id_disciplina) || empty($nome) || empty($carga_horaria) || empty($vagas_disponiveis) || empty($id_professor) || empty($id_curso)) {
        echo json_encode(["erro" => "Todos os campos obrigatórios devem ser preenchidos"]);
        return;
    }

    // Conectar ao banco
    $conn = conectarBanco();
    if ($conn->connect_error) {
        echo json_encode(["erro" => "Falha na conexão com o banco de dados: " . $conn->connect_error]);
        return;
    }

    // SQL para depuração
    $sql_debug = "UPDATE disciplina SET 
                    nome = '$nome',
                    carga_horaria = $carga_horaria,
                    id_sala = $id_sala,
                    vagas_disponiveis = $vagas_disponiveis,
                    id_professor = $id_professor,
                    id_curso = $id_curso
                  WHERE id = $id_disciplina";
    
    // Preparar a query com parâmetros
    $stmt = $conn->prepare("UPDATE disciplina SET 
                                nome = ?, 
                                carga_horaria = ?, 
                                id_sala = ?, 
                                vagas_disponiveis = ?, 
                                id_professor = ?, 
                                id_curso = ? 
                            WHERE id = ?");
    if (!$stmt) {
        echo json_encode(["erro" => "Erro ao preparar a query: " . $conn->error]);
        return;
    }

    $stmt->bind_param("siisiii", $nome, $carga_horaria, $id_sala, $vagas_disponiveis, $id_professor, $id_curso, $id_disciplina);

    // Executar a query
    if ($stmt->execute()) {
        echo json_encode(["sucesso" => "Disciplina editada com sucesso"]);
    } else {
        // Exibir mensagem de erro e SQL de depuração
        echo json_encode([
            "erro" => "Erro ao editar a disciplina: " . $stmt->error,
            "sql" => $sql_debug
        ]);
    }

    // Fechar conexão
    $stmt->close();
    $conn->close();
}


?>