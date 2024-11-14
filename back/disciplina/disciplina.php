<?php
header('Content-Type: application/json');


function buscaDisciplina() {
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
    // Vem do JS
    $id_disciplina = 1; // O ID da disciplina a ser excluída
    if ($id_disciplina) {
        $sql = "DELETE FROM disciplina WHERE id = $id_disciplina";

        $conn = conectarBanco();
        
        if ($conn->query($sql) === TRUE) {
            $resultado = ['status' => 'sucesso', 'mensagem' => 'Disciplina excluída com sucesso.'];
        } else {
            $resultado = ['status' => 'erro', 'mensagem' => 'Erro ao excluir a disciplina: ' . $conn->error];
        }

        $conn->close();

        file_put_contents('resultado.txt', print_r($resultado, true) . PHP_EOL, FILE_APPEND);

        echo json_encode($resultado);
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'ID da disciplina não fornecido.']);
    }
}
?>