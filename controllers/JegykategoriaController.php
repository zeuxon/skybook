<?php
require '../models/JegykategoriaModel.php';
require '../config/connection.php';

$model = new JegykategoriaModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $id = $_POST['jegykategoria_id'] ?? null;
    $nev = htmlspecialchars($_POST['nev']);
    $kedvezmeny_szazalek = $_POST['kedvezmeny_szazalek'];

    if ($action === 'add') {
        $success = $model->createCategory($nev, $kedvezmeny_szazalek);
    } elseif ($action === 'edit' && $id) {
        $success = $model->updateCategory($id, $nev, $kedvezmeny_szazalek);
    } elseif ($action === 'delete' && $id) {
        $success = $model->deleteCategory($id);
    }

    if ($success) {
        header('Location: ../controllers/JegykategoriaController.php');
    } else {
        echo "Operation failed.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $categories = $model->getAllCategories();
    include '../views/jegykategoria.php';
}
?>