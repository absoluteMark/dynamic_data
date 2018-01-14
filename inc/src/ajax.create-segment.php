<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST['segment_name']!="") {

    $segment = new \App\Scoreboard\ SEGMENTS($DB_con);

    $segment_name = $_POST['segment_name'];
    $event_id = $_SESSION['event_id'];
    $start_time = $_POST['start_time'];

    $response = $segment->createSegment($segment_name,$event_id,$start_time);

}
else {
    $response = array();
    $response['status'] = 'error'; // could not create record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Must have a name !';
}


echo json_encode($response);