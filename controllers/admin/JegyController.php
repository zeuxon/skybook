<?php
require '../../controllers/AdminCheckController.php';
require '../../models/JegyModel.php';
require '../../config/connection.php';

if (!isset($_SESSION['username']) || !isAdmin($_SESSION['username'])) {
    header("HTTP/1.1 403 Forbidden");
    echo "Ehhez az oldalhoz nincs jogosultsága. <br> <a href='../../index.php'>Vissza a főoldalra</a>";
    exit();
}

$model = new JegyModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['jegy_id'] ?? null;
    $foglalas_id = $_POST['foglalas_id'];
    $jarat_id = $_POST['jarat_id'];
    $jegykategoria_id = $_POST['jegykategoria_id'];
    $ar = $_POST['ar'];

    // Fetch the Foglalás date from the database
    $foglalas = $model->getBookingById($foglalas_id);
    $foglalas_datum = $foglalas['DATUM'];

    if ($action === 'add') {
        $success = $model->createTicket($foglalas_id, $jarat_id, $jegykategoria_id, $ar);
    } elseif ($action === 'edit' && $id) {
        $success = $model->updateTicket($id, $foglalas_id, $jarat_id, $jegykategoria_id, $ar);
    } elseif ($action === 'delete' && $id) {
        $success = $model->deleteTicket($id);
    }

    if ($success) {
        header('Location: ../../controllers/admin/JegyController.php');
        exit;
    } else {
        echo "Operation failed.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tickets = $model->getAllTickets();
    $bookings = $model->getAllBookingsForDropdown();
    $flights = $model->getAllFlightsForDropdown();
    $categories = $model->getAllJegykategoriaForDropdown();
    $legitarsasagok = $model->getAllLegitarsasagForDropdown();
    include '../../views/admin/jegy.php';
}
?>