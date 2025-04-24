<?php
require '../models/UtModel.php';
require '../config/connection.php';

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
        header('Location: ../controllers/UtController.php');
    } else {
        echo "Operation failed.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $routes = $model->getAllRoutes();
    $airports = $model->getAllAirports();
    include '../views/ut.php';
}
?>