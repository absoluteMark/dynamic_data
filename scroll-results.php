<?php

session_start();

require_once 'inc/src/dbconfig.php';

echo "<h2>Session Values</h2>";

foreach ($_SESSION as $key => $value) {

    echo $key . " = " . $value . "<br />";

}

if (isset($_SESSION['rs_id'])) {

    $segmentID = $_SESSION['rs_id'];
    $results = new \App\Scoreboard\ RESULTS($DB_con);
    $guests = new \App\Scoreboard\ GUESTS($DB_con);

    $response = $results->getAllResults($segmentID,6);

    echo "<h2>Riders with Results:</h2>";

    foreach ($response['names'] as $value) {

        $details = $guests->guest_detail($value);
        print_r($details['guest']);
        echo $value . "<br />";

    }

} else {

    $response = array();
    $response['status'] = 'Error';
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; No Segment Set.';

    echo "<h2>Error Response</h2>";

    foreach ($response as $key => $value) {

        echo $key . " = " . $value . "<br />";
    }


}




