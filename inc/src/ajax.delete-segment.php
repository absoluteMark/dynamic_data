<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST != "") {

    $segment = new SEGMENTS($DB_con);

    $response = $segment->deleteSegment($_POST['segmentId']);


}
else {
    $response = array();
    $response['status'] = 'error'; // could not delete record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Failed to connect';
}


echo json_encode($response);