<?php
require '../../models/RepulojaratModel.php';
require '../../config/connection.php';

$model = new RepulojaratModel($conn);

$flights = $model->getAllFlightsWithTickets();

include '../../views/user/repulojarat_user.php';
?>