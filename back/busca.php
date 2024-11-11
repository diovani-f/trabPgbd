<?php
// buscar.php
include 'conexao.php';
header('Content-Type: application/json');

function buscarDisciplina() {
    $tabela = 'disciplina';

    // -Obrigatorio-
    $data_inicio = '10-10-2020';
    $data_final = '17-10-2020';     //isso compreende uma semana
    // -Opcional-
    $nome_disciplina = 'Matematica';
    $id_disciplina = 12;
    $professor = 'Sergio';

    
    $sql = "select * from disciplina";
    
    $parametros = " where data_inicio = $data_inicio and data_final = $data_final";
    
    if($professor){
        $parametros .= " and f.professor = " . $professor . "%";
    }

    file_put_contents('teste.txt',print_r($parametros, true) . PHP_EOL ,FILE_APPEND);
    


    $conn = conectarBanco();

    $stmt = $conn->prepare("SELECT * FROM $tabela");
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        while ($row = $resultado->fetch_assoc()) {
            $dados[] = $row;
        }
    }

    // Fechar a conexão e a consulta
    $stmt->close();
    $conn->close();
}
buscarDisciplina();
?>




<!-- Inicialmente: Pela Data que vai estar selecionada no calendario. (Talvez pelo curso)

Campos de busca opcional: Professor, Id da disciplina, Id do curso -->