<?php
require '../../config/connection.php';

class RepulojaratModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllFlights() {
        $query = "SELECT r.jaratid, r.indulasi_ido, r.erkezesi_ido, 
                         g.tipus AS repulogep_tipus, 
                         lg.nev AS legitarsasag_nev, 
                         ir.nev AS indulasi_repuloter_nev, 
                         er.nev AS erkezesi_repuloter_nev
                  FROM Repulojarat r
                  JOIN Repulogep g ON r.repulogep_id = g.repulogep_id
                  JOIN Legitarsasag lg ON g.legitarsasag_id = lg.legitarsasag_id
                  JOIN Ut u ON r.ut_id = u.ut_id
                  JOIN Repuloter ir ON u.indulasi_repuloter_id = ir.repuloter_id
                  JOIN Repuloter er ON u.erkezesi_repuloter_id = er.repuloter_id
                  ORDER BY r.jaratid";
        $stid = oci_parse($this->conn, $query);
        oci_execute($stid);
        $flights = [];
        while ($row = oci_fetch_assoc($stid)) {
            $flights[] = $row;
        }
        oci_free_statement($stid);
        return $flights;
    }

    public function getConnections($fromAirportId, $toAirportId) {
        $query = "
        SELECT
            r1.jaratid AS first_flight_id,
            ir1.nev AS first_departure_airport,
            r1.indulasi_ido AS first_departure_time,
            r1.erkezesi_ido AS first_arrival_time,
            er1.nev AS first_arrival_airport,
            r2.jaratid AS second_flight_id,
            ir2.nev AS second_departure_airport,
            r2.indulasi_ido AS second_departure_time,
            r2.erkezesi_ido AS second_arrival_time,
            er2.nev AS second_arrival_airport
        FROM
            Repulojarat r1
            JOIN Ut u1 ON r1.ut_id = u1.ut_id
            JOIN Repuloter ir1 ON u1.indulasi_repuloter_id = ir1.repuloter_id
            JOIN Repuloter er1 ON u1.erkezesi_repuloter_id = er1.repuloter_id
            JOIN Repulojarat r2 ON r1.erkezesi_ido <= r2.indulasi_ido
            JOIN Ut u2 ON r2.ut_id = u2.ut_id
            JOIN Repuloter ir2 ON u2.indulasi_repuloter_id = ir2.repuloter_id
            JOIN Repuloter er2 ON u2.erkezesi_repuloter_id = er2.repuloter_id
        WHERE
            u1.indulasi_repuloter_id = :fromAirportId
            AND u2.erkezesi_repuloter_id = :toAirportId
        ORDER BY
            r1.indulasi_ido, r2.indulasi_ido";
    
        $stid = oci_parse($this->conn, $query);
        oci_bind_by_name($stid, ':fromAirportId', $fromAirportId);
        oci_bind_by_name($stid, ':toAirportId', $toAirportId);
        oci_execute($stid);
    
        $connections = [];
        while ($row = oci_fetch_assoc($stid)) {
            $connections[] = $row;
        }
        oci_free_statement($stid);
        return $connections;
    }

    public function getAllFlightsWithTickets() {
        $query = "SELECT r.jaratid, r.indulasi_ido, r.erkezesi_ido, 
                         g.tipus AS repulogep_tipus, 
                         lg.nev AS legitarsasag_nev, 
                         ir.nev AS indulasi_repuloter_nev, 
                         er.nev AS erkezesi_repuloter_nev,
                         j.jegy_id, 
                         jk.nev AS jegykategoria_nev, 
                         j.ar AS jegy_ar
                  FROM Repulojarat r
                  JOIN Repulogep g ON r.repulogep_id = g.repulogep_id
                  JOIN Legitarsasag lg ON g.legitarsasag_id = lg.legitarsasag_id
                  JOIN Ut u ON r.ut_id = u.ut_id
                  JOIN Repuloter ir ON u.indulasi_repuloter_id = ir.repuloter_id
                  JOIN Repuloter er ON u.erkezesi_repuloter_id = er.repuloter_id
                  LEFT JOIN Jegy j ON j.jarat_id = r.jaratid AND j.foglalva = 0
                  LEFT JOIN Jegykategoria jk ON j.jegykategoria_id = jk.jegykategoria_id
                  ORDER BY r.jaratid, j.jegy_id";
        $stid = oci_parse($this->conn, $query);
        oci_execute($stid);
        $flights = [];
        while ($row = oci_fetch_assoc($stid)) {
            $flights[] = $row;
        }
        oci_free_statement($stid);
        return $flights;
    }

    public function getPopularFlights() {
        $query = "SELECT
                      i.nev AS indulasi_repuloter_nev,
                      e.nev AS erkezesi_repuloter_nev,
                      COUNT(f.foglalas_id) AS foglalasok_szama
                  FROM Foglalas f
                  JOIN Jegy j ON f.jegy_id = j.jegy_id
                  JOIN Repulojarat r ON j.jarat_id = r.jaratid
                  JOIN Ut u ON r.ut_id = u.ut_id
                  JOIN Repuloter i ON u.indulasi_repuloter_id = i.repuloter_id
                  JOIN Repuloter e ON u.erkezesi_repuloter_id = e.repuloter_id
                  GROUP BY i.nev, e.nev, u.indulasi_repuloter_id, u.erkezesi_repuloter_id
                  ORDER BY foglalasok_szama DESC
                  FETCH FIRST ROWS WITH TIES";
        $stid = oci_parse($this->conn, $query);
        oci_execute($stid);
        $flights = [];
        while ($row = oci_fetch_assoc($stid)) {
            $flights[] = $row;
        }
        oci_free_statement($stid);
        return $flights;
    }

    public function getFlightById($id) {
        $stid = oci_parse($this->conn, "SELECT * FROM Repulojarat WHERE jaratid = :id");
        oci_bind_by_name($stid, ':id', $id);
        oci_execute($stid);
        $flight = oci_fetch_assoc($stid);
        oci_free_statement($stid);
        return $flight;
    }

    public function getRepulogepIdByType($type) {
        $stid = oci_parse($this->conn, "SELECT repulogep_id FROM Repulogep WHERE tipus = :type");
        oci_bind_by_name($stid, ':type', $type);
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        oci_free_statement($stid);
        return $row ? $row['REPULOGEP_ID'] : null;
    }
    
    public function getRepuloterIdByName($name) {
        $stid = oci_parse($this->conn, "SELECT repuloter_id FROM Repuloter WHERE nev = :name");
        oci_bind_by_name($stid, ':name', $name);
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        oci_free_statement($stid);
        return $row ? $row['REPULOTER_ID'] : null;
    }
    
    public function getUtIdByRoute($from, $to) {
        $indulasi_repuloter_id = $this->getRepuloterIdByName($from);
        $erkezesi_repuloter_id = $this->getRepuloterIdByName($to);
        echo $indulasi_repuloter_id . " - " . $erkezesi_repuloter_id . "<br>";
    
        if (!$indulasi_repuloter_id || !$erkezesi_repuloter_id) {
            return null;
        }
    
        $stid = oci_parse($this->conn, "SELECT ut_id FROM Ut 
                                        WHERE indulasi_repuloter_id = :indulasi_id
                                        AND erkezesi_repuloter_id = :erkezesi_id");
        oci_bind_by_name($stid, ':indulasi_id', $indulasi_repuloter_id);
        oci_bind_by_name($stid, ':erkezesi_id', $erkezesi_repuloter_id);
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        echo $stid . "<br>";
        oci_free_statement($stid);
        echo $row['UT_ID'] . "<br>";
        return $row ? $row['UT_ID'] : null;
    }
    
    public function createFlight($repulogep_type, $ut_id, $indulasi_ido, $erkezesi_ido) {
        $repulogep_id = $this->getRepulogepIdByType($repulogep_type);
        if (!$repulogep_id || !$ut_id) {
            return false;
        }
    
        $stid = oci_parse($this->conn, "SELECT NVL(MAX(jaratid), 0) + 1 AS next_id FROM Repulojarat");
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        $nextId = $row['NEXT_ID'];
        oci_free_statement($stid);
    
        $indulasi_ido = str_replace('T', ' ', $indulasi_ido);
        $erkezesi_ido = str_replace('T', ' ', $erkezesi_ido);
    
        $stid = oci_parse($this->conn, "INSERT INTO Repulojarat (jaratid, repulogep_id, ut_id, indulasi_ido, erkezesi_ido) 
                                        VALUES (:id, :repulogep_id, :ut_id, TO_DATE(:indulasi_ido, 'YYYY-MM-DD HH24:MI:SS'), TO_DATE(:erkezesi_ido, 'YYYY-MM-DD HH24:MI:SS'))");
        oci_bind_by_name($stid, ':id', $nextId);
        oci_bind_by_name($stid, ':repulogep_id', $repulogep_id);
        oci_bind_by_name($stid, ':ut_id', $ut_id);
        oci_bind_by_name($stid, ':indulasi_ido', $indulasi_ido);
        oci_bind_by_name($stid, ':erkezesi_ido', $erkezesi_ido);
    
        return oci_execute($stid);
    }
    
    public function updateFlight($id, $repulogep_type, $ut_id, $indulasi_ido, $erkezesi_ido) {
        $repulogep_id = $this->getRepulogepIdByType($repulogep_type);
        if (!$repulogep_id || !$ut_id) {
            return false;
        }
    
        $indulasi_ido = str_replace('T', ' ', $indulasi_ido);
        $erkezesi_ido = str_replace('T', ' ', $erkezesi_ido);
    
        $stid = oci_parse($this->conn, "UPDATE Repulojarat 
                                        SET repulogep_id = :repulogep_id, 
                                            ut_id = :ut_id, 
                                            indulasi_ido = TO_DATE(:indulasi_ido, 'YYYY-MM-DD HH24:MI:SS'), 
                                            erkezesi_ido = TO_DATE(:erkezesi_ido, 'YYYY-MM-DD HH24:MI:SS') 
                                        WHERE jaratid = :id");
        oci_bind_by_name($stid, ':id', $id);
        oci_bind_by_name($stid, ':repulogep_id', $repulogep_id);
        oci_bind_by_name($stid, ':ut_id', $ut_id);
        oci_bind_by_name($stid, ':indulasi_ido', $indulasi_ido);
        oci_bind_by_name($stid, ':erkezesi_ido', $erkezesi_ido);
    
        return oci_execute($stid);
    }

    public function deleteFlight($id) {
        $stid = oci_parse($this->conn, "DELETE FROM Repulojarat WHERE jaratid = :id");
        oci_bind_by_name($stid, ':id', $id);
        return oci_execute($stid);
    }
}
?>