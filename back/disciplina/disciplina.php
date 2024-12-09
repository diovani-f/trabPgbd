<?php
// disciplina.php

include_once __DIR__ . '/../conexao.php';

/**
 * Cria uma nova disciplina com base nos parâmetros fornecidos.
 *
 * @param array $parametro Parâmetros do formulário de cadastro de disciplina.
 * @return array Resultado da operação ou erro.
 */
function criarDisciplina($parametro = []) {
    // Verificação de campos obrigatórios
    $required_fields = ["nome_disciplina", "carga_horaria", "vagas_disponiveis", "id_professor", "id_curso", "id_sala"];
    foreach ($required_fields as $field) {
        if (empty($parametro[$field])) {
            return ["erro" => "Todos os campos obrigatórios devem ser preenchidos"];
        }
    }

    $nome_disciplina = trim($parametro['nome_disciplina']);
    $carga_horaria = (int) $parametro['carga_horaria'];
    $vagas_disponiveis = (int) $parametro['vagas_disponiveis'];
    $id_professor = (int) $parametro['id_professor'];
    $id_curso = (int) $parametro['id_curso'];
    $id_sala = (int) $parametro['id_sala'];

    // Conexão com o banco usando conectarBanco()
    $conn = conectarBanco();
    if (!$conn) {
        return ["erro" => "Falha na conexão com o banco de dados."];
    }

    // Verifica se o professor existe
    $stmt_prof = $conn->prepare("SELECT id FROM professor WHERE id = ?");
    if (!$stmt_prof) {
        return ["erro" => "Erro ao preparar a consulta do professor: " . $conn->error];
    }
    $stmt_prof->bind_param("i", $id_professor);
    $stmt_prof->execute();
    $stmt_prof->store_result();
    if ($stmt_prof->num_rows == 0) {
        $stmt_prof->close();
        $conn->close();
        return ["erro" => "Professor não encontrado"];
    }
    $stmt_prof->close();

    // Verifica se o curso existe
    $stmt_curso = $conn->prepare("SELECT id FROM curso WHERE id = ?");
    if (!$stmt_curso) {
        return ["erro" => "Erro ao preparar a consulta do curso: " . $conn->error];
    }
    $stmt_curso->bind_param("i", $id_curso);
    $stmt_curso->execute();
    $stmt_curso->store_result();
    if ($stmt_curso->num_rows == 0) {
        $stmt_curso->close();
        $conn->close();
        return ["erro" => "Curso não encontrado"];
    }
    $stmt_curso->close();

    // Verifica se a sala existe
    $stmt_sala = $conn->prepare("SELECT numero FROM sala WHERE numero = ?");
    if (!$stmt_sala) {
        return ["erro" => "Erro ao preparar a consulta da sala: " . $conn->error];
    }
    $stmt_sala->bind_param("i", $id_sala);
    $stmt_sala->execute();
    $stmt_sala->store_result();
    if ($stmt_sala->num_rows == 0) {
        $stmt_sala->close();
        $conn->close();
        return ["erro" => "Sala não encontrada"];
    }
    $stmt_sala->close();

    // Preparar a query SQL com Prepared Statements
    $sql = "INSERT INTO disciplina (nome, carga_horaria, vagas_disponiveis, id_professor, id_curso, id_sala)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return ["erro" => "Erro ao preparar a consulta: " . $conn->error];
    }

    // Bind dos parâmetros
    $stmt->bind_param("siiiii", $nome_disciplina, $carga_horaria, $vagas_disponiveis, $id_professor, $id_curso, $id_sala);

    // Executa a query
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return ["sucesso" => "Disciplina cadastrada com sucesso"];
    } else {
        $stmt->close();
        $conn->close();
        return ["erro" => "Erro ao cadastrar disciplina: " . $stmt->error];
    }
}

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

    // Conexão com o banco usando conectarBanco()
    $conn = conectarBanco();
    if (!$conn) {
        return ["erro" => "Falha na conexão com o banco de dados."];
    }

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
