<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foglalásaim</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <a href="../../index.php">Vissza</a>
    <h1>Foglalásaim</h1>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <p style="color: green;">Foglalás sikeresen létrehozva!</p>
    <?php elseif (isset($_GET['success']) && $_GET['success'] == 2): ?>
        <p style="color: green;">Foglalás sikeresen törölve!</p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Foglalás ID</th>
                <th>Dátum</th>
                <th>Státusz</th>
                <th>Indulási Repülőtér</th>
                <th>Érkezési Repülőtér</th>
                <th>Jegykategória</th>
                <th>Ár</th>
                <th>Légitársaság</th>
                <th>Művelet</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($bookings)): ?>
                <tr>
                    <td colspan="9">Nincsenek foglalások.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['FOGLALAS_ID']) ?></td>
                        <td><?= htmlspecialchars($booking['DATUM']) ?></td>
                        <td><?= htmlspecialchars($booking['STATUSZ']) ?></td>
                        <td><?= htmlspecialchars($booking['INDULASI_REPULOTER']) ?></td>
                        <td><?= htmlspecialchars($booking['ERKEZESI_REPULOTER']) ?></td>
                        <td><?= htmlspecialchars($booking['JEGYKATEGORIA']) ?></td>
                        <td><?= htmlspecialchars($booking['JEGY_AR']) ?> Ft</td>
                        <td><?= htmlspecialchars($booking['LEGITARSASAG_NEV']) ?></td>
                        <td>
                            <form method="POST" action="../../controllers/user/BookingController.php">
                                <input type="hidden" name="foglalas_id" value="<?= htmlspecialchars($booking['FOGLALAS_ID']) ?>">
                                <button type="submit" name="action" value="delete">Törlés</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>