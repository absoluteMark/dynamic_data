<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST) {

    $score = new SCORES($DB_con);

    $response = $score->getScores($_POST['segmentId']);

} else {
    $response = array();
    $response['status'] = 'error';
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Error.';
}


echo json_encode($response);