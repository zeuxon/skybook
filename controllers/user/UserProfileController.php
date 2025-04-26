<?php
require '../../models/FelhasznaloModel.php';
require '../../config/connection.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../views/login.html");
    exit();
}

$username = $_SESSION['username'];
$model = new FelhasznaloModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $user = $model->getUserByUsername($username);
    include '../../views/user/profile_user.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $newUsername = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $postalCode = htmlspecialchars($_POST['postal_code']);
    $city = htmlspecialchars($_POST['city']);

    if ($model->updateUser($username, $newUsername, $email, $telephone, $postalCode, $city)) {
        $_SESSION['username'] = $newUsername;
        header("Location: ../../controllers/user/UserProfileController.php?success=1");
        exit();
    } else {
        echo "Error: Failed to update profile.";
    }
}

oci_close($conn);
?>