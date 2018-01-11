<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST) {

    $judge = new JUDGES($DB_con);

    $response = $judge->getJudges();

} else {
    $response = array();
    $response['status'] = 'error';
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Error.';
}


echo json_encode($response);