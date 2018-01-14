<?php

header('Content-type: application/json');

require_once 'dbconfig.php';

if ($_POST['segmentId']) {


    $segmentID = $_POST['segmentId'];
    $results = new \App\Scoreboard\ RESULTS($DB_con);

    $response = $results->getAvByName($segmentID);


} else {
    $response = array();
    $response['status'] = 'error';
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Error.';
}


echo json_encode($response);