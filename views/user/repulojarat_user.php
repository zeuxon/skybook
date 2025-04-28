<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repülőjáratok</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <a href="../../index.php">Vissza</a>
    <h1>Repülőjáratok</h1>
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <p style="color: green;">Foglalás sikeresen létrehozva!</p>
    <?php endif; ?>
    <table>
    <thead>
        <tr>
            <th>Járat ID</th>
            <th>Légitársaság</th>
            <th>Repülőgép Típus</th>
            <th>Indulási Repülőtér</th>
            <th>Érkezési Repülőtér</th>
            <th>Indulási Idő</th>
            <th>Érkezési Idő</th>
            <th>Jegyek</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $currentFlightId = null;
        foreach ($flights as $flight): 
            if ($currentFlightId !== $flight['JARATID']): 
                $currentFlightId = $flight['JARATID'];
        ?>
            <tr>
                <td><?= htmlspecialchars($flight['JARATID']) ?></td>
                <td><?= htmlspecialchars($flight['LEGITARSASAG_NEV']) ?></td>
                <td><?= htmlspecialchars($flight['REPULOGEP_TIPUS']) ?></td>
                <td><?= htmlspecialchars($flight['INDULASI_REPULOTER_NEV']) ?></td>
                <td><?= htmlspecialchars($flight['ERKEZESI_REPULOTER_NEV']) ?></td>
                <td>
                    <?php 
                    $indulasiIdo = new DateTime($flight['INDULASI_IDO']);
                    echo htmlspecialchars($indulasiIdo->format('Y.m.d H:i'));
                    ?>
                </td>
                <td>
                    <?php 
                    $erkezesiIdo = new DateTime($flight['ERKEZESI_IDO']);
                    echo htmlspecialchars($erkezesiIdo->format('Y.m.d H:i'));
                    ?>
                </td>
                <td class="ticket-group">
                    <ul>
                        <?php 

                        $ticketGroups = [];
                        foreach ($flights as $ticket) {
                            if ($ticket['JARATID'] === $currentFlightId && !empty($ticket['JEGY_ID'])) {
                                $key = $ticket['JEGYKATEGORIA_NEV'];
                                if (!isset($ticketGroups[$key])) {
                                    $ticketGroups[$key] = [
                                        'count' => 0,
                                        'price' => $ticket['JEGY_AR'],
                                        'category' => $ticket['JEGYKATEGORIA_NEV'],
                                        'ticket_ids' => []
                                    ];
                                }
                                $ticketGroups[$key]['count']++;
                                $ticketGroups[$key]['ticket_ids'][] = $ticket['JEGY_ID'];
                            }
                        }

                        foreach ($ticketGroups as $group): ?>
                            <li>
                                <?= htmlspecialchars($group['count']) ?> db - <?= htmlspecialchars($group['category']) ?> - <?= htmlspecialchars($group['price']) ?> Ft
                                <form method="POST" action="../../controllers/user/BookingController.php" style="display:inline;">
                                    <input type="hidden" name="ticket_id" value="<?= htmlspecialchars($group['ticket_ids'][0]) ?>">
                                    <button type="submit" name="action" value="book">Foglalás</button>
                                </form>
                            </li>
                        <?php endforeach; ?>

                        <?php if (empty($ticketGroups)): ?>
                            <li>Nincs elérhető jegy</li>
                        <?php endif; ?>
                    </ul>
                </td>
            </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
    </table>
</body>
</html>