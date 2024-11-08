-- Ajustes para iniciar o script
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`professor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`professor` (
  `idprofessor` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `disciplinas` TEXT NULL,
  `coordenador` BOOLEAN DEFAULT FALSE,   -- Define se o professor também é um coordenador
  PRIMARY KEY (`idprofessor`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`coordenador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`coordenador` (
  `idcoordenador` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idcoordenador`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC)
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`curso`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`curso` (
  `idcurso` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `coordenador_idcoordenador` INT NOT NULL,
  PRIMARY KEY (`idcurso`),
  INDEX `fk_curso_coordenador1_idx` (`coordenador_idcoordenador` ASC),
  CONSTRAINT `fk_curso_coordenador1`
    FOREIGN KEY (`coordenador_idcoordenador`)
    REFERENCES `mydb`.`coordenador` (`idcoordenador`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`disciplina`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`disciplina` (
  `iddisciplina` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `dept` VARCHAR(45) NOT NULL,
  `c_horaria` SMALLINT UNSIGNED NOT NULL,
  `capacidade_sala` SMALLINT UNSIGNED NOT NULL,
  `professor_idprofessor` INT NOT NULL,
  `curso_idcurso` INT NOT NULL,
  `quantidade_matriculas` SMALLINT UNSIGNED DEFAULT 0,
  PRIMARY KEY (`iddisciplina`),
  INDEX `fk_disciplina_professor_idx` (`professor_idprofessor` ASC),
  INDEX `fk_disciplina_curso1_idx` (`curso_idcurso` ASC),
  CONSTRAINT `fk_disciplina_professor`
    FOREIGN KEY (`professor_idprofessor`)
    REFERENCES `mydb`.`professor` (`idprofessor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_disciplina_curso1`
    FOREIGN KEY (`curso_idcurso`)
    REFERENCES `mydb`.`curso` (`idcurso`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `mydb`.`coordenador_disciplina`
-- -----------------------------------------------------
-- Tabela para registrar ações de criação e remoção de disciplinas pelos coordenadores
CREATE TABLE IF NOT EXISTS `mydb`.`coordenador_disciplina` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `coordenador_id` INT NOT NULL,
  `disciplina_id` INT NOT NULL,
  `acao` ENUM('criada', 'removida') NOT NULL,
  `data_acao` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `fk_coordenador_disciplina_coordenador_idx` (`coordenador_id` ASC),
  INDEX `fk_coordenador_disciplina_disciplina_idx` (`disciplina_id` ASC),
  CONSTRAINT `fk_coordenador_disciplina_coordenador`
    FOREIGN KEY (`coordenador_id`)
    REFERENCES `mydb`.`coordenador` (`idcoordenador`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_coordenador_disciplina_disciplina`
    FOREIGN KEY (`disciplina_id`)
    REFERENCES `mydb`.`disciplina` (`iddisciplina`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB;

-- Restaurar configurações
SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
