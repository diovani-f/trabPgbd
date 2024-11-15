# apaga aulas caso quando uma disciplina é deletada.
DELIMITER //

CREATE TRIGGER delete_aulas_on_disciplina_delete
AFTER DELETE ON disciplina
FOR EACH ROW
BEGIN
    DELETE FROM aula
    WHERE id_disciplina = OLD.id;
END;

//

DELIMITER ;
