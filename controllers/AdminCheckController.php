<?php
require dirname(__DIR__) . '/config/connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isAdmin($username) {
    global $conn;
    $stid = oci_parse($conn, "SELECT admin FROM Felhasznalo WHERE felhasznalonev = :username");
    oci_bind_by_name($stid, ':username', $username);
    oci_execute($stid);
    $row = oci_fetch_assoc($stid);
    oci_free_statement($stid);
    return $row && $row['ADMIN'] == 1;
}
?>