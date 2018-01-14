<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST['guestId']) {

    $result = new \App\Scoreboard\ RESULTS($DB_con);

    $guestId = $_POST['guestId'];
    $response = $result->getResultDetails($guestId);

    //Output to csv file section
    //For multi-value records, first line key fields, second line key values
    $keys = array_keys($response['results']);

    $fields = "";
    for ($i = 0; $i < count($response['results']); $i++) {
        foreach ($response['results'][$keys[$i]] as $key => $value) {
            if ($key == "score_result") {
                $fields = $fields . "sr_" . $i . ",";
            } elseif ($key == "score_name") {
                $fields = $fields . "sn_" . $i . ",";
            }
        }
    }
    $fields = $fields . "sum" . ",";
    $fields = $fields . "av" . ",";
    $fields = $fields . "cn" . ",";
    $fields = $fields . "hn" . ",";
    $fields = $fields . "en" . ",";
    $fields = $fields . "sgn" . ",";
    $fields = $fields . "end" . PHP_EOL;

    $values = "";
    $total = 0;
    $average = 0;
    $count = 0;

    $cn = $response['results'][0]['guest_name'];
    $hn = $response['results'][0]['horse_name'];
    $en = $response['results'][0]['event_name'];
    $sgn = $response['results'][0]['segment_name'];


    for ($i = 0; $i < count($response['results']); $i++) {
        foreach ($response['results'][$keys[$i]] as $key => $value) {
            if ($key == "score_result") {
                $values = $values . $value . ",";
                $total = $total + $value;
                $count = $count + 1;
                $average = round($total / $count,3);
            } elseif ($key == "score_name") {
                $values = $values . $value . ",";
            }
        }
    }
    $values = $values . $total . ",";
    $values = $values . $average . ",";
    $values = $values . $cn . ",";
    $values = $values . $hn . ",";
    $values = $values . $en . ",";
    $values = $values . $sgn . ",";
    $values = $values . "end";

    $my_file = 'single_result.csv';
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