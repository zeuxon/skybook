<?php
require dirname(__DIR__) . '/config/connection.php';
function checkMessage($username)
{
    global $conn;
    $stid = oci_parse($conn, "SELECT felhasznalo_id FROM Felhasznalo WHERE nev =: username");
    oci_bind_by_name($stid, ':username', $username);
    oci_execute($stid);
    $row = oci_fetch_assoc($stid);
    oci_free_statement($stid);

    $user_id = $row['FELHASZNALO_ID'];

    $query = "
        SELECT jv.jaratid, jv.jarat_valtozas
        FROM Foglalas f
        JOIN Jegy j ON f.jegy_id = j.jegy_id
        JOIN Jarat_valtozas_log jv ON j.jarat_id = jv.jaratid
        WHERE f.felhasznalo_id = :userid
        ";

        $stid = oci_parse($conn, $query);
        oci_bind_by_name($stid, ":userid", $user_id);
        oci_execute($stid);

        $found = false;
        while ($row = oci_fetch_assoc($stid)) {
            echo "Változás: " . $row['JARAT_VALTOZAS'] . "<br><br>";
            $found = true;
        }
        oci_free_statement($stid);

        if (!$found) {
            echo "Nincs járatváltozás a lefoglalt jegyekhez.";
        }
}
?>
