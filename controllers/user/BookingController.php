<?php
require '../../config/connection.php';
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
$felhasznalo_id = $row['FELHASZNALO_ID'];
oci_free_statement($stid);

if (!$felhasznalo_id) {
    echo "Error: User not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'book') {
    $ticket_id = $_POST['ticket_id'];
    session_start();
    $_SESSION['ticket_id'] = $ticket_id;
    header("Location: ../../views/user/hely_valasztas.php");


    $stid = oci_parse($conn, "SELECT foglalva FROM Jegy WHERE jegy_id = :ticket_id");
    oci_bind_by_name($stid, ':ticket_id', $ticket_id);
    oci_execute($stid);
    $row = oci_fetch_assoc($stid);
    if (!$row || $row['FOGLALVA'] == 1) {
        echo "Error: Ticket is already booked or does not exist.";
        exit();
    }
    oci_free_statement($stid);

    $stid = oci_parse($conn, "SELECT NVL(MAX(foglalas_id), 0) + 1 AS next_id FROM Foglalas");
    oci_execute($stid);
    $row = oci_fetch_assoc($stid);
    $next_foglalas_id = $row['NEXT_ID'];
    oci_free_statement($stid);

    $datum = date('Y-m-d');
    $statusz = 'Fizetetlen';


    list($sor, $oszlop) = explode('_', $_POST['index']);

    echo $sor;
    echo $oszlop;

    $stid = oci_parse($conn, "INSERT INTO Foglalas (foglalas_id, felhasznalo_id, jegy_id, datum, statusz)
                              VALUES (:foglalas_id, :felhasznalo_id, :ticket_id, TO_DATE(:datum, 'YYYY-MM-DD'), :statusz)");
    oci_bind_by_name($stid, ':foglalas_id', $next_foglalas_id);
    oci_bind_by_name($stid, ':felhasznalo_id', $felhasznalo_id);
    oci_bind_by_name($stid, ':ticket_id', $ticket_id);
    oci_bind_by_name($stid, ':datum', $datum);
    oci_bind_by_name($stid, ':statusz', $statusz);

    if (oci_execute($stid)) {
        $stid = oci_parse($conn, "UPDATE Jegy SET foglalva = 1 WHERE jegy_id = :ticket_id");
        oci_bind_by_name($stid, ':ticket_id', $ticket_id);
        oci_execute($stid);


        header("Location: ../../controllers/user/RepulojaratUserController.php?success=1");
        exit();
    } else {
        $e = oci_error($stid);
        echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
    }

    oci_free_statement($stid);


}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $foglalas_id = $_POST['foglalas_id'];

    $stid = oci_parse($conn, "SELECT jegy_id FROM Foglalas WHERE foglalas_id = :foglalas_id AND felhasznalo_id = :felhasznalo_id");
    oci_bind_by_name($stid, ':foglalas_id', $foglalas_id);
    oci_bind_by_name($stid, ':felhasznalo_id', $felhasznalo_id);
    oci_execute($stid);
    $row = oci_fetch_assoc($stid);
    $ticket_id = $row['JEGY_ID'];
    oci_free_statement($stid);

    if ($ticket_id) {
        $stid = oci_parse($conn, "UPDATE Jegy SET foglalva = 0 WHERE jegy_id = :ticket_id");
        oci_bind_by_name($stid, ':ticket_id', $ticket_id);
        oci_execute($stid);
    }

    $stid = oci_parse($conn, "DELETE FROM Foglalas WHERE foglalas_id = :foglalas_id AND felhasznalo_id = :felhasznalo_id");
    oci_bind_by_name($stid, ':foglalas_id', $foglalas_id);
    oci_bind_by_name($stid, ':felhasznalo_id', $felhasznalo_id);

    if (oci_execute($stid)) {
        header("Location: ../../controllers/user/BookingController.php?success=2");
        exit();
    } else {
        $e = oci_error($stid);
        echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
    }

    oci_free_statement($stid);
}

$stid = oci_parse($conn, "
    SELECT f.foglalas_id, 
           TO_CHAR(f.datum, 'YYYY.MM.DD') AS datum, 
           f.statusz, 
           ir.nev AS indulasi_repuloter, 
           er.nev AS erkezesi_repuloter, 
           jk.nev AS jegykategoria, 
           j.ar AS jegy_ar, 
           lg.nev AS legitarsasag_nev
    FROM Foglalas f
    JOIN Jegy j ON f.jegy_id = j.jegy_id
    JOIN Repulojarat r ON j.jarat_id = r.jaratid
    JOIN Ut u ON r.ut_id = u.ut_id
    JOIN Repuloter ir ON u.indulasi_repuloter_id = ir.repuloter_id
    JOIN Repuloter er ON u.erkezesi_repuloter_id = er.repuloter_id
    JOIN Jegykategoria jk ON j.jegykategoria_id = jk.jegykategoria_id
    JOIN Repulogep rg ON r.repulogep_id = rg.repulogep_id
    JOIN Legitarsasag lg ON rg.legitarsasag_id = lg.legitarsasag_id
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
oci_close($conn);

include '../../views/user/foglalas_user.php';
?>