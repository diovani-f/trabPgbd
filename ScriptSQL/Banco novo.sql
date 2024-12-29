-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS oferta;
USE oferta;

-- Criação da tabela professor
CREATE TABLE professor (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);

-- Criação da tabela curso
CREATE TABLE curso (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    id_coordenador INT UNIQUE,
    FOREIGN KEY (id_coordenador) REFERENCES professor(id)
);

-- Criação da tabela sala
CREATE TABLE sala (
    numero INT PRIMARY KEY,
    capacidade INT NOT NULL,
    CONSTRAINT unique_numero UNIQUE (numero)
);

-- Criação da tabela disciplina

CREATE TABLE disciplina (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    carga_horaria INT NOT NULL,
    id_sala INT,
    vagas_disponiveis INT NOT NULL,
    id_professor INT,
    id_curso INT,
    FOREIGN KEY (id_professor) REFERENCES professor(id) ON DELETE SET NULL,
    FOREIGN KEY (id_curso) REFERENCES curso(id) ON DELETE SET NULL,
    FOREIGN KEY (id_sala) REFERENCES sala(numero) ON DELETE SET NULL
);

-- Tabela que guarda o histórico de inserções, remoções e alterações nas disciplinas
CREATE TABLE historico_disciplinas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_coordenador INT NOT NULL,
    id_disciplina INT NOT NULL,
    nome_disciplina varchar(100) NOT NULL,
    acao ENUM('inserção', 'remoção', 'edição') NOT NULL,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela que guarda os horários e dias que cada aula de uma determinada disciplina é ministrada
CREATE TABLE aula (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_disciplina INT NOT NULL,
    dia_da_semana ENUM('Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo') NOT NULL,
    horario_inicio TIME NOT NULL,
    horario_fim TIME NOT NULL,
    data_inicio DATE NOT NULL, 
    data_final DATE NOT NULL,
    FOREIGN KEY (id_disciplina) REFERENCES disciplina(id)
);

-- Criação da tabela de usuários (login)
CREATE TABLE usuario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('super_usuario', 'coordenador') NOT NULL
);
-- Relacionamento entre coordenador e curso
ALTER TABLE curso ADD FOREIGN KEY (id_coordenador) REFERENCES usuario(id);


-- Adiciona a coluna id_curso em historico_disciplinas
ALTER TABLE historico_disciplinas ADD COLUMN id_curso INT;

-- Cria a foreign key entre historico_disciplinas e curso
ALTER TABLE historico_disciplinas
ADD CONSTRAINT fk_historico_curso
FOREIGN KEY (id_curso) REFERENCES curso(id)
ON DELETE CASCADE;


ALTER TABLE aula
DROP FOREIGN KEY aula_ibfk_1;

ALTER TABLE aula
ADD CONSTRAINT aula_ibfk_1
FOREIGN KEY (id_disciplina) REFERENCES disciplina(id)
ON DELETE CASCADE;

CREATE DATABASE IF NOT EXISTS oferta;
USE oferta;


CREATE TABLE professor (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE
);


CREATE TABLE curso (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    id_coordenador INT UNIQUE,
    FOREIGN KEY (id_coordenador) REFERENCES professor(id)
);


CREATE TABLE sala (
    numero INT PRIMARY KEY,
    capacidade INT NOT NULL,
    CONSTRAINT unique_numero UNIQUE (numero)
);

CREATE TABLE disciplina (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    carga_horaria INT NOT NULL,
    id_sala INT,
    vagas_disponiveis INT NOT NULL,
    id_professor INT,
    id_curso INT,
    FOREIGN KEY (id_professor) REFERENCES professor(id) ON DELETE SET NULL,
    FOREIGN KEY (id_curso) REFERENCES curso(id) ON DELETE SET NULL,
    FOREIGN KEY (id_sala) REFERENCES sala(numero) ON DELETE SET NULL
);


CREATE TABLE historico_disciplinas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_coordenador INT NOT NULL,
    id_disciplina INT NOT NULL,
    nome_disciplina varchar(100) NOT NULL,
    acao ENUM('inserção', 'remoção', 'edição') NOT NULL,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE aula (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_disciplina INT NOT NULL,
    dia_da_semana ENUM('Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo') NOT NULL,
    horario_inicio TIME NOT NULL,
    horario_fim TIME NOT NULL,
    data_inicio DATE NOT NULL, 
    data_final DATE NOT NULL,    
    FOREIGN KEY (id_disciplina) REFERENCES disciplina(id)
);


CREATE TABLE usuario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('super_usuario', 'coordenador') NOT NULL
);

ALTER TABLE curso ADD FOREIGN KEY (id_coordenador) REFERENCES usuario(id);


ALTER TABLE historico_disciplinas ADD COLUMN id_curso INT;


ALTER TABLE historico_disciplinas
ADD CONSTRAINT fk_historico_curso
FOREIGN KEY (id_curso) REFERENCES curso(id)
ON DELETE CASCADE;


ALTER TABLE aula
DROP FOREIGN KEY aula_ibfk_1;

ALTER TABLE aula
ADD CONSTRAINT aula_ibfk_1
FOREIGN KEY (id_disciplina) REFERENCES disciplina(id)
ON DELETE CASCADE;



-------------------------------


DELIMITER $$

CREATE TRIGGER after_disciplina_insert
AFTER INSERT ON disciplina
FOR EACH ROW
BEGIN
    INSERT INTO historico_disciplinas (id_coordenador, id_disciplina, acao, id_curso, nome_disciplina)
    VALUES (NEW.id_professor, NEW.id, 'inserção', NEW.id_curso, NEW.nome);
END $$

DELIMITER ;


DELIMITER $$

CREATE TRIGGER after_disciplina_update
AFTER UPDATE ON disciplina
FOR EACH ROW
BEGIN
    INSERT INTO historico_disciplinas (id_coordenador, id_disciplina, acao, id_curso, nome_disciplina)
    VALUES (NEW.id_professor, NEW.id, 'edição', NEW.id_curso, NEW.nome);
END $$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER after_disciplina_delete
AFTER DELETE ON disciplina
FOR EACH ROW
BEGIN
    INSERT INTO historico_disciplinas (id_coordenador, id_disciplina, acao, id_curso, nome_disciplina)
    VALUES (OLD.id_professor, OLD.id, 'remoção', OLD.id_curso, OLD.nome);
END $$

DELIMITER ;
