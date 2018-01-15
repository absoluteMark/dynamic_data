<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST['name']!="") {

    $host = new \App\Scoreboard\ HOSTS($DB_con);

    //print_r($_POST);

    $name = $_POST['name'];
    $subtitle = $_POST['subtitle'];
    $horse = $_POST['horse'];
    $event_id = $_POST['eventId'];

    $response = $host->createHost($name, $subtitle, $horse, $event_id);

}
else {
    $response = array();
    $response['status'] = 'error'; // could not create record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Must have a name !';
}


echo json_encode($response);