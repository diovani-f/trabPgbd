<?php
// disciplina.php

include_once __DIR__ . '/../conexao.php';

/**
 * Cria uma nova aula com base nos parâmetros fornecidos.
 *
 * @param array $parametro Parâmetros do formulário de cadastro de aula.
 * @return array Resultado da operação ou erro.
 */
function criarAula($parametro = []) {
    // Verificação de campos obrigatórios
    $required_fields = ["id_disciplina", "dia_da_semana", "horario_inicio", "horario_fim", "data_inicio", "data_final"];
    foreach ($required_fields as $field) {
        if (empty($parametro[$field])) {
            return ["erro" => "Todos os campos obrigatórios devem ser preenchidos"];
        }
    }

    $id_disciplina = (int) $parametro['id_disciplina'];
    $dia_da_semana = $parametro['dia_da_semana'];
    $horario_inicio = $parametro['horario_inicio'];
    $horario_fim = $parametro['horario_fim'];
    $data_inicio = $parametro['data_inicio'];
    $data_final = $parametro['data_final'];

    // Validação de `dia_da_semana`
    $dias_validos = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
    if (!in_array($dia_da_semana, $dias_validos)) {
        return ["erro" => "O dia da semana é inválido"];
    }

    // Validação de horários
    if ($horario_inicio >= $horario_fim) {
        return ["erro" => "O horário de fim deve ser posterior ao horário de início"];
    }

    // Conexão com o banco
    $conn = conectarBanco();

    // Verifica se a disciplina existe
    $stmt_check = $conn->prepare("SELECT id FROM disciplina WHERE id = ?");
    if (!$stmt_check) {
        return ["erro" => "Erro ao preparar a consulta: " . $conn->error];
    }
    $stmt_check->bind_param("i", $id_disciplina);
    $stmt_check->execute();
    $stmt_check->store_result();
    if ($stmt_check->num_rows == 0) {
        $stmt_check->close();
        $conn->close();
        return ["erro" => "Disciplina não encontrada"];
    }
    $stmt_check->close();

    // Preparar a query SQL com Prepared Statements
    $sql = "INSERT INTO aula (id_disciplina, dia_da_semana, horario_inicio, horario_fim, data_inicio, data_final)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return ["erro" => "Erro ao preparar a consulta: " . $conn->error];
    }

    // Bind dos parâmetros
    $stmt->bind_param("isssss", $id_disciplina, $dia_da_semana, $horario_inicio, $horario_fim, $data_inicio, $data_final);

    // Executa a query
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return ["sucesso" => "Aula cadastrada com sucesso"];
    } else {
        $stmt->close();
        $conn->close();
        return ["erro" => "Erro ao cadastrar aula: " . $stmt->error];
    }
}

// Outras funções como buscarDisciplina, editarDisciplina, excluirDisciplina, etc.
// Certifique-se de que todas elas retornem arrays associativos semelhantes a ["sucesso" => "..."] ou ["erro" => "..."]

?>
