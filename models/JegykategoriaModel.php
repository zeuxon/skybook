<?php
require '../../config/connection.php';

class JegykategoriaModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllCategories() {
        $query = "SELECT * FROM Jegykategoria
                  ORDER BY jegykategoria_id";
        $stid = oci_parse($this->conn, $query);
        oci_execute($stid);
        $categories = [];
        while ($row = oci_fetch_assoc($stid)) {
            $categories[] = $row;
        }
        oci_free_statement($stid);
        return $categories;
    }

    public function getCategoryById($id) {
        $stid = oci_parse($this->conn, "SELECT * FROM Jegykategoria WHERE jegykategoria_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        oci_execute($stid);
        $category = oci_fetch_assoc($stid);
        oci_free_statement($stid);
        return $category;
    }

    public function createCategory($nev, $kedvezmeny_szazalek) {
        $stid = oci_parse($this->conn, "SELECT NVL(MAX(jegykategoria_id), 0) + 1 AS next_id FROM Jegykategoria");
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        $nextId = $row['NEXT_ID'];
        oci_free_statement($stid);

        $stid = oci_parse($this->conn, "INSERT INTO Jegykategoria (jegykategoria_id, nev, kedvezmeny_szazalek) VALUES (:id, :nev, :kedvezmeny_szazalek)");
        oci_bind_by_name($stid, ':id', $nextId);
        oci_bind_by_name($stid, ':nev', $nev);
        oci_bind_by_name($stid, ':kedvezmeny_szazalek', $kedvezmeny_szazalek);
        return oci_execute($stid);
    }

    public function updateCategory($id, $nev, $kedvezmeny_szazalek) {
        $stid = oci_parse($this->conn, "UPDATE Jegykategoria SET nev = :nev, kedvezmeny_szazalek = :kedvezmeny_szazalek WHERE jegykategoria_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        oci_bind_by_name($stid, ':nev', $nev);
        oci_bind_by_name($stid, ':kedvezmeny_szazalek', $kedvezmeny_szazalek);
        return oci_execute($stid);
    }

    public function deleteCategory($id) {
        $stid = oci_parse($this->conn, "DELETE FROM Jegykategoria WHERE jegykategoria_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        return oci_execute($stid);
    }
}
?>