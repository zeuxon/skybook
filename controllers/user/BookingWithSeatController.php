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
$felhasznalo_id = $row['FELHASZNALO_ID'] ?? null;
oci_free_statement($stid);

if (!$felhasznalo_id) {
    echo "Error: User not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jarat_id = $_POST['jarat_id'] ?? null;
    $index = $_POST['index'] ?? null;

    if (!$jarat_id || !$index) {
        echo "Error: Missing flight ID or seat index.";
        exit();
    }

    list($sor, $oszlop) = explode('_', $index);
    echo "Selected seat: Row $sor, Column $oszlop<br>";

    echo "Flight ID: $jarat_id<br>";

    $ticket_id = $_POST['jegy_id'];

    echo "Ticket ID: $ticket_id<br>";

    $stid = oci_parse($conn, "SELECT NVL(MAX(foglalas_id), 0) + 1 AS next_id FROM Foglalas");
    oci_execute($stid);
    $row = oci_fetch_assoc($stid);
    $next_foglalas_id = $row['NEXT_ID'];
    oci_free_statement($stid);

    $datum = date('Y-m-d');
    $statusz = 'Fizetetlen';

    $stid = oci_parse($conn, "INSERT INTO Foglalas (foglalas_id, felhasznalo_id, jegy_id, datum, statusz, sor, oszlop)
                              VALUES (:foglalas_id, :felhasznalo_id, :ticket_id, TO_DATE(:datum, 'YYYY-MM-DD'), :statusz, :sor, :oszlop)");
    oci_bind_by_name($stid, ':foglalas_id', $next_foglalas_id);
    oci_bind_by_name($stid, ':felhasznalo_id', $felhasznalo_id);
    oci_bind_by_name($stid, ':ticket_id', $ticket_id);
    oci_bind_by_name($stid, ':datum', $datum);
    oci_bind_by_name($stid, ':statusz', $statusz);
    oci_bind_by_name($stid, ':sor', $sor);
    oci_bind_by_name($stid, ':oszlop', $oszlop);

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