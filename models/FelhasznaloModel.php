<?php
require '../../config/connection.php';

class FelhasznaloModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUserByUsername($username) {
        $stid = oci_parse($this->conn, "SELECT felhasznalonev, email, telefonszam, iranyitoszam, telepules 
                                        FROM Felhasznalo WHERE felhasznalonev = :username");
        oci_bind_by_name($stid, ':username', $username);
        oci_execute($stid);
        $user = oci_fetch_assoc($stid);
        oci_free_statement($stid);
        return $user;
    }

    public function updateUser($currentUsername, $newUsername, $email, $telephone, $postalCode, $city) {
        $stid = oci_parse($this->conn, "UPDATE Felhasznalo 
                                        SET felhasznalonev = :new_username, 
                                            email = :email, 
                                            telefonszam = :telephone, 
                                            iranyitoszam = :postal_code, 
                                            telepules = :city 
                                        WHERE felhasznalonev = :current_username");
        oci_bind_by_name($stid, ':new_username', $newUsername);
        oci_bind_by_name($stid, ':email', $email);
        oci_bind_by_name($stid, ':telephone', $telephone);
        oci_bind_by_name($stid, ':postal_code', $postalCode);
        oci_bind_by_name($stid, ':city', $city);
        oci_bind_by_name($stid, ':current_username', $currentUsername);

        return oci_execute($stid);
    }
}
?>