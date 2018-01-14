<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST != "") {

    $guest = new \App\Scoreboard\ GUESTS($DB_con);

    $response = $guest->deleteGuest($_POST['guestId']);


}
else {
    $response = array();
    $response['status'] = 'error'; // could not delete record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Failed to connect';
}


echo json_encode($response);