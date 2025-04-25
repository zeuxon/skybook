<?php
require '../models/FoglalasModel.php';
require '../config/connection.php';

$model = new FoglalasModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['foglalas_id'] ?? null;
    $felhasznalo_id = $_POST['felhasznalo_id'];
    $datum = $_POST['datum'];
    $statusz = htmlspecialchars($_POST['statusz']);

    if ($action === 'add') {
        $success = $model->createBooking($felhasznalo_id, $datum, $statusz);
    } elseif ($action === 'edit' && $id) {
        $success = $model->updateBooking($id, $felhasznalo_id, $datum, $statusz);
    } elseif ($action === 'delete' && $id) {
        $success = $model->deleteBooking($id);
    }

    if ($success) {
        header('Location: ../controllers/FoglalasController.php');
    } else {
        echo "Operation failed.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $bookings = $model->getAllBookings(); // Fetch all bookings
    $users = $model->getAllUsers(); // Fetch all users
    include '../views/foglalas.php'; // Pass data to the view
}
?>