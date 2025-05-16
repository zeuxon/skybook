<?php
require '../../controllers/AdminCheckController.php';
require '../../models/BiztositasModel.php';
require '../../config/connection.php';

if (!isset($_SESSION['username']) || !isAdmin($_SESSION['username'])) {
    header("HTTP/1.1 403 Forbidden");
    echo "Ehhez az oldalhoz nincs jogosultsága. <br> <a href='../../index.php'>Vissza a főoldalra</a>";
    exit();
}

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
        header('Location: ../../controllers/admin/BiztositasController.php');
    } else {
        echo "Operation failed.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $insurance = $model->getAllInsurance();
    $mostUsedInsurance = $model->getMostUsedInsurance();
    include '../../views/admin/biztositas.php';
}
?>