<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST['scorename_name']!="") {

    $scorename = new SCORENAMES($DB_con);

    $scorename_name = $_POST['scorename_name'];


    $response = $scorename->createScorename($scorename_name);

}
else {
    $response = array();
    $response['status'] = 'error'; // could not create record
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Must have a name !';
}


echo json_encode($response);