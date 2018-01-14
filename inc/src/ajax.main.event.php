<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_GET['name']) {

    $event = new \App\Scoreboard\ EVENTS($DB_con);

    $name = $_GET['name'];

    $response = $event->event_details($name);

} else {
    $response = array();
    $response['status'] = 'error';
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Error.';
}


echo json_encode($response);