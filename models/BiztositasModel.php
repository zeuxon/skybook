<?php
require '../config/connection.php';

class BiztositasModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllInsurance() {
        $query = "SELECT * FROM Biztositas";
        $stid = oci_parse($this->conn, $query);
        oci_execute($stid);
        $insurance = [];
        while ($row = oci_fetch_assoc($stid)) {
            $insurance[] = $row;
        }
        oci_free_statement($stid);
        return $insurance;
    }

    public function getInsuranceById($id) {
        $stid = oci_parse($this->conn, "SELECT * FROM Biztositas WHERE biztositas_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        oci_execute($stid);
        $insurance = oci_fetch_assoc($stid);
        oci_free_statement($stid);
        return $insurance;
    }

    public function createInsurance($nev, $ar) {
        $stid = oci_parse($this->conn, "SELECT NVL(MAX(biztositas_id), 0) + 1 AS next_id FROM Biztositas");
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        $nextId = $row['NEXT_ID'];
        oci_free_statement($stid);

        $stid = oci_parse($this->conn, "INSERT INTO Biztositas (biztositas_id, nev, ar) VALUES (:id, :nev, :ar)");
        oci_bind_by_name($stid, ':id', $nextId);
        oci_bind_by_name($stid, ':nev', $nev);
        oci_bind_by_name($stid, ':ar', $ar);
        return oci_execute($stid);
    }

    public function updateInsurance($id, $nev, $ar) {
        $stid = oci_parse($this->conn, "UPDATE Biztositas SET nev = :nev, ar = :ar WHERE biztositas_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        oci_bind_by_name($stid, ':nev', $nev);
        oci_bind_by_name($stid, ':ar', $ar);
        return oci_execute($stid);
    }

    public function deleteInsurance($id) {
        $stid = oci_parse($this->conn, "DELETE FROM Biztositas WHERE biztositas_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        return oci_execute($stid);
    }
}
?>