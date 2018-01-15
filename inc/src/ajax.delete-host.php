<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST != "") {

    $host = new \App\Scoreboard\ HOSTS($DB_con);

    $response = $host->deleteHost($_POST['hostId']);


}
else {
    $response = array();
    $response['status'] = 'error'; // could not delete record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Failed to connect';
}


echo json_encode($response);