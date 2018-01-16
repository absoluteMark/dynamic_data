<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST != "") {

    $result = new \App\Scoreboard\ RESULTS($DB_con);

    $response = $result->insertResults($_POST);

    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Records Inserted !';

}
else {
    $response = array();
    $response['status'] = 'error'; // could not create record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Some Error Occurred !';
}


echo json_encode($response);