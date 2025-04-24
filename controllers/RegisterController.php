<?php
require '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stid = oci_parse($conn, "INSERT INTO Felhasznalo (felhasznalo_id, nev, admin, jelszo, felhasznalonev, email) VALUES (FELHASZNALO_SEQ.NEXTVAL, :name, 0, :password, :username, :email)");
        oci_bind_by_name($stid, ':name', $username);
        oci_bind_by_name($stid, ':password', $hashedPassword);
        oci_bind_by_name($stid, ':username', $username);
        oci_bind_by_name($stid, ':email', $email);

        if (oci_execute($stid)) {
            header("Location: ../index.php");
        } else {
            $e = oci_error($stid);
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
        }

        oci_free_statement($stid);
    }
}

oci_close($conn);
?>
