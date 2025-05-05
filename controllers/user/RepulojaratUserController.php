<?php
require '../../models/RepulojaratModel.php';
require '../../config/connection.php';

$model = new RepulojaratModel($conn);

$flights = $model->getAllFlightsWithTickets();
$popularFlights = $model->getPopularFlights();

include '../../views/user/repulojarat_user.php';
?>