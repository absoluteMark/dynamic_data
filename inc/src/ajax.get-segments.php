<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST) {

    $segment = new \App\Scoreboard\ SEGMENTS($DB_con);
    $response = $segment->getSegments();

    //Output to csv file section
    //For multi-value records, first line fields

    $keys = array_keys($response['segments']);
    $fields = "";

    for ($i = 0; $i < count($response['segments']); $i++) {
        foreach ($response['segments'][$keys[$i]] as $key => $value) {
            if ($key == "segment_name") {
                $fields = $fields . "sgn_" . $i . ",";
            } elseif ($key == "start_time") {
                $fields = $fields . "st_" . $i . ",";
            }
        }
    }

    $fields = $fields . "en" . ",";
    $fields = $fields . "loc" . ",";
    $fields = $fields . "end" . PHP_EOL;

    $values = "";

    $en = $response['segments'][0]['event_name'];
    $loc = $response['segments'][0]['location'];

    for ($i = 0; $i < count($response['segments']); $i++) {
        foreach ($response['segments'][$keys[$i]] as $key => $value) {
            if ($key == "segment_name") {
                $values = $values . $value . ",";
            } elseif ($key == "start_time") {
                $time = (date_create($value));
                $value = date_format($time, 'g:i');
                $values = $values . $value . ",";
            }

        }
    }

    $values = $values . $en . ",";
    $values = $values . $loc . ",";
    $values = $values . "end";

    $my_file = 'segments.csv';
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