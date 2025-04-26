<?php
session_start();
require 'controllers/AdminCheckController.php';

if (!isset($_SESSION['username'])) {
    header("Location: views/login.html");
    exit();
}

$isAdmin = isAdmin($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kezdőlap</title>
</head>
<body>
    <h1>
        Üdvözöljük a rendszerben!
        <?php echo htmlspecialchars($_SESSION['username']); ?>!
    </h1>
    <nav>
        <ul>
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
            <li><a href="controllers/LogoutController.php">Kijelentkezés</a></li>
        </ul>
    </nav>
</body>
</html>