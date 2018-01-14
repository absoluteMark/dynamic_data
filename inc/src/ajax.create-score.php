<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST['score_name']!="") {

    $score = new \App\Scoreboard\ SCORES($DB_con);

    //print_r($_POST);

    $score_name = $_POST['score_name'];
    $sId = $_POST['segmentId'];

    $response = $score->createScore($score_name,$sId);

}
else {
    $response = array();
    $response['status'] = 'error'; // could not create record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Must have a name !';
}


echo json_encode($response);