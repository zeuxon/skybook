<?php
require '../../controllers/AdminCheckController.php';
require '../../models/RepuloterModel.php';
require '../../config/connection.php';

if (!isset($_SESSION['username']) || !isAdmin($_SESSION['username'])) {
    header("HTTP/1.1 403 Forbidden");
    echo "Ehhez az oldalhoz nincs jogosultsága. <br> <a href='../../index.php'>Vissza a főoldalra</a>";
    exit();
}

$model = new RepuloterModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['repuloter_id'] ?? null;
    $nev = htmlspecialchars($_POST['nev']);
    $varos = htmlspecialchars($_POST['varos']);
    $orszag = htmlspecialchars($_POST['orszag']);

    if ($action === 'add') {
        $success = $model->createAirport($nev, $varos, $orszag);
    } elseif ($action === 'edit' && $id) {
        $success = $model->updateAirport($id, $nev, $varos, $orszag);
    } elseif ($action === 'delete' && $id) {
        $success = $model->deleteAirport($id);
    }

    if ($success) {
        header('Location: ../../controllers/admin/RepuloterController.php');
    } else {
        echo "Operation failed.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $airports = $model->getAllAirports();
    $departureCounts = $model->getDepartureCounts();
    $arrivalCounts = $model->getArrivalCounts();
    include '../../views/admin/repuloter.php';
}
?>