<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST) {

    $scorename = new SCORENAMES($DB_con);

    $response = $scorename->getScorenames();

} else {
    $response = array();
    $response['status'] = 'error';
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Error.';
}


echo json_encode($response);