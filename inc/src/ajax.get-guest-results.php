<?php

header('Content-type: application/json');

require_once 'dbconfig.php';

if ($_POST['segmentId']) {


    $segmentID = $_POST['segmentId'];
    $results = new \App\Scoreboard\ RESULTS($DB_con);

    $response = $results->getAvByName($segmentID);
    $results->getAllResults($segmentID,12);


    //Output to csv file section
    //For multi-value records, first line fields

    $keys = array_keys($response['guests']);
    $fields = "";
    for ($i = 0; $i < count($response['guests']); $i++) {
        foreach ($response['guests'][$keys[$i]] as $key => $value) {
            if ($key == "gn"){
                $fields = $fields . $key . "_" . $i . ",";
            } elseif ($key == "hn") {
                $fields = $fields . $key . "_" . $i . ",";
            } elseif ($key == "av") {
                $fields = $fields . $key . "_" . $i . ",";
            }

        }
    }

    $fields = $fields . "en" . ",";
    $fields = $fields . "loc" . ",";
    $fields = $fields . "sgn" . ",";
    $fields = $fields . "end" . PHP_EOL;

    $values = "";

    $en = $response['guests'][0]['en'];
    $loc = $response['guests'][0]['loc'];
    $sgn = $response['guests'][0]['sgn'];

    for ($i = 0; $i < count($response['guests']); $i++) {
        foreach ($response['guests'][$keys[$i]] as $key => $value) {
            if ($key == "gn"){
                $values = $values . $value . ",";
            } elseif ($key == "hn") {
                $values = $values . $value . ",";
            } elseif ($key == "av") {
                $values = $values . $value . ",";
            }
        }
    }
    $values = $values . $en . ",";
    $values = $values . $loc . ",";
    $values = $values . $sgn . ",";
    $values = $values . "end";



    $my_file = 'results_table.csv';
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