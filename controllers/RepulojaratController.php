<?php
require '../models/RepulojaratModel.php';
require '../config/connection.php';

$model = new RepulojaratModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['jaratid'] ?? null;
    $repulogep_type = $_POST['repulogep_type'];
    $ut_id = $_POST['route'];
    $indulasi_ido = $_POST['indulasi_ido'];
    $erkezesi_ido = $_POST['erkezesi_ido'];

    if ($action === 'add') {
        $success = $model->createFlight($repulogep_type, $ut_id, $indulasi_ido, $erkezesi_ido);
    } elseif ($action === 'edit' && $id) {
        $success = $model->updateFlight($id, $repulogep_type, $ut_id, $indulasi_ido, $erkezesi_ido);
    } elseif ($action === 'delete' && $id) {
        $success = $model->deleteFlight($id);
    }

    if ($success) {
        header('Location: ../controllers/RepulojaratController.php');
    } else {
        echo "Operation failed.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $flights = $model->getAllFlights();
    include '../views/repulojarat.php';
}
?>