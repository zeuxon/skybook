CREATE OR REPLACE FUNCTION LefoglaltHelyek(jarat_id_in IN NUMBER)
  RETURN SYS_REFCURSOR
IS
  eredmeny SYS_REFCURSOR;
BEGIN
  OPEN eredmeny FOR
    SELECT f.sor, f.oszlop
    FROM Foglalas f
    JOIN Jegy j ON f.jegy_id = j.jegy_id
    WHERE j.jarat_id = jarat_id_in;

  RETURN eredmeny;
END;

CREATE OR REPLACE PROCEDURE CreateBooking (
    p_felhasznalo_id IN NUMBER,
    p_jegy_id IN NUMBER,
    p_datum IN DATE,
    p_statusz IN VARCHAR2
) AS
    v_foglalva NUMBER;
    v_next_id NUMBER;
BEGIN
    SELECT foglalva INTO v_foglalva FROM Jegy WHERE jegy_id = p_jegy_id;

    IF v_foglalva = 1 THEN
            RAISE_APPLICATION_ERROR(-20001, 'A jegy már foglalt.');
    END IF;

    SELECT NVL(MAX(foglalas_id), 0) + 1 INTO v_next_id FROM Foglalas;

    INSERT INTO Foglalas (foglalas_id, felhasznalo_id, jegy_id, datum, statusz)
    VALUES (v_next_id, p_felhasznalo_id, p_jegy_id, p_datum, p_statusz);
END;