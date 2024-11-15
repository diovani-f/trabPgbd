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



#salvar historico de exclusão

DELIMITER $$

CREATE TRIGGER trg_disciplina_delete
BEFORE DELETE ON disciplina
FOR EACH ROW
BEGIN
    -- Insere a ação no histórico
    INSERT INTO historico_disciplinas (id_coordenador, id_disciplina, acao, data_hora)
    VALUES (
        (SELECT id_coordenador FROM curso WHERE id = OLD.id_curso), -- Coordenador associado ao curso
        OLD.id, -- ID da disciplina que será excluída
        'remoção', -- Ação de remoção
        NOW() -- Data e hora atuais
    );
END$$

DELIMITER ;
