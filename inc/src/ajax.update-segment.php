<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST != "") {

    $segment = new SEGMENTS($DB_con);

    $segmentName = $_POST['segment_name'];
    $segmentID = $_POST['segment_id'];
    $start_time = $_POST['start_time'];

    $response = $segment->updateSegment($segmentName,$start_time,$segmentID);



}
else {
    $response = array();
    $response['status'] = 'error'; // could not create record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Must have a name !';
}


echo json_encode($response);