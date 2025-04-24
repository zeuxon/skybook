<?php
require '../config/connection.php';

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../views/login.html");
    exit();
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

if (!isAdmin($_SESSION['username'])) {
    die("Access denied. Admins only.");
}
?>
