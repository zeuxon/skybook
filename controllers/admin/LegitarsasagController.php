<?php
require '../../controllers/AdminCheckController.php';
require '../../models/LegitarsasagModel.php';
require '../../config/connection.php';

if (!isset($_SESSION['username']) || !isAdmin($_SESSION['username'])) {
    header("HTTP/1.1 403 Forbidden");
    echo "Ehhez az oldalhoz nincs jogosultsága. <br> <a href='../../index.php'>Vissza a főoldalra</a>";
    exit();
}

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
        header('Location: ../../controllers/admin/LegitarsasagController.php');
    } else {
        echo "Operation failed.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $airlines = $model->getAllAirlines();
    $yearly_stats = $model->getYearlyStats();
    $monthly_stats = $model->getMonthlyStats();
    include '../../views/admin/legitarsasag.php';
}
?>