<?php
require '../../config/connection.php';
require_once '../../models/FoglalasModel.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../views/login.html");
    exit();
}

$username = $_SESSION['username'];

$stid = oci_parse($conn, "SELECT felhasznalo_id FROM Felhasznalo WHERE felhasznalonev = :username");
oci_bind_by_name($stid, ':username', $username);
oci_execute($stid);
$row = oci_fetch_assoc($stid);
$felhasznalo_id = $row ? $row['FELHASZNALO_ID'] : null;
oci_free_statement($stid);

if (!$felhasznalo_id) {
    echo "Error: User not found.";
    oci_close($conn);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'book') {
        $_SESSION['jarat_id'] = $_POST['jarat_id'];
        $_SESSION['jegy_id'] = $_POST['jegy_id'];
        oci_close($conn);
        header("Location: ../../views/user/hely_valasztas.php");
        exit();
    }

    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $foglalas_id = $_POST['foglalas_id'];

        $stid = oci_parse($conn, "SELECT jegy_id FROM Foglalas WHERE foglalas_id = :foglalas_id AND felhasznalo_id = :felhasznalo_id");
        oci_bind_by_name($stid, ':foglalas_id', $foglalas_id);
        oci_bind_by_name($stid, ':felhasznalo_id', $felhasznalo_id);
        oci_execute($stid);
        $row = oci_fetch_assoc($stid);
        $ticket_id = $row ? $row['JEGY_ID'] : null;
        oci_free_statement($stid);

        if ($ticket_id) {
            $stid = oci_parse($conn, "UPDATE Jegy SET foglalva = 0 WHERE jegy_id = :ticket_id");
            oci_bind_by_name($stid, ':ticket_id', $ticket_id);
            oci_execute($stid);
            oci_free_statement($stid);
        }

        $stid = oci_parse($conn, "DELETE FROM Foglalas WHERE foglalas_id = :foglalas_id AND felhasznalo_id = :felhasznalo_id");
        oci_bind_by_name($stid, ':foglalas_id', $foglalas_id);
        oci_bind_by_name($stid, ':felhasznalo_id', $felhasznalo_id);

        if (oci_execute($stid)) {
            oci_free_statement($stid);
            oci_close($conn);
            header("Location: ../../controllers/user/BookingController.php?success=2");
            exit();
        } else {
            $e = oci_error($stid);
            oci_free_statement($stid);
            oci_close($conn);
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
            exit();
        }
    }
}

$stid = oci_parse($conn, "
    SELECT f.foglalas_id, 
           TO_CHAR(f.datum, 'YYYY.MM.DD') AS datum, 
           f.statusz, 
           ir.nev AS indulasi_repuloter, 
           er.nev AS erkezesi_repuloter, 
           jk.nev AS jegykategoria, 
           j.ar AS jegy_ar, 
           lg.nev AS legitarsasag_nev,
           rg.etkezes AS repulogep_etkezes,
           b.nev AS biztositas_nev,
           b.ar AS biztositas_ar
    FROM Foglalas f
    JOIN Jegy j ON f.jegy_id = j.jegy_id
    JOIN Repulojarat r ON j.jarat_id = r.jaratid
    JOIN Ut u ON r.ut_id = u.ut_id
    JOIN Repuloter ir ON u.indulasi_repuloter_id = ir.repuloter_id
    JOIN Repuloter er ON u.erkezesi_repuloter_id = er.repuloter_id
    JOIN Jegykategoria jk ON j.jegykategoria_id = jk.jegykategoria_id
    JOIN Repulogep rg ON r.repulogep_id = rg.repulogep_id
    JOIN Legitarsasag lg ON rg.legitarsasag_id = lg.legitarsasag_id
    LEFT JOIN Biztositas b ON f.biztositas_id = b.biztositas_id
    WHERE f.felhasznalo_id = :felhasznalo_id
    ORDER BY f.foglalas_id
");
oci_bind_by_name($stid, ':felhasznalo_id', $felhasznalo_id);
oci_execute($stid);

$bookings = [];
while ($row = oci_fetch_assoc($stid)) {
    $bookings[] = $row;
}
oci_free_statement($stid);

$foglalasModel = new FoglalasModel($conn);
$bookingCount = $foglalasModel->getBookingCountByUser($felhasznalo_id);

oci_close($conn);

include '../../views/user/foglalas_user.php';
?>