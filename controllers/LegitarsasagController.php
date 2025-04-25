<?php
require '../models/LegitarsasagModel.php';
require '../config/connection.php';

$model = new LegitarsagModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['legitarsasag_id'] ?? null;
    $nev = htmlspecialchars($_POST['nev']);
    $szekhely = htmlspecialchars($_POST['szekhely']);
    $orszag = htmlspecialchars($_POST['orszag']);

    if ($action === 'add') {
        $success = $model->createAirline($nev, $szekhely, $orszag);
    } elseif ($action === 'edit' && $id) {
        $success = $model->updateAirline($id, $nev, $szekhely, $orszag);
    } elseif ($action === 'delete' && $id) {
        $success = $model->deleteAirline($id);
    }

    if ($success) {
        header('Location: ../controllers/LegitarsasagController.php');
    } else {
        echo "Operation failed.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $airlines = $model->getAllAirlines();
    include '../views/legitarsasag.php';
}
?>