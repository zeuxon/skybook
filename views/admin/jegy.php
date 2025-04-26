<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jegy kezelése</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <a href="../../index.php">Vissza</a>
    <h1>Jegy kezelése</h1>
    <form method="POST" action="../../controllers/admin/JegyController.php">
        <label for="jegy_id">Jegy ID (csak módosításhoz/törléshez):</label>
        <input type="number" id="jegy_id" name="jegy_id"><br>

        <label for="jarat_id">Járat:</label>
        <select id="jarat_id" name="jarat_id" required>
            <?php foreach ($flights as $flight): ?>
                <option value="<?= htmlspecialchars($flight['JARATID']) ?>">
                    <?= htmlspecialchars($flight['INDULASI_REPULOTER_NEV']) ?> - <?= htmlspecialchars($flight['ERKEZESI_REPULOTER_NEV']) ?>
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
        <input type="number" id="ar" name="ar" required><br>

        <button type="submit" name="action" value="add">Hozzáadás</button>
        <button type="submit" name="action" value="edit">Módosítás</button>
        <button type="submit" name="action" value="delete">Törlés</button>
    </form>

    <h1>Jegyek</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Járat</th>
                <th>Jegykategória</th>
                <th>Ár</th>
                <th>Foglalva</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?= htmlspecialchars($ticket['JEGY_ID']) ?></td>
                    <td><?= htmlspecialchars($ticket['INDULASI_REPULOTER_NEV']) ?> - <?= htmlspecialchars($ticket['ERKEZESI_REPULOTER_NEV']) ?></td>
                    <td><?= htmlspecialchars($ticket['JEGYKATEGORIA_NEV']) ?></td>
                    <td><?= htmlspecialchars($ticket['AR']) ?> Ft</td>
                    <td><?= $ticket['FOGLALVA'] == 1 ? 'Igen' : 'Nem' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>