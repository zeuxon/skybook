<?php
require '../config/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    $stid = oci_parse($conn, "SELECT jelszo FROM Felhasznalo WHERE felhasznalonev = :username");
    oci_bind_by_name($stid, ':username', $username);
    oci_execute($stid);

    $row = oci_fetch_assoc($stid);
    if ($row && password_verify($password, $row['JELSZO'])) {
        $_SESSION['username'] = $username;
        header("Location: ../index.php");
        exit();
    } else {
        echo "Invalid username or password.";
    }

    oci_free_statement($stid);
}

oci_close($conn);
?>
