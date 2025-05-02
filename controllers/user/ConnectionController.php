<?php
require '../../models/RepulojaratModel.php';
require '../../config/connection.php';

$model = new RepulojaratModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $fromAirportId = $_GET['from_airport'] ?? null;
    $toAirportId = $_GET['to_airport'] ?? null;

    if ($fromAirportId && $toAirportId) {
        $connections = $model->getConnections($fromAirportId, $toAirportId);
        include '../../views/user/connections_user.php';
    } else {
        echo "Please select both departure and arrival airports.";
    }
}

oci_close($conn);
?>