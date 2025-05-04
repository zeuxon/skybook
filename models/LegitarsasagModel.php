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

    public function getYearlyStats() {
        $query = "SELECT
                      l.nev AS legitarsasag_nev,
                      EXTRACT(YEAR FROM f.datum) AS ev,
                      COUNT(f.foglalas_id) AS foglalasok_szama
                  FROM Foglalas f
                  JOIN Jegy j ON f.jegy_id = j.jegy_id
                  JOIN Repulojarat r ON j.jarat_id = r.jaratid
                  JOIN Repulogep g ON r.repulogep_id = g.repulogep_id
                  JOIN Legitarsasag l ON g.legitarsasag_id = l.legitarsasag_id
                  GROUP BY l.nev, EXTRACT(YEAR FROM f.datum)
                  ORDER BY l.nev, ev";
        $stid = oci_parse($this->conn, $query);
        oci_execute($stid);
        $stats = [];
        while ($row = oci_fetch_assoc($stid)) {
            $stats[] = $row;
        }
        oci_free_statement($stid);
        return $stats;
    }

    public function getMonthlyStats() {
        $query = "SELECT
                      l.nev AS legitarsasag_nev,
                      TO_CHAR(f.datum, 'YYYY-MM') AS honap,
                      COUNT(f.foglalas_id) AS foglalasok_szama
                  FROM Foglalas f
                  JOIN Jegy j ON f.jegy_id = j.jegy_id
                  JOIN Repulojarat r ON j.jarat_id = r.jaratid
                  JOIN Repulogep g ON r.repulogep_id = g.repulogep_id
                  JOIN Legitarsasag l ON g.legitarsasag_id = l.legitarsasag_id
                  GROUP BY l.nev, TO_CHAR(f.datum, 'YYYY-MM')
                  ORDER BY l.nev, honap";
        $stid = oci_parse($this->conn, $query);
        oci_execute($stid);
        $stats = [];
        while ($row = oci_fetch_assoc($stid)) {
            $stats[] = $row;
        }
        oci_free_statement($stid);
        return $stats;
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