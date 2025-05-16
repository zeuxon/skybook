<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biztosítás kezelése</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <a href="../../index.php">Vissza</a>
    <h1>Biztosítás kezelése</h1>
    <form method="POST" action="../../controllers/admin/BiztositasController.php">
        <label for="biztositas_id">Biztosítás ID (csak módosításhoz/törléshez):</label>
        <input type="number" id="biztositas_id" name="biztositas_id"><br>
        <label for="nev">Név:</label>
        <input type="text" id="nev" name="nev" required><br>
        <label for="ar">Ár:</label>
        <input type="number" id="ar" name="ar" required><br>
        <button type="submit" name="action" value="add">Hozzáadás</button>
        <button type="submit" name="action" value="edit">Módosítás</button>
        <button type="submit" name="action" value="delete">Törlés</button>
    </form>

    <h1>Biztosítások</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Név</th>
                <th>Ár</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($insurance as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['BIZTOSITAS_ID']) ?></td>
                    <td><?= htmlspecialchars($item['NEV']) ?></td>
                    <td><?= htmlspecialchars($item['AR']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h1>Leggyakrabban használt biztosítások</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Név</th>
                <th>Ár</th>
                <th>Használat száma</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mostUsedInsurance as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['BIZTOSITAS_ID']) ?></td>
                    <td><?= htmlspecialchars($item['NEV']) ?></td>
                    <td><?= htmlspecialchars($item['AR']) ?></td>
                    <td><?= htmlspecialchars($item['HASZNALAT_SZAMA']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>