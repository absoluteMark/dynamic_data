<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST['guest_name']!="") {

    $guest = new GUESTS($DB_con);

    //print_r($_POST);

    $guest_name = $_POST['guest_name'];
    $guest_number = $_POST['guest_number'];
    $horse_name = $_POST['horse_name'];
    $segment_id = $_POST['segmentId'];

    $response = $guest->createGuest($guest_name, $guest_number, $horse_name, $segment_id);

}
else {
    $response = array();
    $response['status'] = 'error'; // could not create record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Must have a name !';
}


echo json_encode($response);