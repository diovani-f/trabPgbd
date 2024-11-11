<?php
// buscar.php
include 'conexao.php';
header('Content-Type: application/json');


function buscarDisciplina() {
    // Isso vem tudo do js
    // -Obrigatorio-
    $data_inicio = '10-10-2020';
    $data_final = '17-10-2020';     //isso compreende uma semana
    // -Opcional-
    $nome_disciplina = 0;
    $id_disciplina = 0;
    $professor = 0;

    $sql = "select d.iddisciplina, d.nome, p.nome as professor from disciplina d join professor p ON p.idprofessor = d.professor_idprofessor";
    
    
    // correto
    // $sql = "select d.iddisciplina, d.nome, p.nome as professor from disciplina d join professor p ON p.idprofessor = d.professor_idprofessor 
    // where d.data_inicio < $data_inicio and d.data_fim > $data_fim ";


    //data de inicio e fim pesquisada, tem que estar dentro a data_inicio e fim do banco
    // dias da semana


    $parametros = " where ";

    if($professor){
        $parametros .= " and p.nome like '" . utf8_decode($professor) . "%'";
    }

    if($id_disciplina){
        $parametros .= " and d.iddisciplina = " . $id_disciplina;
    }

    if($nome_disciplina){
        $parametros .= " and d.nome like '" . utf8_decode($nome_disciplina) . "%'";
    }

    // $sql .= $parametros;    

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

buscarDisciplina();
?>