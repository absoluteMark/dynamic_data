<?php


/**
 *
 * Brings up the show segment
 * selected
 *
 *
 *
 */

header('Content-type: application/json');

require_once 'dbconfig.php';

if ($_GET['segmentId']) {

    $segment = new \App\Scoreboard\ SEGMENTS($DB_con);
    $_SESSION['segment_id'] = $_GET['segmentId'];
    $segmentId = $_GET['segmentId'];
    $response = $segment->segment_detail($segmentId);

} else {
    $response = array();
    $response['status'] = 'error';
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Error.';
}

echo json_encode($response);


?>
