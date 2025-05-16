<!doctype html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/hely_valasztas.css">
    <title>Seat Selection</title>
    <script>
        function selectSeat(index) {
            const selectedSeatInput = document.getElementById('selectedSeat');
            selectedSeatInput.value = index;

            const seats = document.querySelectorAll('.szek_grid-item');
            seats.forEach(seat => seat.classList.remove('akt'));
            const selectedSeat = document.querySelector(`[data-index="${index}"]`);
            if (selectedSeat) {
                selectedSeat.classList.add('akt');
            }
        }
    </script>
</head>
<body>
    <?php
    require '../../config/connection.php';
    session_start();

    $biztositasok = [];


    if (isset($_SESSION['jarat_id'])) {
        // echo "Járat ID: " . $_SESSION['jarat_id'];
        $jarat_id = $_SESSION['jarat_id'];
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
            // echo "Sor: " . $seat_position['SOR'] . ", Oszlop: " . $seat_position['OSZLOP'] . "<br>";
        }

        $stid = oci_parse($conn, "SELECT biztositas_id, nev, ar FROM Biztositas");
        oci_execute($stid);
        while ($row = oci_fetch_assoc($stid)) {
            $biztositasok[] = $row;
        }
        oci_free_statement($stid);

        oci_free_statement($stmt);
        oci_free_statement($cursor);
        oci_close($conn);
    }
    ?>

    <a href="../../controllers/user/RepulojaratUserController.php">Vissza</a>

    <form method="POST" action="../../controllers/user/BookingWithSeatController.php">
        <input type="hidden" name="index" id="selectedSeat" value="">
        <input type="hidden" name="jarat_id" value="<?= htmlspecialchars($jarat_id) ?>">
        <input type="hidden" name="jegy_id" value="<?= htmlspecialchars($_SESSION['jegy_id']) ?>">
        <div class="seat_grid">
            <?php
            $posNum = 0;

            for ($i = 0; $i < 7; $i++) {
                for ($j = 0; $j < 6; $j++) {
                    if ($i == 0) {
                        if ($j == 0 || $j == 5) {
                            echo "<div> </div>";
                        } else {
                            echo "<span class='center'>$j</span>";
                        }
                    } elseif ($j == 0 || $j == 5) {
                        echo "<span class='center'>$i</span>";
                    } else {
                        $index = $i . '_' . $j;
                        $isUnavailable = false;

                        if ($seat_positions != null && $posNum < count($seat_positions)) {
                            if ($index == $seat_positions[$posNum]['SOR'] . '_' . $seat_positions[$posNum]['OSZLOP']) {
                                $isUnavailable = true;
                                $posNum++;
                            }
                        }

                        if ($isUnavailable) {
                            echo '<button type="button" class="szek_grid-item unavailable" disabled>' . $j . '</button>';
                        } else {
                            echo '<button type="button" class="szek_grid-item available" data-index="' . $index . '" onclick="selectSeat(\'' . $index . '\')">' . $j . '</button>';
                        }
                    }
                }
            }
            ?>
        </div>

        <div style="margin: 20px 0;">
            <label for="biztositas_id">Biztosítás (nem kötelező):</label>
            <select name="biztositas_id" id="biztositas_id">
                <option value="">Nincs</option>
                <?php foreach ($biztositasok as $bizt): ?>
                    <option value="<?= htmlspecialchars($bizt['BIZTOSITAS_ID']) ?>">
                        <?= htmlspecialchars($bizt['NEV']) ?> (<?= htmlspecialchars($bizt['AR']) ?> Ft)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="book-button">Book it!</button>
    </form>
</body>
</html>