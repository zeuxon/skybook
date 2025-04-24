<?php
session_start();
$isLoggedIn = isset($_SESSION['username']);
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
        <?php if ($isLoggedIn): ?>
            <?php echo htmlspecialchars($_SESSION['username']); ?>!
        <?php endif; ?>
    </h1>
    <nav>
        <ul>
            <?php if ($isLoggedIn): ?>
                <li><a href="views/repuloter_form.html">Repülőtér kezelése</a></li>
                <li><a href="controllers/logout.php">Kijelentkezés</a></li>
            <?php else: ?>
                <li><a href="views/login.html">Bejelentkezés</a></li>
                <li><a href="views/register.html">Regisztráció</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</body>
</html>