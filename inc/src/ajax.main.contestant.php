<?php


/**
 *
 * Brings up the show segment
 * selected
 *
 *
 *
 */

header('Content-type: application/json');

require_once 'dbconfig.php';

if ($_POST['guestId']) {

    $contestant = new CONTESTANTS($DB_con);

    //print_r($_POST);

    $guestId = $_POST['guestId'];

    $response = $contestant->guest_detail($guestId);

} else {
    $response = array();
    $response['status'] = 'error';
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Error.';
}

echo json_encode($response);


?>
