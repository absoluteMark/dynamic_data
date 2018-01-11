<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST['contestant_name']!="") {

    $contestant = new CONTESTANTS($DB_con);

    $contestant_name = $_POST['contestant_name'];
    $contestant_number = $_POST['contestant_number'];
    $horse_name = $_POST['horse_name'];

    $response = $contestant->createContestant($contestant_name, $contestant_number, $horse_name);

}
else {
    $response = array();
    $response['status'] = 'error'; // could not create record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Must have a name !';
}


echo json_encode($response);