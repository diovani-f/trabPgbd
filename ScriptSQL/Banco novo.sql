-- Criação do banco de dados e seleção dele
CREATE DATABASE IF NOT EXISTS oferta;
USE oferta;

-- Criação da tabela professor
CREATE TABLE professor (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    coordenador BOOLEAN DEFAULT FALSE
);

-- Criação da tabela curso
CREATE TABLE curso (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    id_coordenador INT,
    FOREIGN KEY (id_coordenador) REFERENCES professor(id)
);

-- Criação da tabela sala
CREATE TABLE sala (
    numero INT PRIMARY KEY,     
    capacidade INT NOT NULL,           
    CONSTRAINT unique_nome UNIQUE (numero)
);



-- Criação da tabela disciplina
CREATE TABLE disciplina (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    carga_horaria INT NOT NULL,
    id_sala INT,                    -- Relacionamento com a tabela sala
    vagas_disponiveis INT NOT NULL,  -- Número de vagas disponíveis
    id_professor INT,
    id_curso INT,
    FOREIGN KEY (id_professor) REFERENCES professor(id),
    FOREIGN KEY (id_curso) REFERENCES curso(id),
    FOREIGN KEY (id_sala) REFERENCES sala(numero)  -- Relacionamento com sala
);


-- Tabela que guarda o histórico de inserções, remoções e alterações nas disciplinas
CREATE TABLE historico_disciplinas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_coordenador INT NOT NULL,
    id_disciplina INT NOT NULL,
    acao ENUM('inserção', 'remoção') NOT NULL,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
);

-- Tabela que guarda os horários e dias que cada aula de uma determinada disciplina é ministrada
CREATE TABLE aula (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_disciplina INT NOT NULL,
    dia_da_semana ENUM('Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo') NOT NULL,
    horario_inicio TIME NOT NULL,
    horario_fim TIME NOT NULL,
    FOREIGN KEY (id_disciplina) REFERENCES disciplina(id)
);






