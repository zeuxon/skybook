<?php
require '../models/JegyModel.php';
require '../config/connection.php';

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
        // Redirect to avoid form resubmission
        header('Location: ../controllers/JegyController.php');
        exit;
    } else {
        echo "Operation failed.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tickets = $model->getAllTickets(); // Fetch all tickets
    $bookings = $model->getAllBookingsForDropdown(); // Fetch bookings for dropdown
    $flights = $model->getAllFlightsForDropdown(); // Fetch flights for dropdown
    $categories = $model->getAllJegykategoriaForDropdown(); // Fetch jegykategoria for dropdown
    $legitarsasagok = $model->getAllLegitarsasagForDropdown(); // Fetch legitarsasag for dropdown
    include '../views/jegy.php'; // Pass data to the view
}
?>