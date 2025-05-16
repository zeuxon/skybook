<?php
require '../../config/connection.php';

class FoglalasModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllBookings() {
        $query = "SELECT f.foglalas_id, 
                         fel.nev AS felhasznalo_nev, 
                         TO_CHAR(f.datum, 'YYYY.MM.DD') AS datum, 
                         f.statusz, 
                         jk.nev AS jegykategoria_nev, 
                         j.ar AS jegy_ar
                  FROM Foglalas f
                  JOIN Felhasznalo fel ON f.felhasznalo_id = fel.felhasznalo_id
                  JOIN Jegy j ON f.jegy_id = j.jegy_id
                  JOIN Jegykategoria jk ON j.jegykategoria_id = jk.jegykategoria_id
                  ORDER BY f.foglalas_id";
        $stid = oci_parse($this->conn, $query);
        oci_execute($stid);
    
        $bookings = [];
        while ($row = oci_fetch_assoc($stid)) {
            $bookings[] = $row;
        }
        oci_free_statement($stid);
        return $bookings;
    }

    public function getBookingCountByUser($felhasznalo_id) {
        $query = "SELECT COUNT(*) AS BOOKING_COUNT
                FROM (
                    SELECT f.foglalas_id
                    FROM Foglalas f
                    JOIN Jegy j ON f.jegy_id = j.jegy_id
                    WHERE f.felhasznalo_id = :felhasznalo_id
                    GROUP BY f.foglalas_id
                )";
        $stid = oci_parse($this->conn, $query);
        oci_bind_by_name($stid, ':felhasznalo_id', $felhasznalo_id);
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        oci_free_statement($stid);
        return $row ? $row['BOOKING_COUNT'] : 0;
    }

    public function getBookingById($id) {
        $stid = oci_parse($this->conn, "SELECT * FROM Foglalas WHERE foglalas_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        oci_execute($stid);
        $booking = oci_fetch_assoc($stid);
        oci_free_statement($stid);
        return $booking;
    }

    public function getAllUsers() {
        $stid = oci_parse($this->conn, "SELECT felhasznalo_id, nev FROM Felhasznalo");
        oci_execute($stid);
        $users = [];
        while ($row = oci_fetch_assoc($stid)) {
            $users[] = $row;
        }
        oci_free_statement($stid);
        return $users;
    }

    public function createBooking($felhasznalo_id, $datum, $statusz) {
        $stid = oci_parse($this->conn, "SELECT NVL(MAX(foglalas_id), 0) + 1 AS next_id FROM Foglalas");
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        $nextId = $row['NEXT_ID'];
        oci_free_statement($stid);

        $stid = oci_parse($this->conn, "INSERT INTO Foglalas (foglalas_id, felhasznalo_id, datum, statusz) 
                                        VALUES (:id, :felhasznalo_id, TO_DATE(:datum, 'YYYY-MM-DD'), :statusz)");
        oci_bind_by_name($stid, ':id', $nextId);
        oci_bind_by_name($stid, ':felhasznalo_id', $felhasznalo_id);
        oci_bind_by_name($stid, ':datum', $datum);
        oci_bind_by_name($stid, ':statusz', $statusz);
        return oci_execute($stid);
    }

    public function updateBooking($id, $felhasznalo_id, $datum, $statusz) {
        $stid = oci_parse($this->conn, "UPDATE Foglalas 
                                        SET felhasznalo_id = :felhasznalo_id, 
                                            datum = TO_DATE(:datum, 'YYYY-MM-DD'), 
                                            statusz = :statusz 
                                        WHERE foglalas_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        oci_bind_by_name($stid, ':felhasznalo_id', $felhasznalo_id);
        oci_bind_by_name($stid, ':datum', $datum);
        oci_bind_by_name($stid, ':statusz', $statusz);
        return oci_execute($stid);
    }

    public function deleteBooking($id) {
        $stid = oci_parse($this->conn, "DELETE FROM Foglalas WHERE foglalas_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        return oci_execute($stid);
    }
}
?>