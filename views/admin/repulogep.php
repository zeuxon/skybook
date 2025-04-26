<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repülőgép kezelése</title>
</head>
<body>
    <a href="../../index.php">Vissza</a>
    <h1>Repülőgép kezelése</h1>
    <form method="POST" action="../../controllers/admin/RepulogepController.php">
        <label for="repulogep_id">Repülőgép ID (csak módosításhoz/törléshez):</label>
        <input type="number" id="repulogep_id" name="repulogep_id"><br>
        <label for="legitarsasag_name">Légitársaság:</label>
        <select id="legitarsasag_name" name="legitarsasag_name" required>
            <?php
            $stid = oci_parse($conn, "SELECT nev FROM Legitarsasag");
            oci_execute($stid);
            while ($row = oci_fetch_assoc($stid)) {
                echo "<option value=\"" . htmlspecialchars($row['NEV']) . "\">" . htmlspecialchars($row['NEV']) . "</option>";
            }
            oci_free_statement($stid);
            ?>
        </select><br>
        <label for="kapacitas">Kapacitás:</label>
        <input type="number" id="kapacitas" name="kapacitas" required><br>
        <label for="tipus">Típus:</label>
        <input type="text" id="tipus" name="tipus" required><br>
        <button type="submit" name="action" value="add">Hozzáadás</button>
        <button type="submit" name="action" value="edit">Módosítás</button>
        <button type="submit" name="action" value="delete">Törlés</button>
    </form>

    <h1>Repülőgépek</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Légitársaság</th>
                <th>Kapacitás</th>
                <th>Típus</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($aircraft as $plane): ?>
                <tr>
                    <td><?= htmlspecialchars($plane['REPULOGEP_ID']) ?></td>
                    <td><?= htmlspecialchars($plane['LEGITARSASAG_NEV']) ?></td>
                    <td><?= htmlspecialchars($plane['KAPACITAS']) ?></td>
                    <td><?= htmlspecialchars($plane['TIPUS']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>