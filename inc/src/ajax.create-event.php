<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST['event_name']!="") {

    $event = new EVENTS($DB_con);


    //$uploadResponse = $event->uploadFile($_POST);

    $response = $event->add_event($_POST);

}
else {
    $response = array();
    $response['status'] = 'error'; // could not register
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Could not register event.';
}

echo json_encode($response);