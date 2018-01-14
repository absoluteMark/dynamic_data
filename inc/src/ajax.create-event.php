<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST['event_name']!="") {

    $event = new \App\Scoreboard\ EVENTS($DB_con);

    $response = $event->add_event($_POST);

}
else {
    $response = array();
    $response['status'] = 'error'; // could not register
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Could not register event.';
}

echo json_encode($response);