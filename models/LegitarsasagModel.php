<?php
require '../../config/connection.php';

class LegitarsagModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllAirlines() {
        $query = "SELECT * FROM Legitarsasag
                  ORDER BY legitarsasag_id";
        $stid = oci_parse($this->conn, $query);
        oci_execute($stid);
        $airlines = [];
        while ($row = oci_fetch_assoc($stid)) {
            $airlines[] = $row;
        }
        oci_free_statement($stid);
        return $airlines;
    }

    public function getAirlineById($id) {
        $stid = oci_parse($this->conn, "SELECT * FROM Legitarsasag WHERE legitarsasag_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        oci_execute($stid);
        $airline = oci_fetch_assoc($stid);
        oci_free_statement($stid);
        return $airline;
    }

    public function createAirline($nev, $szekhely, $orszag) {
        $stid = oci_parse($this->conn, "SELECT NVL(MAX(legitarsasag_id), 0) + 1 AS next_id FROM Legitarsasag");
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        $nextId = $row['NEXT_ID'];
        oci_free_statement($stid);

        $stid = oci_parse($this->conn, "INSERT INTO Legitarsasag (legitarsasag_id, nev, szekhely, orszag) VALUES (:id, :nev, :szekhely, :orszag)");
        oci_bind_by_name($stid, ':id', $nextId);
        oci_bind_by_name($stid, ':nev', $nev);
        oci_bind_by_name($stid, ':szekhely', $szekhely);
        oci_bind_by_name($stid, ':orszag', $orszag);
        return oci_execute($stid);
    }

    public function updateAirline($id, $nev, $szekhely, $orszag) {
        $stid = oci_parse($this->conn, "UPDATE Legitarsasag SET nev = :nev, szekhely = :szekhely, orszag = :orszag WHERE legitarsasag_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        oci_bind_by_name($stid, ':nev', $nev);
        oci_bind_by_name($stid, ':szekhely', $szekhely);
        oci_bind_by_name($stid, ':orszag', $orszag);
        return oci_execute($stid);
    }

    public function deleteAirline($id) {
        $stid = oci_parse($this->conn, "DELETE FROM Legitarsasag WHERE legitarsasag_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        return oci_execute($stid);
    }
}
?>