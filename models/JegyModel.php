<?php
require '../../config/connection.php';

class JegyModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllTickets() {
        $query = "SELECT j.jegy_id, 
                         TO_CHAR(f.datum, 'YYYY.MM.DD') AS foglalas_datum, 
                         f.statusz AS foglalas_statusz, 
                         fel.nev AS felhasznalo_nev, 
                         TO_CHAR(r.indulasi_ido, 'YYYY.MM.DD HH24:MI') AS jarat_indulasi_ido, 
                         TO_CHAR(r.erkezesi_ido, 'YYYY.MM.DD HH24:MI') AS jarat_erkezesi_ido, 
                         rg.tipus AS repulogep_tipus, 
                         lg.nev AS legitarsasag_nev, 
                         lg.szekhely AS legitarsasag_szekhely, 
                         ir.nev AS indulasi_repuloter_nev, 
                         er.nev AS erkezesi_repuloter_nev, 
                         jk.nev AS jegykategoria_nev, 
                         j.ar
                  FROM Jegy j
                  JOIN Foglalas f ON j.foglalas_id = f.foglalas_id
                  JOIN Felhasznalo fel ON f.felhasznalo_id = fel.felhasznalo_id
                  JOIN Repulojarat r ON j.jarat_id = r.jaratid
                  JOIN Repulogep rg ON r.repulogep_id = rg.repulogep_id
                  JOIN Legitarsasag lg ON rg.legitarsasag_id = lg.legitarsasag_id
                  JOIN Ut u ON r.ut_id = u.ut_id
                  JOIN Repuloter ir ON u.indulasi_repuloter_id = ir.repuloter_id
                  JOIN Repuloter er ON u.erkezesi_repuloter_id = er.repuloter_id
                  JOIN Jegykategoria jk ON j.jegykategoria_id = jk.jegykategoria_id
                  ORDER BY j.jegy_id ASC";
        $stid = oci_parse($this->conn, $query);
    
        if (!oci_execute($stid)) {
            $e = oci_error($stid);
            echo "SQL Error: " . $e['message'];
            return [];
        }
    
        $tickets = [];
        while ($row = oci_fetch_assoc($stid)) {
            $tickets[] = $row;
        }
        oci_free_statement($stid);
        return $tickets;
    }

    public function getTicketById($id) {
        $stid = oci_parse($this->conn, "SELECT * FROM Jegy WHERE jegy_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        oci_execute($stid);
        $ticket = oci_fetch_assoc($stid);
        oci_free_statement($stid);
        return $ticket;
    }

    public function getAllBookingsForDropdown() {
        $query = "SELECT f.foglalas_id, fel.nev AS felhasznalo_nev, f.datum, f.statusz
                  FROM Foglalas f
                  JOIN Felhasznalo fel ON f.felhasznalo_id = fel.felhasznalo_id";
        $stid = oci_parse($this->conn, $query);
        oci_execute($stid);
        $bookings = [];
        while ($row = oci_fetch_assoc($stid)) {
            $bookings[] = $row;
        }
        oci_free_statement($stid);
        return $bookings;
    }

    public function getAllFlightsForDropdown() {
        $query = "SELECT r.jaratid, 
                         r.indulasi_ido, 
                         r.erkezesi_ido, 
                         rg.tipus AS repulogep_tipus, 
                         ir.nev AS indulasi_repuloter_nev, 
                         er.nev AS erkezesi_repuloter_nev
                  FROM Repulojarat r
                  JOIN Repulogep rg ON r.repulogep_id = rg.repulogep_id
                  JOIN Ut u ON r.ut_id = u.ut_id
                  JOIN Repuloter ir ON u.indulasi_repuloter_id = ir.repuloter_id
                  JOIN Repuloter er ON u.erkezesi_repuloter_id = er.repuloter_id";
        $stid = oci_parse($this->conn, $query);
        oci_execute($stid);
        $flights = [];
        while ($row = oci_fetch_assoc($stid)) {
            $flights[] = $row;
        }
        oci_free_statement($stid);
        return $flights;
    }

    public function getAllJegykategoriaForDropdown() {
        $query = "SELECT jegykategoria_id, nev FROM Jegykategoria";
        $stid = oci_parse($this->conn, $query);
        oci_execute($stid);
        $categories = [];
        while ($row = oci_fetch_assoc($stid)) {
            $categories[] = $row;
        }
        oci_free_statement($stid);
        return $categories;
    }

    public function getAllLegitarsasagForDropdown() {
        $query = "SELECT legitarsasag_id, nev FROM Legitarsasag";
        $stid = oci_parse($this->conn, $query);
        oci_execute($stid);
        $legitarsasagok = [];
        while ($row = oci_fetch_assoc($stid)) {
            $legitarsasagok[] = $row;
        }
        oci_free_statement($stid);
        return $legitarsasagok;
    }

    public function getBookingById($foglalas_id) {
        $query = "SELECT TO_CHAR(datum, 'YYYY.MM.DD') AS datum FROM Foglalas WHERE foglalas_id = :foglalas_id";
        $stid = oci_parse($this->conn, $query);
        oci_bind_by_name($stid, ':foglalas_id', $foglalas_id);
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        oci_free_statement($stid);
        return $row;
    }

    public function createTicket($foglalas_id, $jarat_id, $jegykategoria_id, $ar) {
        // Get the next ID for the ticket
        $stid = oci_parse($this->conn, "SELECT NVL(MAX(jegy_id), 0) + 1 AS next_id FROM Jegy");
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        $nextId = $row['NEXT_ID'];
        oci_free_statement($stid);
    
        // Insert the new ticket
        $stid = oci_parse($this->conn, "INSERT INTO Jegy (jegy_id, foglalas_id, jarat_id, jegykategoria_id, ar) 
                                        VALUES (:id, :foglalas_id, :jarat_id, :jegykategoria_id, :ar)");
        oci_bind_by_name($stid, ':id', $nextId);
        oci_bind_by_name($stid, ':foglalas_id', $foglalas_id);
        oci_bind_by_name($stid, ':jarat_id', $jarat_id);
        oci_bind_by_name($stid, ':jegykategoria_id', $jegykategoria_id);
        oci_bind_by_name($stid, ':ar', $ar);
    
        return oci_execute($stid);
    }

    public function updateTicket($id, $foglalas_id, $jarat_id, $jegykategoria_id, $ar) {
        $stid = oci_parse($this->conn, "UPDATE Jegy 
                                        SET foglalas_id = :foglalas_id, 
                                            jarat_id = :jarat_id, 
                                            jegykategoria_id = :jegykategoria_id, 
                                            ar = :ar 
                                        WHERE jegy_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        oci_bind_by_name($stid, ':foglalas_id', $foglalas_id);
        oci_bind_by_name($stid, ':jarat_id', $jarat_id);
        oci_bind_by_name($stid, ':jegykategoria_id', $jegykategoria_id);
        oci_bind_by_name($stid, ':ar', $ar);
    
        return oci_execute($stid);
    }

    public function deleteTicket($id) {
        $stid = oci_parse($this->conn, "DELETE FROM Jegy WHERE jegy_id = :id");
        oci_bind_by_name($stid, ':id', $id);
        return oci_execute($stid);
    }
}
?>