<?php
require '../../models/RepulojaratModel.php';
require '../../config/connection.php';

$model = new RepulojaratModel($conn);

$airports = $model->getAllAirports();

$from = isset($_GET['from']) && $_GET['from'] !== '' ? $_GET['from'] : null;
$to = isset($_GET['to']) && $_GET['to'] !== '' ? $_GET['to'] : null;
$date = isset($_GET['date']) && $_GET['date'] !== '' ? $_GET['date'] : null;

$flights = $model->getAllFlightsWithTicketsFiltered($from, $to, $date);
$flightCount = $model->getFilteredFlightCount($from, $to, $date);

$popularFlights = $model->getPopularFlights();

include '../../views/user/repulojarat_user.php';
?>