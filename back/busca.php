<?php
// buscar.php
include 'conexao.php';
header('Content-Type: application/json');

function buscarDisciplina() {
    $tabela = 'disciplina';

    // Isso vem tudo do js
    // -Obrigatorio-
    $data_inicio = '10-10-2020';
    $data_final = '17-10-2020';     //isso compreende uma semana
    // -Opcional-
    $nome_disciplina = 'Matemática';
    $id_disciplina = 1;
    $professor = 'João';

    
    $sql = "select d.iddisciplina, d.nome, p.nome as professor from disciplina d";
    // quando o pedro colocar as colunas descomentar isso e tirar o where do sql
    // $parametros = " where data_inicio = $data_inicio and data_final = $data_final";

    $parametros = " where ";
    
    if($professor){
        $sql .= " join professor p ON p.idprofessor = d.professor_idprofessor";
        //quando aplicar as outras mudanças colocar and aqui tbm
        $parametros .= " p.nome like '" . $professor . "%'";
    }

    if($id_disciplina){

        $parametros .= " and d.iddisciplina = " . $id_disciplina;
    }

    if($nome_disciplina){
        $parametros .= " and d.nome like '" . $nome_disciplina . "%'";
    }

    
    $sql .= $parametros;
    
    file_put_contents('teste.txt',print_r($sql, true) . PHP_EOL ,FILE_APPEND);


    $conn = conectarBanco();

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $dados[] = $row;
        }
    }

    file_put_contents('$dados.txt',print_r($dados, true) . PHP_EOL ,FILE_APPEND);

    // Fechar a conexão e a consulta
    $stmt->close();
    $conn->close();
}
buscarDisciplina();

//  Inicialmente: Pela Data que vai estar selecionada no calendario. (Talvez pelo curso)

// Campos de busca opcional: Professor, Id da disciplina, Id do curso 
?>