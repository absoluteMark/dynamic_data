<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST != "") {

    $guest = new \App\Scoreboard\ GUESTS($DB_con);

    //print_r($_POST);

    $response = $guest->updateGuest($_POST);



}
else {
    $response = array();
    $response['status'] = 'error'; // could not create record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Could not update !';
}


echo json_encode($response);