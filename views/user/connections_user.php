<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Átszállások</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <a href="../../index.php">Vissza</a>
    <h1>Átszállások</h1>
    <table>
        <thead>
            <tr>
                <th>Első Járat ID</th>
                <th>Indulási Repülőtér</th>
                <th>Indulási Idő</th>
                <th>Érkezési Repülőtér</th>
                <th>Érkezési Idő</th>
                <th>Második Járat ID</th>
                <th>Indulási Repülőtér</th>
                <th>Indulási Idő</th>
                <th>Érkezési Repülőtér</th>
                <th>Érkezési Idő</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($connections)): ?>
                <?php foreach ($connections as $connection): ?>
                    <tr>
                        <td><?= htmlspecialchars($connection['FIRST_FLIGHT_ID']) ?></td>
                        <td><?= htmlspecialchars($connection['FIRST_DEPARTURE_AIRPORT']) ?></td>
                        <td><?= htmlspecialchars($connection['FIRST_DEPARTURE_TIME']) ?></td>
                        <td><?= htmlspecialchars($connection['FIRST_ARRIVAL_AIRPORT']) ?></td>
                        <td><?= htmlspecialchars($connection['FIRST_ARRIVAL_TIME']) ?></td>
                        <td><?= htmlspecialchars($connection['SECOND_FLIGHT_ID']) ?></td>
                        <td><?= htmlspecialchars($connection['SECOND_DEPARTURE_AIRPORT']) ?></td>
                        <td><?= htmlspecialchars($connection['SECOND_DEPARTURE_TIME']) ?></td>
                        <td><?= htmlspecialchars($connection['SECOND_ARRIVAL_AIRPORT']) ?></td>
                        <td><?= htmlspecialchars($connection['SECOND_ARRIVAL_TIME']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">Nincsenek elérhető átszállások.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>