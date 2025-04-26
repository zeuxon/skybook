<?php
require '../../config/connection.php';

class UtModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllRoutes() {
        $query = "
            SELECT 
                u.ut_id, 
                r1.nev AS indulasi_repuloter_nev, 
                r2.nev AS erkezesi_repuloter_nev
            FROM Ut u
            JOIN Repuloter r1 ON u.indulasi_repuloter_id = r1.repuloter_id
            JOIN Repuloter r2 ON u.erkezesi_repuloter_id = r2.repuloter_id
            ORDER BY u.ut_id
        ";
        $stid = oci_parse($this->conn, $query);
        oci_execute($stid);
        $routes = [];
        while ($row = oci_fetch_assoc($stid)) {
            $routes[] = $row;
        }
        oci_free_statement($stid);
        return $routes;
    }

    public function getAllAirports() {
        $query = "SELECT repuloter_id, nev FROM Repuloter";
        $stid = oci_parse($this->conn, $query);
        oci_execute($stid);
        $airports = [];
        while ($row = oci_fetch_assoc($stid)) {
            $airports[] = $row;
        }
        oci_free_statement($stid);
        return $airports;
    }

    public function getRouteById($id) {
        $stid = oci_parse($this->conn, "SELECT * FROM Ut WHERE ut_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        oci_execute($stid);
        $route = oci_fetch_assoc($stid);
        oci_free_statement($stid);
        return $route;
    }

    public function createRoute($indulasi_repuloter_id, $erkezesi_repuloter_id) {
        $stid = oci_parse($this->conn, "SELECT NVL(MAX(ut_id), 0) + 1 AS next_id FROM Ut");
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        $nextId = $row['NEXT_ID'];
        oci_free_statement($stid);

        $stid = oci_parse($this->conn, "INSERT INTO Ut (ut_id, indulasi_repuloter_id, erkezesi_repuloter_id) VALUES (:id, :indulasi, :erkezesi)");
        oci_bind_by_name($stid, ':id', $nextId);
        oci_bind_by_name($stid, ':indulasi', $indulasi_repuloter_id);
        oci_bind_by_name($stid, ':erkezesi', $erkezesi_repuloter_id);
        return oci_execute($stid);
    }

    public function updateRoute($id, $indulasi_repuloter_id, $erkezesi_repuloter_id) {
        $stid = oci_parse($this->conn, "UPDATE Ut SET indulasi_repuloter_id = :indulasi, erkezesi_repuloter_id = :erkezesi WHERE ut_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        oci_bind_by_name($stid, ':indulasi', $indulasi_repuloter_id);
        oci_bind_by_name($stid, ':erkezesi', $erkezesi_repuloter_id);
        return oci_execute($stid);
    }

    public function deleteRoute($id) {
        $stid = oci_parse($this->conn, "DELETE FROM Ut WHERE ut_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        return oci_execute($stid);
    }
}
?>