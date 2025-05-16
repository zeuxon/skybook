<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repülőtér kezelése</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <a href="../../index.php">Vissza</a>
    <h1>Repülőtér kezelése</h1>
    <form method="POST" action="../../controllers/admin/RepuloterController.php">
        <label for="repuloter_id">Repülőtér ID (csak módosításhoz/törléshez):</label>
        <input type="number" id="repuloter_id" name="repuloter_id"><br>
        <label for="nev">Név:</label>
        <input type="text" id="nev" name="nev" required><br>
        <label for="varos">Város:</label>
        <input type="text" id="varos" name="varos" required><br>
        <label for="orszag">Ország:</label>
        <input type="text" id="orszag" name="orszag" required><br>
        <button type="submit" name="action" value="add">Hozzáadás</button>
        <button type="submit" name="action" value="edit">Módosítás</button>
        <button type="submit" name="action" value="delete">Törlés</button>
    </form>

    <h1>Repülőterek</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Név</th>
                <th>Város</th>
                <th>Ország</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($airports as $airport): ?>
                <tr>
                    <td><?= htmlspecialchars($airport['REPULOTER_ID']) ?></td>
                    <td><?= htmlspecialchars($airport['NEV']) ?></td>
                    <td><?= htmlspecialchars($airport['VAROS']) ?></td>
                    <td><?= htmlspecialchars($airport['ORSZAG']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h1>Induló járatok száma repülőterenként</h1>
    <table>
        <thead>
            <tr>
                <th>Repülőtér ID</th>
                <th>Név</th>
                <th>Induló járatok száma</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($departureCounts as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['REPULOTER_ID']) ?></td>
                    <td><?= htmlspecialchars($row['NEV']) ?></td>
                    <td><?= htmlspecialchars($row['INDULASI_JARATOK']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h1>Érkező járatok száma repülőterenként</h1>
    <table>
        <thead>
            <tr>
                <th>Repülőtér ID</th>
                <th>Név</th>
                <th>Érkező járatok száma</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($arrivalCounts as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['REPULOTER_ID']) ?></td>
                    <td><?= htmlspecialchars($row['NEV']) ?></td>
                    <td><?= htmlspecialchars($row['ERKEZESI_JARATOK']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
