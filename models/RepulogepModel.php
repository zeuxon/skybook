<?php
require '../../config/connection.php';

class RepulogepModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllAircraft() {
        $query = "SELECT r.repulogep_id, r.kapacitas, r.tipus, l.nev AS legitarsasag_nev 
                  FROM Repulogep r 
                  JOIN Legitarsasag l ON r.legitarsasag_id = l.legitarsasag_id
                  ORDER BY r.repulogep_id";
        $stid = oci_parse($this->conn, $query);
        oci_execute($stid);
        $aircraft = [];
        while ($row = oci_fetch_assoc($stid)) {
            $aircraft[] = $row;
        }
        oci_free_statement($stid);
        return $aircraft;
    }

    public function getAircraftById($id) {
        $stid = oci_parse($this->conn, "SELECT * FROM Repulogep WHERE repulogep_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        oci_execute($stid);
        $aircraft = oci_fetch_assoc($stid);
        oci_free_statement($stid);
        return $aircraft;
    }

    public function getLegitarsasagIdByName($name) {
        $stid = oci_parse($this->conn, "SELECT legitarsasag_id FROM Legitarsasag WHERE nev = :name");
        oci_bind_by_name($stid, ':name', $name);
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        oci_free_statement($stid);
        return $row ? $row['LEGITARSASAG_ID'] : null;
    }
    
    public function createAircraft($legitarsasag_name, $kapacitas, $tipus) {
        $legitarsasag_id = $this->getLegitarsasagIdByName($legitarsasag_name);
        if (!$legitarsasag_id) {
            return false;
        }
    
        $stid = oci_parse($this->conn, "SELECT NVL(MAX(repulogep_id), 0) + 1 AS next_id FROM Repulogep");
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        $nextId = $row['NEXT_ID'];
        oci_free_statement($stid);
    
        $stid = oci_parse($this->conn, "INSERT INTO Repulogep (repulogep_id, legitarsasag_id, kapacitas, tipus) VALUES (:id, :legitarsasag_id, :kapacitas, :tipus)");
        oci_bind_by_name($stid, ':id', $nextId);
        oci_bind_by_name($stid, ':legitarsasag_id', $legitarsasag_id);
        oci_bind_by_name($stid, ':kapacitas', $kapacitas);
        oci_bind_by_name($stid, ':tipus', $tipus);
        return oci_execute($stid);
    }
    
    public function updateAircraft($id, $legitarsasag_name, $kapacitas, $tipus) {
        $legitarsasag_id = $this->getLegitarsasagIdByName($legitarsasag_name);
        if (!$legitarsasag_id) {
            return false;
        }
    
        $stid = oci_parse($this->conn, "UPDATE Repulogep SET legitarsasag_id = :legitarsasag_id, kapacitas = :kapacitas, tipus = :tipus WHERE repulogep_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        oci_bind_by_name($stid, ':legitarsasag_id', $legitarsasag_id);
        oci_bind_by_name($stid, ':kapacitas', $kapacitas);
        oci_bind_by_name($stid, ':tipus', $tipus);
        return oci_execute($stid);
    }

    public function deleteAircraft($id) {
        $stid = oci_parse($this->conn, "DELETE FROM Repulogep WHERE repulogep_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        return oci_execute($stid);
    }
}
?>