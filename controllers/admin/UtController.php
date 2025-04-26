<?php
require '../../controllers/AdminCheckController.php';
require '../../models/UtModel.php';
require '../../config/connection.php';

if (!isset($_SESSION['username']) || !isAdmin($_SESSION['username'])) {
    header("HTTP/1.1 403 Forbidden");
    echo "Ehhez az oldalhoz nincs jogosultsága. <br> <a href='../../index.php'>Vissza a főoldalra</a>";
    exit();
}

$model = new UtModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['ut_id'] ?? null;
    $indulasi_repuloter_id = htmlspecialchars($_POST['indulasi_repuloter_id']);
    $erkezesi_repuloter_id = htmlspecialchars($_POST['erkezesi_repuloter_id']);

    if ($action === 'add') {
        $success = $model->createRoute($indulasi_repuloter_id, $erkezesi_repuloter_id);
    } elseif ($action === 'edit' && $id) {
        $success = $model->updateRoute($id, $indulasi_repuloter_id, $erkezesi_repuloter_id);
    } elseif ($action === 'delete' && $id) {
        $success = $model->deleteRoute($id);
    }

    if ($success) {
        header('Location: ../../controllers/admin/UtController.php');
    } else {
        echo "Operation failed.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $routes = $model->getAllRoutes();
    $airports = $model->getAllAirports();
    include '../../views/admin/ut.php';
}
?>