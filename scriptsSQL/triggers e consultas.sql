DELIMITER //

CREATE TRIGGER after_disciplina_delete
AFTER DELETE ON disciplina
FOR EACH ROW
BEGIN
  -- Insere um registro na tabela de histórico indicando que a disciplina foi removida
  INSERT INTO coordenador_disciplina (coordenador_id, disciplina_id, acao, data_acao)
  VALUES (
    (SELECT coordenador_idcoordenador FROM curso WHERE idcurso = OLD.curso_idcurso),
    OLD.iddisciplina,
    'removida',
    NOW()
  );
END //

DELIMITER ;


-- Início do script para atualizar e adicionar novas funcionalidades ao banco de dados

-- 1. Alteração na tabela disciplina para adicionar a coluna de quantidade de matrículas
ALTER TABLE disciplina
ADD COLUMN quantidade_matriculas SMALLINT UNSIGNED DEFAULT 0;

-- 2. Trigger para verificar se o número de matrículas não ultrapassa a capacidade da sala
DELIMITER //

CREATE TRIGGER before_add_matricula
BEFORE UPDATE ON disciplina
FOR EACH ROW
BEGIN
  -- Verifica se a quantidade de matrículas não ultrapassa a capacidade da sala
  IF NEW.quantidade_matriculas > NEW.capacidade_sala THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Capacidade da sala atingida. Não é possível adicionar mais alunos.';
  END IF;
END //

DELIMITER ;

-- 3. Trigger para verificar a remoção de matrículas
DELIMITER //

CREATE TRIGGER before_remove_matricula
BEFORE UPDATE ON disciplina
FOR EACH ROW
BEGIN
  -- Verifica se o número de matrículas está sendo reduzido corretamente
  IF NEW.quantidade_matriculas < OLD.quantidade_matriculas THEN
    -- Caso contrário, tudo bem, apenas diminui a matrícula
    -- Aqui não precisa de verificação de capacidade, pois a sala não tem limitações para redução
  END IF;
END //

DELIMITER ;

-- 4. Trigger para garantir que o coordenador só edite disciplinas do curso que ele coordena
DELIMITER //

CREATE TRIGGER check_coordenador_permission
BEFORE UPDATE ON disciplina
FOR EACH ROW
BEGIN
  -- Verifica se o coordenador é o responsável pelo curso da disciplina
  DECLARE coordenador_do_curso INT;
  
  SELECT coordenador_idcoordenador INTO coordenador_do_curso
  FROM curso
  WHERE idcurso = NEW.curso_idcurso;
  
  IF coordenador_do_curso != (SELECT idprofessor FROM professor WHERE idprofessor = NEW.professor_idprofessor) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Você não tem permissão para alterar esta disciplina fora do seu curso';
  END IF;
END //

DELIMITER ;

-- 5. Trigger para registrar a remoção de uma disciplina no histórico
DELIMITER //

CREATE TRIGGER after_disciplina_delete
AFTER DELETE ON disciplina
FOR EACH ROW
BEGIN
  -- Insere um registro na tabela de histórico indicando que a disciplina foi removida
  INSERT INTO coordenador_disciplina (coordenador_id, disciplina_id, acao, data_acao)
  VALUES (
    (SELECT coordenador_idcoordenador FROM curso WHERE idcurso = OLD.curso_idcurso),
    OLD.iddisciplina,
    'removida',
    NOW()
  );
END //

DELIMITER ;

-- 6. Inserção de dados de exemplo (professores, coordenadores, cursos, disciplinas)
-- Inserindo dados na tabela professor
INSERT INTO professor (nome, email, disciplinas, coordenador)
VALUES 
  ('João Silva', 'joao.silva@universidade.edu', 'Matemática Avançada, Física', TRUE),
  ('Maria Oliveira', 'maria.oliveira@universidade.edu', 'Química, Biologia', FALSE),
  ('Carlos Santos', 'carlos.santos@universidade.edu', 'História', TRUE),
  ('Ana Souza', 'ana.souza@universidade.edu', 'Literatura', FALSE);

-- Inserindo dados na tabela coordenador
INSERT INTO coordenador (nome, email)
VALUES 
  ('João Silva', 'joao.silva@universidade.edu'),  -- Coordenador do curso de Exatas
  ('Carlos Santos', 'carlos.santos@universidade.edu'); -- Coordenador do curso de Humanas

-- Inserindo dados na tabela curso
INSERT INTO curso (nome, coordenador_idcoordenador)
VALUES 
  ('Engenharia da Computação', 1),  -- Curso de Exatas coordenado por João Silva
  ('História', 2);  -- Curso de Humanas coordenado por Carlos Santos

-- Inserindo dados na tabela disciplina
INSERT INTO disciplina (nome, dept, c_horaria, capacidade_sala, professor_idprofessor, curso_idcurso)
VALUES 
  ('Matemática Avançada', 'Departamento de Exatas', 60, 40, 1, 1),
  ('Física', 'Departamento de Exatas', 45, 40, 1, 1),
  ('Química', 'Departamento de Exatas', 60, 30, 2, 1),
  ('História Geral', 'Departamento de Humanas', 50, 35, 3, 2),
  ('Literatura Brasileira', 'Departamento de Humanas', 40, 25, 4, 2);

-- Inserindo dados na tabela coordenador_disciplina (ações de criação e remoção)
INSERT INTO coordenador_disciplina (coordenador_id, disciplina_id, acao, data_acao)
VALUES 
  (1, 1, 'criada', NOW()),  -- João Silva criou Matemática Avançada
  (1, 2, 'criada', NOW()),  -- João Silva criou Física
  (1, 3, 'criada', NOW()),  -- João Silva criou Química
  (2, 4, 'criada', NOW()),  -- Carlos Santos criou História Geral
  (2, 5, 'criada', NOW());  -- Carlos Santos criou Literatura Brasileira

-- 7. Consultas de filtros para disciplinas
-- Filtro por Código
SELECT * FROM disciplina WHERE iddisciplina = ?;

-- Filtro por Nome
SELECT * FROM disciplina WHERE nome LIKE '%?%';

-- Filtro por Curso
SELECT * FROM disciplina WHERE curso_idcurso = ?;

-- Filtro Combinado (Código, Nome ou Curso)
SELECT * FROM disciplina
WHERE (iddisciplina = ? OR ? IS NULL)
  AND (nome LIKE ? OR ? IS NULL)
  AND (curso_idcurso = ? OR ? IS NULL);

-- Fim do script
