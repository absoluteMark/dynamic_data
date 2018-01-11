<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST) {

    $event = new EVENTS($DB_con);

    $response = $event->get_all_events();

} else {
    $response = array();
    $response['status'] = 'error';
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Error.';
}


echo json_encode($response);