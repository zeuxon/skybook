<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilom</title>
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>
    <a href="../../index.php">Vissza</a>
    <h1>Profilom</h1>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <p style="color: green;">Profil sikeresen frissítve!</p>
    <?php endif; ?>

    <form method="POST" action="../../controllers/user/UserProfileController.php">
        <label for="username">Felhasználónév:</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['FELHASZNALONEV']) ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['EMAIL']) ?>" required><br>

        <label for="telephone">Telefonszám:</label>
        <input type="tel" id="telephone" name="telephone" value="<?= htmlspecialchars($user['TELEFONSZAM']) ?>" required><br>

        <label for="postal_code">Irányítószám:</label>
        <input type="number" id="postal_code" name="postal_code" value="<?= htmlspecialchars($user['IRANYITOSZAM']) ?>" required><br>

        <label for="city">Település:</label>
        <input type="text" id="city" name="city" value="<?= htmlspecialchars($user['TELEPULES']) ?>" required><br>

        <button type="submit" name="action" value="update">Frissítés</button>
    </form>
</body>
</html>