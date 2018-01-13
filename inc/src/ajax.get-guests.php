<?php

header('Content-type: application/json');

require_once 'dbconfig.php';

if ($_POST['segmentID']) {

    $_SESSION['segmentID'] = $_POST['segmentID'];
    $segmentID = $_POST['segmentID'];
    $guest = new GUESTS($DB_con);

    $response = $guest->getGuests($segmentID);

    //Output to csv file section
    //For multi-value records, first line fields

    $keys = array_keys($response['guests']);
    $fields = "";
    for ($i = 0; $i < count($response['guests']); $i++) {
        foreach ($response['guests'][$keys[$i]] as $key => $value) {
            $fields = $fields . $key . "_" . $i . ",";
        }
    }
    $fields = $fields . "end" . PHP_EOL;

    $values = "";
    for ($i = 0; $i < count($response['guests']); $i++) {
        foreach ($response['guests'][$keys[$i]] as $key => $value) {
            $values = $values . $value . ",";
        }
    }
    $values = $values . "end";

    $my_file = 'guests.csv';
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