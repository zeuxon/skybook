<?php
session_start();
require 'controllers/AdminCheckController.php';
require 'config/connection.php';

if (isset($_SESSION['username'])) {
    $isAdmin = isAdmin($_SESSION['username']);
}

$query = oci_parse($conn, "SELECT repuloter_id, nev FROM Repuloter ORDER BY nev");
oci_execute($query);

$airports = [];
while ($row = oci_fetch_assoc($query)) {
    $airports[] = $row;
}
oci_free_statement($query);
oci_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kezdőlap</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php if (isset($_GET['success'])): ?>
        <?php if ($_GET['success'] === 'login'): ?>
            <p style="color: green;">Sikeres bejelentkezés!</p>
        <?php elseif ($_GET['success'] === 'register'): ?>
            <p style="color: green;">Sikeres regisztráció!</p>
        <?php endif; ?>
    <?php endif; ?>
    <h1>
        Üdvözöljük a rendszerben,
        <?php 
            if (isset($_SESSION['username'])) {
                echo htmlspecialchars($_SESSION['username']);
            } else {
                echo "Vendég";
            }
        ?>!
    </h1>
    <nav>
        <ul>
            <?php if (isset($_SESSION['username'])): ?>
                <?php if ($isAdmin): ?>
                <li><a href="controllers/admin/RepuloterController.php">Repülőtér kezelése</a></li>
                <li><a href="controllers/admin/UtController.php">Útvonalak kezelése</a></li>
                <li><a href="controllers/admin/LegitarsasagController.php">Légitársaság kezelése</a></li>
                <li><a href="controllers/admin/RepulogepController.php">Repülőgép kezelése</a></li>
                <li><a href="controllers/admin/RepulojaratController.php">Repülőjárat kezelése</a></li>
                <li><a href="controllers/admin/FoglalasController.php">Foglalás kezelése</a></li>
                <li><a href="controllers/admin/BiztositasController.php">Biztosítás kezelése</a></li>
                <li><a href="controllers/admin/JegykategoriaController.php">Jegykategória kezelése</a></li>
                <li><a href="controllers/admin/JegyController.php">Jegy kezelése</a></li>
                <?php endif; ?>
                <li><a href="controllers/user/RepulojaratUserController.php">Repülőjáratok megtekintése</a></li>
                <li><a href="controllers/user/BookingController.php">Foglalásaim</a></li>
                <li><a href="controllers/user/UserProfileController.php">Profilom</a></li>
                <li><a href="controllers/LogoutController.php">Kijelentkezés</a></li>
            <?php else: ?>
                <li><a href="views/login.html">Bejelentkezés</a></li>
                <li><a href="views/register.html">Regisztráció</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <form method="GET" action="controllers/user/ConnectionController.php">
        <label for="from_airport">Indulási Repülőtér:</label>
        <select id="from_airport" name="from_airport" required>
            <?php foreach ($airports as $airport): ?>
                <option value="<?= htmlspecialchars($airport['REPULOTER_ID']) ?>">
                    <?= htmlspecialchars($airport['NEV']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="to_airport">Érkezési Repülőtér:</label>
        <select id="to_airport" name="to_airport" required>
            <?php foreach ($airports as $airport): ?>
                <option value="<?= htmlspecialchars($airport['REPULOTER_ID']) ?>">
                    <?= htmlspecialchars($airport['NEV']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Keresés</button>
    </form>
</body>
</html>