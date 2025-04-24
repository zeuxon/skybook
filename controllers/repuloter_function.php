<?php
require 'admin_check.php';
require 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['repuloter_id'] ?? null;
    $nev = htmlspecialchars($_POST['nev']);
    $varos = htmlspecialchars($_POST['varos']);
    $orszag = htmlspecialchars($_POST['orszag']);

    if ($action === 'add') {
        $stid = oci_parse($conn, "INSERT INTO Repuloter (repuloter_id, nev, varos, orszag) VALUES (REPULOTER_SEQ.NEXTVAL, :nev, :varos, :orszag)");
    } elseif ($action === 'edit' && $id) {
        $stid = oci_parse($conn, "UPDATE Repuloter SET nev = :nev, varos = :varos, orszag = :orszag WHERE repuloter_id = :id");
        oci_bind_by_name($stid, ':id', $id);
    } elseif ($action === 'delete' && $id) {
        $stid = oci_parse($conn, "DELETE FROM Repuloter WHERE repuloter_id = :id");
        oci_bind_by_name($stid, ':id', $id);
    }

    if ($action !== 'delete') {
        oci_bind_by_name($stid, ':nev', $nev);
        oci_bind_by_name($stid, ':varos', $varos);
        oci_bind_by_name($stid, ':orszag', $orszag);
    }

    if (oci_execute($stid)) {
        echo "Operation successful.";
    } else {
        $e = oci_error($stid);
        echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
    }

    oci_free_statement($stid);
}
?>