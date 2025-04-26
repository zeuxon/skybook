<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repülőjárat kezelése</title>
</head>
<body>
    <a href="../../index.php">Vissza</a>
    <h1>Repülőjárat kezelése</h1>
    <form method="POST" action="../../controllers/admin/RepulojaratController.php">
        <label for="jaratid">Járat ID (csak módosításhoz/törléshez):</label>
        <input type="number" id="jaratid" name="jaratid"><br>
        <label for="repulogep_type">Repülőgép Típus:</label>
        <select id="repulogep_type" name="repulogep_type" required>
            <?php
            $stid = oci_parse($conn, "SELECT tipus FROM Repulogep");
            oci_execute($stid);
            while ($row = oci_fetch_assoc($stid)) {
                echo "<option value=\"" . htmlspecialchars($row['TIPUS']) . "\">" . htmlspecialchars($row['TIPUS']) . "</option>";
            }
            oci_free_statement($stid);
            ?>
        </select><br>
        <label for="route">Útvonal:</label>
        <select id="route" name="route" required>
            <?php
            $stid = oci_parse($conn, "SELECT u.ut_id, ir.nev AS indulasi_repuloter, er.nev AS erkezesi_repuloter 
                                      FROM Ut u
                                      JOIN Repuloter ir ON u.indulasi_repuloter_id = ir.repuloter_id
                                      JOIN Repuloter er ON u.erkezesi_repuloter_id = er.repuloter_id");
            oci_execute($stid);
            while ($row = oci_fetch_assoc($stid)) {
                $route = htmlspecialchars($row['INDULASI_REPULOTER']) . " - " . htmlspecialchars($row['ERKEZESI_REPULOTER']);
                echo "<option value=\"" . htmlspecialchars($row['UT_ID']) . "\">" . $route . "</option>";
            }
            oci_free_statement($stid);
            ?>
        </select><br>
        <label for="indulasi_ido">Indulási idő:</label>
        <input type="datetime-local" id="indulasi_ido" name="indulasi_ido" required><br>
        <label for="erkezesi_ido">Érkezési idő:</label>
        <input type="datetime-local" id="erkezesi_ido" name="erkezesi_ido" required><br>
        <button type="submit" name="action" value="add">Hozzáadás</button>
        <button type="submit" name="action" value="edit">Módosítás</button>
        <button type="submit" name="action" value="delete">Törlés</button>
    </form>

    <h1>Repülőjáratok</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Repülőgép Típus</th>
                <th>Indulási Repülőtér</th>
                <th>Érkezési Repülőtér</th>
                <th>Indulási Idő</th>
                <th>Érkezési Idő</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($flights as $flight): ?>
                <tr>
                    <td><?= htmlspecialchars($flight['JARATID']) ?></td>
                    <td><?= htmlspecialchars($flight['REPULOGEP_TIPUS']) ?></td>
                    <td><?= htmlspecialchars($flight['INDULASI_REPULOTER_NEV']) ?></td>
                    <td><?= htmlspecialchars($flight['ERKEZESI_REPULOTER_NEV']) ?></td>
                    <td>
                        <?php 
                        $indulasiIdo = new DateTime($flight['INDULASI_IDO']);
                        echo htmlspecialchars($indulasiIdo->format('Y.m.d H:i'));
                        ?>
                    </td>
                    <td>
                        <?php 
                        $erkezesiIdo = new DateTime($flight['ERKEZESI_IDO']);
                        echo htmlspecialchars($erkezesiIdo->format('Y.m.d H:i'));
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>