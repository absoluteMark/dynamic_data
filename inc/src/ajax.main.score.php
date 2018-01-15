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

if ($_POST['scoreId']) {

    $score = new \App\Scoreboard\ SCORES($DB_con);

    //print_r($_POST);

    $scoreId = $_POST['scoreId'];

    $response = $score->score_detail($scoreId);

} else {
    $response = array();
    $response['status'] = 'error';
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Error.';
}

echo json_encode($response);



