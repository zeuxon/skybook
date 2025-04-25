<?php
require '../models/BiztositasModel.php';
require '../config/connection.php';

$model = new BiztositasModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['biztositas_id'] ?? null;
    $nev = htmlspecialchars($_POST['nev']);
    $ar = $_POST['ar'];

    if ($action === 'add') {
        $success = $model->createInsurance($nev, $ar);
    } elseif ($action === 'edit' && $id) {
        $success = $model->updateInsurance($id, $nev, $ar);
    } elseif ($action === 'delete' && $id) {
        $success = $model->deleteInsurance($id);
    }

    if ($success) {
        header('Location: ../controllers/BiztositasController.php');
    } else {
        echo "Operation failed.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $insurance = $model->getAllInsurance();
    include '../views/biztositas.php';
}
?>