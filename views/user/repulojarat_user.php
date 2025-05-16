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


    <form method="GET" style="margin-bottom: 20px;">
        <label>Indulási repülőtér:
            <select name="from">
                <option value="">-- Mindegy --</option>
                <?php foreach ($airports as $airport): ?>
                    <option value="<?= htmlspecialchars($airport['REPULOTER_ID']) ?>" <?= (isset($_GET['from']) && $_GET['from'] == $airport['REPULOTER_ID']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($airport['NEV']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Érkezési repülőtér:
            <select name="to">
                <option value="">-- Mindegy --</option>
                <?php foreach ($airports as $airport): ?>
                    <option value="<?= htmlspecialchars($airport['REPULOTER_ID']) ?>" <?= (isset($_GET['to']) && $_GET['to'] == $airport['REPULOTER_ID']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($airport['NEV']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Dátum:
            <input type="date" name="date" value="<?= isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '' ?>">
        </label>
        <button type="submit">Szűrés</button>
    </form>

    <p>
        Találatok száma: <strong><?= $flightCount ?></strong>
    </p>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <p style="color: green;">Foglalás sikeresen létrehozva!</p>
    <?php endif; ?>
    <table>
    <thead>
        <tr>
            <th>Járat ID</th>
            <th>Légitársaság</th>
            <th>Repülőgép Típus</th>
            <th>Étkezési lehetőség</th>
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
        $hasFlights = false;
        foreach ($flights as $flight):
            if ($currentFlightId !== $flight['JARATID']): 
                $hasFlights = true;
                $currentFlightId = $flight['JARATID'];
        ?>
            <tr>
                <td><?= htmlspecialchars($flight['JARATID']) ?></td>
                <td><?= htmlspecialchars($flight['LEGITARSASAG_NEV']) ?></td>
                <td><?= htmlspecialchars($flight['REPULOGEP_TIPUS']) ?></td>
                <td><?= htmlspecialchars($flight['REPULOGEP_ETKEZES']) ? 'Igen' : 'Nem' ?></td>
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
                                    'jarat_id' => $ticket['JARATID'],
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
                                <input type="hidden" name="jarat_id" value="<?= htmlspecialchars($group['jarat_id']) ?>">
                                <input type="hidden" name="jegy_id" value="<?= htmlspecialchars($group['ticket_ids'][0]) ?>">
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
        <?php if (!$hasFlights): ?>
            <tr>
                <td colspan="9" style="text-align:center;">Nincs találat a megadott szűrőkre.</td>
            </tr>
        <?php endif; ?>
    </tbody>
    </table>
    <h1>Legnépszerűbb járataink</h1>
    <table>
    <thead>
        <tr>
            <th>Indulási Repülőtér</th>
            <th>Érkezési Repülőtér</th>
            <th>Foglalások száma</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (count($popularFlights) === 0) {
            echo "<tr><td colspan='3'>Nincs egy járat sem</td></tr>";
        }
        foreach ($popularFlights as $flight):
        ?>
            <tr>
                <td><?= htmlspecialchars($flight['INDULASI_REPULOTER_NEV']) ?></td>
                <td><?= htmlspecialchars($flight['ERKEZESI_REPULOTER_NEV']) ?></td>
                <td><?= htmlspecialchars($flight['FOGLALASOK_SZAMA']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
</body>
</html>