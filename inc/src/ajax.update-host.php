<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST != "") {

    $host = new \App\Scoreboard\ HOSTS($DB_con);

    //print_r($_POST);

    $name = $_POST['Host_Name'];
    $horse = $_POST['Horse'];//note should be the jQuery passed variable
    $subtitle = $_POST['Subtitle'];
    $id = $_POST['hostId'];

    $response = $host->updateHost($name,$horse,$subtitle,$id);



}
else {
    $response = array();
    $response['status'] = 'error'; // could not create record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Could not update !';
}


echo json_encode($response);