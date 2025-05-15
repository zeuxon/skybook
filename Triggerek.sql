CREATE OR REPLACE TRIGGER jarat_fissites
AFTER UPDATE OF indulasi_ido, erkezesi_ido, ut_id 
ON Repulojarat
FOR EACH ROW
DECLARE
    v_uzenet VARCHAR(4000);
    v_indulas_id NUMBER(5);
    v_erkezes_id NUMBER(5);
    v_indulasi_repter VARCHAR(255);
    v_erkezesi_repter VARCHAR(255);
    v_id_count NUMBER;
BEGIN
    IF UPDATING THEN
        IF :OLD.indulasi_ido != :NEW.indulasi_ido OR :OLD.erkezesi_ido != :NEW.erkezesi_ido THEN
            v_uzenet := 'A ' || :OLD.jaratid || ' járat indulási vagy érkezési dátuma módosult! Aktuális indulási dátuma: ' ||
            TO_CHAR(:NEW.indulasi_ido, 'YYYY-MM-DD HH24:MI') || ', aktuális érkezési dátuma: ' ||
            TO_CHAR(:NEW.erkezesi_ido, 'YYYY-MM-DD HH24:MI');
        END IF;
            
        IF :OLD.ut_id != :NEW.ut_id THEN
            SELECT indulasi_repuloter_id, erkezesi_repuloter_id
            INTO v_indulas_id, v_erkezes_id
            FROM Ut
            WHERE ut_id = :NEW.ut_id;

            SELECT nev INTO v_indulasi_repter FROM Repuloter WHERE repuloter_id = v_indulas_id;
            SELECT nev INTO v_erkezesi_repter FROM Repuloter WHERE repuloter_id = v_erkezes_id;

            v_uzenet := v_uzenet || '\nA ' || :OLD.jaratid || ' járat útvonala módosult! Aktuális indulási repülőtér: ' || v_indulasi_repter || ', aktuális érkezési repülőtér: ' || v_erkezesi_repter;
        END IF;

    END IF;


    SELECT COUNT(*) INTO v_id_count FROM Jarat_valtozas_log WHERE jaratid = :OLD.jaratid;

    IF v_id_count > 0 THEN
        UPDATE Jarat_valtozas_log
        SET jarat_valtozas = v_uzenet
        WHERE jaratid = :OLD.jaratid;
    ELSE
        INSERT INTO Jarat_valtozas_log (jaratid, jarat_valtozas)
        VALUES (:OLD.jaratid, v_uzenet);
    END IF;
END;
/

CREATE OR REPLACE TRIGGER jarat_torles
BEFORE DELETE
ON Repulojarat
FOR EACH ROW
DECLARE
    v_uzenet VARCHAR2(4000);
    v_id_count NUMBER;
BEGIN
    v_uzenet := 'A ' || :OLD.jaratid || ' járat törölve lett!';

    SELECT COUNT(*) INTO v_id_count
    FROM Jarat_valtozas_log
    WHERE jaratid = :OLD.jaratid;

    IF v_id_count > 0 THEN
        UPDATE Jarat_valtozas_log
        SET jarat_valtozas = v_uzenet
        WHERE jaratid = :OLD.jaratid;
    ELSE
        INSERT INTO Jarat_valtozas_log (jaratid, jarat_valtozas)
        VALUES (:OLD.jaratid, v_uzenet);
    END IF;
END;
/