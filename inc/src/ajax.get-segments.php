<?php

header('Content-type: application/json');

require_once 'dbconfig.php';


if ($_POST) {

    $segment = new SEGMENTS($DB_con);
    $response = $segment->getSegments();

    //Output to csv file section
    //For multi-value records, first line fields

    $keys2 = array_keys($response['segments']);
    for ($i = 0; $i < count($response['segments']); $i++) {
        foreach ($response['segments'][$keys2[$i]] as $key => $value) {
            $fields = $fields . $key . "_" . $i . ",";
        }
    }

    $keys3 = array_keys($response['event']);
    for ($i = 0; $i < count($response['event']); $i++) {
        foreach ($response['event'][$keys3[$i]] as $key => $value) {
            $fields = $fields . $key . "_" . $i . ",";
        }
    }

    $fields = $fields . "end" . PHP_EOL;

    $values = "";

    for ($i = 0; $i < count($response['segments']); $i++) {
        foreach ($response['segments'][$keys2[$i]] as $key => $value) {
            if ($key == "start_time") {
                $time = (date_create($value));
                //print_r($time);
                $value = date_format($time,'g:i');

            }

            $values = $values . $value . ",";
        }
    }

    for ($i = 0; $i < count($response['event']); $i++) {
        foreach ($response['event'][$keys3[$i]] as $key => $value) {
            $values = $values . $value . ",";
        }
    }


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