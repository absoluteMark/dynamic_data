<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST != "") {

    //print_r($_POST);

    $result = new \App\Scoreboard\ RESULTS($DB_con);

    $response = $result->updateResults($_POST);



}
else {
    $response = array();
    $response['status'] = 'error'; // could not create record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Must have a name !';
}


echo json_encode($response);