<?php
require '../../controllers/AdminCheckController.php';
require '../../models/RepulogepModel.php';
require '../../config/connection.php';

if (!isset($_SESSION['username']) || !isAdmin($_SESSION['username'])) {
    header("HTTP/1.1 403 Forbidden");
    echo "Ehhez az oldalhoz nincs jogosultsága. <br> <a href='../../index.php'>Vissza a főoldalra</a>";
    exit();
}

$model = new RepulogepModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['repulogep_id'] ?? null;
    $legitarsasag_name = $_POST['legitarsasag_name'];
    $kapacitas = $_POST['kapacitas'];
    $tipus = htmlspecialchars($_POST['tipus']);

    if ($action === 'add') {
        $success = $model->createAircraft($legitarsasag_name, $kapacitas, $tipus);
    } elseif ($action === 'edit' && $id) {
        $success = $model->updateAircraft($id, $legitarsasag_name, $kapacitas, $tipus);
    } elseif ($action === 'delete' && $id) {
        $success = $model->deleteAircraft($id);
    }

    if ($success) {
        header('Location: ../../controllers/admin/RepulogepController.php');
    } else {
        echo "Operation failed.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $aircraft = $model->getAllAircraft();
    include '../../views/admin/repulogep.php';
}
?>