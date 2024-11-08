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
INSERT INTO coordenador_disciplina (coordenador_id, disciplina_id, acao)
VALUES 
  (1, 1, 'criada'),  -- João Silva criou Matemática Avançada
  (1, 2, 'criada'),  -- João Silva criou Física
  (1, 3, 'criada'),  -- João Silva criou Química
  (2, 4, 'criada'),  -- Carlos Santos criou História Geral
  (2, 5, 'criada');  -- Carlos Santos criou Literatura Brasileira
  
