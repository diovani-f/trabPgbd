DELIMITER //

CREATE TRIGGER delete_aulas_cascade
AFTER DELETE ON disciplina
FOR EACH ROW
BEGIN
    DELETE FROM aula WHERE id_disciplina = OLD.id;
END //

DELIMITER ;

