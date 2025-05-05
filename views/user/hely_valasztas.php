<!doctype html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/hely_valasztas.css">
    <title>Document</title>
</head>
<body>
        <?php
        require '../../config/connection.php';
        session_start();

        if (isset($_SESSION['ticket_id'])) {
            $jarat_id = $_SESSION['ticket_id'];
            $sql = 'BEGIN :cursor := LefoglaltHelyek(:jarat_id); END;';
            $stmt = oci_parse($conn, $sql);


            $cursor = oci_new_cursor($conn);
            oci_bind_by_name($stmt, ':cursor', $cursor, -1, OCI_B_CURSOR);


            oci_bind_by_name($stmt, ':jarat_id', $jarat_id);

            oci_execute($stmt);
            oci_execute($cursor);

            global $seat_positions;
            $seat_positions = [];

            while ($seat_position = oci_fetch_assoc($cursor)) {
                $seat_positions[] = $seat_position;
                echo "Sor: " . $seat_position['SOR'] . ", Oszlop: " . $seat_position['OSZLOP'] . "<br>";
            }

            oci_free_statement($stmt);
            oci_free_statement($cursor);
            oci_close($conn);

        }
        ?>


    <div class="seat_grid">
        <?php
        if (isset($_POST['index'])) {
            $selected = $_POST['index'];
        }
        $selected = '0_0';
        $posNum = 0;

        for ($i = 0; $i < 7; $i++) {
            for ($j = 0; $j < 6; $j++) {
                if ($i == 0){
                    if ($j == 0 || $j == 5){
                        echo "<div> </div>";
                    }else{
                        echo "<span class='center'>$j</span>";
                    }
                } elseif ($j == 0 || $j == 5){
                    echo "<span class='center'>$i</span>";
                }
                else{
                    echo '<form method="POST">';
                    $index = $i .'_'. $j;
                    echo '<input type="hidden" name="index" value="'.  $index  . '">';
                    if ($seat_positions != null && $posNum < count($seat_positions)){
                        if ($index == $seat_positions[$posNum]['SOR'].  '_'. $seat_positions[$posNum]['OSZLOP']){
                            $posNum++;
                            echo '<input type="submit" class="szek_grid-item unavailable" value="'. $j . '">';
                        }
                        elseif ($index == $selected){
                            echo '<input type="submit" class="szek_grid-item akt" value="'. $j . '">';
                        }
                        else{
                            echo '<input type="submit" class="szek_grid-item available" value="'. $j . '">';
                        }
                    }
                    else{
                        if ($index == $selected){
                            echo '<input type="submit" class="szek_grid-item akt" value="'. $j . '">';
                        }else{
                            echo '<input type="submit" class="szek_grid-item available" value="'. $j . '">';
                        }
                    }
                    echo '</form>';
                }
            }
        }


        ?>
    </div>


</body>
</html>

