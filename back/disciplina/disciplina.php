<?php
header('Content-Type: application/json');
include_once __DIR__ . '/../conexao.php';

function buscarDisciplina($parametro = 0) {
    // Isso vem tudo do js
    // -Obrigatorio-
    $id_curso = $parametro["id_curso"];
    $data_inicio = $parametro["data_inicio"];
    $data_final = $parametro["data_final"];

    // // -Opcional-
    if(!empty($parametro["nome_professor"]))
        $professor = " and p.nome like '" . $parametro["nome_professor"] . "%'";
    else
        $professor = "";
    

    if(!empty($parametro["id_disciplina"]))
        $id_disciplina = " and d.id = " . $parametro["id_disciplina"];
    else
        $id_disciplina = "";


    if(!empty($parametro["nome_disciplina"]))
        $nome_disciplina = " and d.nome like '" . $parametro["nome_disciplina"] . "%'";
    else
        $nome_disciplina = "";
    
    $sql = "select 
            d.id as id_disciplina, 
            d.nome as disciplina, 
            p.nome as professor, 
            a.dia_da_semana, 
            a.horario_inicio, 
            a.horario_fim , 
            s.numero AS sala
            from disciplina d 
            join professor p ON p.id = d.id_professor
            join aula a ON a.id_disciplina = d.id
            join sala s ON s.numero = d.id_sala
            join curso c ON c.id = d.id_curso 
            where 
            c.id = $id_curso 
            and a.data_inicio <= '$data_inicio' 
            and a.data_final >= '$data_final'
            $professor
            $id_disciplina
            $nome_disciplina";
    

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
        
        foreach ($dados as $index => $item) {
            $dados[$index]['dia_semana'] = buscardiadasemana($data_inicio, $data_final, $item['dia_da_semana']);
        }

        $stmt->close();
        $conn->close();

        echo json_encode($dados);
}

function buscardiadasemana($dataInicio, $dataFim, $diaSemanaDesejado){
    // Mapear dias da semana de inglês para português
    $diasSemana = [
        'Monday'    => 'Segunda',
        'Tuesday'   => 'Terça',
        'Wednesday' => 'Quarta',
        'Thursday'  => 'Quinta',
        'Friday'    => 'Sexta',
        'Saturday'  => 'Sábado',
        'Sunday'    => 'Domingo',
    ];

    // Converter para DateTime
    $inicio = new DateTime($dataInicio);
    $fim = new DateTime($dataFim);

    // Iterar sobre o intervalo
    $periodo = new DatePeriod($inicio, new DateInterval('P1D'), $fim);    

    foreach ($periodo as $data) {
        $diaSemanaAtual = $diasSemana[$data->format('l')]; // Traduz para português
        if ($diaSemanaAtual === $diaSemanaDesejado) {
            return $data->format('Y-m-d');
        }
    }

}

function buscarDisciplina_listagem($parametro = []) {
    // Isso vem tudo do js
    // -Obrigatorio-
    $id_curso = $parametro["id_curso"];

    // // -Opcional-
    if(!empty($parametro["nome_professor"]))
        $professor = " and p.nome like '" . $parametro["nome_professor"] . "%'";
    else
        $professor = "";
    
    if(!empty($parametro["id_disciplina"]))
        $id_disciplina = " and d.id = " . $parametro["id_disciplina"];
    else
        $id_disciplina = "";


    if(!empty($parametro["nome_disciplina"]))
        $nome_disciplina = " and d.nome like '" . $parametro["nome_disciplina"] . "%'";
    else
        $nome_disciplina = "";
    
    $sql = "select 
            d.id as id_disciplina, 
            d.nome as disciplina, 
            p.nome as professor, 
            a.dia_da_semana, 
            a.horario_inicio, 
            a.horario_fim , 
            s.numero AS sala
            from disciplina d 
            join professor p ON p.id = d.id_professor
            join aula a ON a.id_disciplina = d.id
            join sala s ON s.numero = d.id_sala
            join curso c ON c.id = d.id_curso 
            where 
            c.id = $id_curso 
            $professor
            $id_disciplina
            $nome_disciplina";
    

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

function excluirDisciplina($parametro = 0) {
    $id_disciplina = $parametro["id_disciplina"];

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

function editarDisciplina($parametro = 0) {

    $nome = $parametro["nome_disciplina"];
    $carga_horaria = $parametro["carga_horaria"]; 
    $id_sala = $parametro["id_sala"];
    $vagas_disponiveis = $parametro["vagas_disponiveis"];
    $id_professor = $parametro["id_professor"];
    $id_curso = $parametro["id_curso"];
    $id_disciplina = $parametro["id_disciplina"];


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
}?>