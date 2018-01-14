<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST != "") {

    $segment = new \App\Scoreboard\ SEGMENTS($DB_con);

    //print_r($_POST);

    $segmentName = $_POST['Segment_Name'];
    $segmentID = $_POST['segmentId'];//note should be the jQuery passed variable
    $start_time = $_POST['Start_Time'];

    $response = $segment->updateSegment($segmentName,$start_time,$segmentID);



}
else {
    $response = array();
    $response['status'] = 'error'; // could not create record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Could not update !';
}


echo json_encode($response);