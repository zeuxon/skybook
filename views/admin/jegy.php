<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jegy kezelése</title>
</head>
<body>
    <a href="../../index.php">Vissza</a>
    <h1>Jegy kezelése</h1>
    <form method="POST" action="../../controllers/admin/JegyController.php">
        <label for="jegy_id">Jegy ID (csak módosításhoz/törléshez):</label>
        <input type="number" id="jegy_id" name="jegy_id"><br>

        <label for="foglalas_id">Foglalás:</label>
        <select id="foglalas_id" name="foglalas_id" required>
            <?php foreach ($bookings as $booking): ?>
                <option value="<?= htmlspecialchars($booking['FOGLALAS_ID']) ?>">
                    <?= htmlspecialchars($booking['FELHASZNALO_NEV']) ?> - 
                    <?= htmlspecialchars($booking['DATUM']) ?> - 
                    <?= htmlspecialchars($booking['STATUSZ']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="jarat_id">Járat:</label>
        <select id="jarat_id" name="jarat_id" required>
            <?php foreach ($flights as $flight): ?>
                <option value="<?= htmlspecialchars($flight['JARATID']) ?>">
                    <?= htmlspecialchars($flight['INDULASI_IDO']) ?> - 
                    <?= htmlspecialchars($flight['ERKEZESI_IDO']) ?> - 
                    <?= htmlspecialchars($flight['REPULOGEP_TIPUS']) ?> - 
                    <?= htmlspecialchars($flight['INDULASI_REPULOTER_NEV']) ?> - 
                    <?= htmlspecialchars($flight['ERKEZESI_REPULOTER_NEV']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="jegykategoria_id">Jegykategória:</label>
        <select id="jegykategoria_id" name="jegykategoria_id" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?= htmlspecialchars($category['JEGYKATEGORIA_ID']) ?>">
                    <?= htmlspecialchars($category['NEV']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="ar">Ár:</label>
        <input type="number" id="ar" name="ar" value="" required><br>

        <label for="legitarsasag_id">Legitársaság:</label>
        <select id="legitarsasag_id" name="legitarsasag_id" required>
            <?php foreach ($legitarsasagok as $legitarsasag): ?>
                <option value="<?= htmlspecialchars($legitarsasag['LEGITARSASAG_ID']) ?>">
                    <?= htmlspecialchars($legitarsasag['NEV']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <button type="submit" name="action" value="add">Hozzáadás</button>
        <button type="submit" name="action" value="edit">Módosítás</button>
        <button type="submit" name="action" value="delete">Törlés</button>
    </form>

    <h1>Jegyek</h1>
    <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Foglalás Részletei</th>
            <th>Járat Indulási Idő</th>
            <th>Járat Érkezési Idő</th>
            <th>Repülőgép Típus</th>
            <th>Indulási Repülőtér</th>
            <th>Érkezési Repülőtér</th>
            <th>Jegykategória</th>
            <th>Ár</th>
            <th>Legitársaság</th>
            <th>Legitársaság Székhely</th>
            <th>Foglalás Dátum</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tickets as $ticket): ?>
            <tr>
                <td><?= htmlspecialchars($ticket['JEGY_ID']) ?></td>
                <td>
                    <?= htmlspecialchars($ticket['FELHASZNALO_NEV']) ?> - 
                    <?= htmlspecialchars($ticket['FOGLALAS_DATUM']) ?> - 
                    <?= htmlspecialchars($ticket['FOGLALAS_STATUSZ']) ?>
                </td>
                <td><?= htmlspecialchars($ticket['JARAT_INDULASI_IDO']) ?></td>
                <td><?= htmlspecialchars($ticket['JARAT_ERKEZESI_IDO']) ?></td>
                <td><?= htmlspecialchars($ticket['REPULOGEP_TIPUS']) ?></td>
                <td><?= htmlspecialchars($ticket['INDULASI_REPULOTER_NEV']) ?></td>
                <td><?= htmlspecialchars($ticket['ERKEZESI_REPULOTER_NEV']) ?></td>
                <td><?= htmlspecialchars($ticket['JEGYKATEGORIA_NEV']) ?></td>
                <td><?= htmlspecialchars($ticket['AR']) ?> Ft</td>
                <td><?= htmlspecialchars($ticket['LEGITARSASAG_NEV']) ?></td>
                <td><?= htmlspecialchars($ticket['LEGITARSASAG_SZEKHELY']) ?></td>
                <td><?= htmlspecialchars($ticket['FOGLALAS_DATUM']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
</body>
</html>