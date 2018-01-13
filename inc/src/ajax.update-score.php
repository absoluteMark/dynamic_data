<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST != "") {

    $score = new SCORES($DB_con);

    //print_r($_POST);

    $name = $_POST['Score_Name'];
    $id = $_POST['scId'];

    $response = $score->updateScore($name,$id);



}
else {
    $response = array();
    $response['status'] = 'error'; // could not create record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Could not update !';
}


echo json_encode($response);