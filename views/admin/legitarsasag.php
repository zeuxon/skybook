<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Légitársaság kezelése</title>
</head>
<body>
    <a href="../../index.php">Vissza</a>
    <h1>Légitársaság kezelése</h1>
    <form method="POST" action="../../controllers/admin/LegitarsasagController.php">
        <label for="legitarsasag_id">Légitársaság ID (csak módosításhoz/törléshez):</label>
        <input type="number" id="legitarsasag_id" name="legitarsasag_id"><br>
        <label for="nev">Név:</label>
        <input type="text" id="nev" name="nev" required><br>
        <label for="szekhely">Székhely:</label>
        <input type="text" id="szekhely" name="szekhely" required><br>
        <label for="orszag">Ország:</label>
        <input type="text" id="orszag" name="orszag" required><br>
        <button type="submit" name="action" value="add">Hozzáadás</button>
        <button type="submit" name="action" value="edit">Módosítás</button>
        <button type="submit" name="action" value="delete">Törlés</button>
    </form>

    <h1>Légitársaságok</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Név</th>
                <th>Székhely</th>
                <th>Ország</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($airlines as $airline): ?>
                <tr>
                    <td><?= htmlspecialchars($airline['LEGITARSASAG_ID']) ?></td>
                    <td><?= htmlspecialchars($airline['NEV']) ?></td>
                    <td><?= htmlspecialchars($airline['SZEKHELY']) ?></td>
                    <td><?= htmlspecialchars($airline['ORSZAG']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>