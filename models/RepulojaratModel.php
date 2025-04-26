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
                         ir.nev AS indulasi_repuloter_nev, 
                         er.nev AS erkezesi_repuloter_nev
                  FROM Repulojarat r
                  JOIN Repulogep g ON r.repulogep_id = g.repulogep_id
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
        // Convert airport names to their IDs
        $indulasi_repuloter_id = $this->getRepuloterIdByName($from);
        $erkezesi_repuloter_id = $this->getRepuloterIdByName($to);
        echo $indulasi_repuloter_id . " - " . $erkezesi_repuloter_id . "<br>";
    
        if (!$indulasi_repuloter_id || !$erkezesi_repuloter_id) {
            return null; // Return null if either airport ID is not found
        }
    
        // Query the Ut table using the airport IDs
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