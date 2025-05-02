<?php
require '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $telephone = htmlspecialchars($_POST['telephone']);
    $postalCode = htmlspecialchars($_POST['postal_code']);
    $city = htmlspecialchars($_POST['city']);

    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $query = oci_parse($conn, "SELECT NVL(MAX(felhasznalo_id), 0) + 1 AS new_id FROM Felhasznalo");
        oci_execute($query);
        $row = oci_fetch_assoc($query);
        $felhasznaloId = $row['NEW_ID'];
        oci_free_statement($query);

        $stid = oci_parse($conn, "INSERT INTO Felhasznalo (felhasznalo_id, nev, admin, jelszo, felhasznalonev, email, telefonszam, iranyitoszam, telepules) VALUES (:id, :name, 0, :password, :username, :email, :telephone, :postalCode, :city)");
        oci_bind_by_name($stid, ':id', $felhasznaloId);
        oci_bind_by_name($stid, ':name', $username);
        oci_bind_by_name($stid, ':password', $hashedPassword);
        oci_bind_by_name($stid, ':username', $username);
        oci_bind_by_name($stid, ':email', $email);
        oci_bind_by_name($stid, ':telephone', $telephone);
        oci_bind_by_name($stid, ':postalCode', $postalCode);
        oci_bind_by_name($stid, ':city', $city);

        if (oci_execute($stid)) {
            header("Location: ../index.php?success=register");
        } else {
            $e = oci_error($stid);
            echo "Error: " . htmlentities($e['message'], ENT_QUOTES);
        }

        oci_free_statement($stid);
    }
}

oci_close($conn);
?>