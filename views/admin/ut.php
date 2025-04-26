<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Útvonalak kezelése</title>
</head>
<body>
    <a href="../../index.php">Vissza</a>
    <h1>Útvonalak kezelése</h1>
    <form method="POST" action="../../controllers/admin/UtController.php">
        <label for="ut_id">Út ID (csak módosításhoz/törléshez):</label>
        <input type="number" id="ut_id" name="ut_id"><br>

        <label for="indulasi_repuloter_id">Indulási repülőtér:</label>
        <select id="indulasi_repuloter_id" name="indulasi_repuloter_id" required>
            <option value="">Válasszon indulási repülőteret</option>
            <?php foreach ($airports as $airport): ?>
                <option value="<?= htmlspecialchars($airport['REPULOTER_ID']) ?>">
                    <?= htmlspecialchars($airport['NEV']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="erkezesi_repuloter_id">Érkezési repülőtér:</label>
        <select id="erkezesi_repuloter_id" name="erkezesi_repuloter_id" required>
            <option value="">Válasszon érkezési repülőteret</option>
            <?php foreach ($airports as $airport): ?>
                <option value="<?= htmlspecialchars($airport['REPULOTER_ID']) ?>">
                    <?= htmlspecialchars($airport['NEV']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <button type="submit" name="action" value="add">Hozzáadás</button>
        <button type="submit" name="action" value="edit">Módosítás</button>
        <button type="submit" name="action" value="delete">Törlés</button>
    </form>

    <h1>Útvonalak</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Indulási repülőtér</th>
                <th>Érkezési repülőtér</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($routes as $route): ?>
                <tr>
                    <td><?= htmlspecialchars($route['UT_ID']) ?></td>
                    <td><?= htmlspecialchars($route['INDULASI_REPULOTER_NEV']) ?></td>
                    <td><?= htmlspecialchars($route['ERKEZESI_REPULOTER_NEV']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>