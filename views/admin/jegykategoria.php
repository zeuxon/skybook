<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jegykategória kezelése</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <a href="../../index.php">Vissza</a>
    <h1>Jegykategória kezelése</h1>
    <form method="POST" action="../../controllers/admin/JegykategoriaController.php">
        <label for="jegykategoria_id">Jegykategória ID (csak módosításhoz/törléshez):</label>
        <input type="number" id="jegykategoria_id" name="jegykategoria_id"><br>
        <label for="nev">Név:</label>
        <input type="text" id="nev" name="nev" required><br>
        <label for="kedvezmeny_szazalek">Kedvezmény százalék:</label>
        <input type="number" id="kedvezmeny_szazalek" name="kedvezmeny_szazalek" required><br>
        <button type="submit" name="action" value="add">Hozzáadás</button>
        <button type="submit" name="action" value="edit">Módosítás</button>
        <button type="submit" name="action" value="delete">Törlés</button>
    </form>

    <h1>Jegykategóriák</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Név</th>
                <th>Kedvezmény Százalék</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= htmlspecialchars($category['JEGYKATEGORIA_ID']) ?></td>
                    <td><?= htmlspecialchars($category['NEV']) ?></td>
                    <td><?= htmlspecialchars($category['KEDVEZMENY_SZAZALEK']) ?>%</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

        <h1>Leggyakrabban használt jegykategóriák</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Név</th>
                    <th>Használat (db)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mostUsedCategories as $cat): ?>
                    <tr>
                        <td><?= htmlspecialchars($cat['JEGYKATEGORIA_ID']) ?></td>
                        <td><?= htmlspecialchars($cat['NEV']) ?></td>
                        <td><?= htmlspecialchars($cat['DARAB']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>
</html>