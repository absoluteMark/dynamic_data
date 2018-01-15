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

if ($_POST['hostId']) {

    $host = new \App\Scoreboard\ HOSTS($DB_con);

    //print_r($_POST);

    $hostID = $_POST['hostId'];

    $response = $host->host_detail($hostID);


    $keys = array_keys($response['full-details']);

    //print_r($keys);

    $fields = "";
    foreach ($response['full-details'] as $key => $value) {
        $fields = $fields . $key . ",";
    }
    $fields = $fields . "end" . PHP_EOL;

    $values = "";

    foreach ($response['full-details'] as $key => $value) {
        $values = $values . $value . ",";

    }

    $values = $values . "end";


    $my_file = 'single_host.csv';
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



