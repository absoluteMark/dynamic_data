<?php

header('Content-type: application/json');

require_once 'dbconfig.php';

if ($_POST['eventId']) {


    $eventID = $_POST['eventId'];
    $host = new \App\Scoreboard\ HOSTS($DB_con);

    $response = $host->getHosts($eventID);

    //Output to csv file section
    //For multi-value records, first line fields

    $keys = array_keys($response['hosts']);
    $fields = "";
    for ($i = 0; $i < count($response['hosts']); $i++) {
        foreach ($response['hosts'][$keys[$i]] as $key => $value) {
            if ($key == "n"){
                $fields = $fields . $key . "_" . $i . ",";
            } elseif ($key == "hn") {
                $fields = $fields . $key . "_" . $i . ",";
            } elseif ($key == "sbtl") {
                $fields = $fields . $key . "_" . $i . ",";
            }
        }
    }

    $fields = $fields . "en" . ",";
    $fields = $fields . "loc" . ",";
    $fields = $fields . "end" . PHP_EOL;

    $values = "";

    $en = $response['hosts'][0]['en'];
    $loc = $response['hosts'][0]['loc'];
    $sgn = $response['hosts'][0]['sgn'];

    for ($i = 0; $i < count($response['hosts']); $i++) {
        foreach ($response['hosts'][$keys[$i]] as $key => $value) {
            if ($key == "n"){
                $values = $values . $value . ",";
            } elseif ($key == "hn") {
                $values = $values . $value . ",";
            } elseif ($key == "sbtl") {
                $values = $values . $value . ",";
            }

        }
    }
    $values = $values . $en . ",";
    $values = $values . $loc . ",";
    $values = $values . "end";



    $my_file = 'hosts.csv';
    $this_dir = dirname(__FILE__);
    $parent_dir = realpath($this_dir . '/..');
    $grandparent_dir = realpath($parent_dir . '/..');
    $target_path = $grandparent_dir . '/data_output/' . $my_file;
    $handle = fopen($target_path, 'w') or die('Cannot open file: ' . $my_file);
    fwrite($handle, $fields);
    fwrite($handle, $values);
    fclose($handle);

    //End of Output to CSV Section

} else {
    $response = array();
    $response['status'] = 'error';
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Error.';
}


echo json_encode($response);