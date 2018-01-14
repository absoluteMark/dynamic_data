<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST != "") {

    $score = new \App\Scoreboard\ SCORES($DB_con);

    $response = $score->deleteScore($_POST['scoreId']);


}
else {
    $response = array();
    $response['status'] = 'error'; // could not delete record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Failed to connect';
}


echo json_encode($response);