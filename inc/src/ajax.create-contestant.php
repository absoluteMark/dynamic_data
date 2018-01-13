<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST['contestant_name']!="") {

    $contestant = new CONTESTANTS($DB_con);

    //print_r($_POST);

    $contestant_name = $_POST['contestant_name'];
    $contestant_number = $_POST['contestant_number'];
    $horse_name = $_POST['horse_name'];
    $segment_id = $_POST['segmentId'];

    $response = $contestant->createContestant($contestant_name, $contestant_number, $horse_name, $segment_id);

}
else {
    $response = array();
    $response['status'] = 'error'; // could not create record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Must have a name !';
}


echo json_encode($response);