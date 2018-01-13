<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST != "") {

    $guest = new GUESTS($DB_con);

    //print_r($_POST);

    $name = $_POST['Guest_Name'];
    $horse = $_POST['Horse'];//note should be the jQuery passed variable
    $number = $_POST['Number'];
    $id = $_POST['gId'];

    $response = $guest->updateGuest($name,$horse,$number,$id);



}
else {
    $response = array();
    $response['status'] = 'error'; // could not create record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Could not update !';
}


echo json_encode($response);