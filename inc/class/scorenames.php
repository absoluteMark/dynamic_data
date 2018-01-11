<?php
/**
 * Created by PhpStorm.
 * User: markslater
 * Date: 1/1/18
 * Time: 1:49 PM
 *
 *
 * Class for scoring categories
 * used for each segment
 *
 *
 */

class SCORENAMES
{
    private $db;

    function __construct($DB_con)
    {
        $this->db = $DB_con;
    }

    public function getScorenames()

    {

        $response = array();

        $eventID = $_SESSION['event_id'];
        $segmentID = $_SESSION['segment_id'];

        $stmt = $this->db->prepare("SELECT * FROM scorenames WHERE event_id=:eventID && segment_id=:segmentID");
        $stmt->execute(array(':eventID' => $eventID, ':segmentID' => $segmentID));
        $res = $stmt->fetchALL(PDO::FETCH_ASSOC);

        $response['events'] = $res;

        //print_r($res);

        // check for success
        if ($stmt->rowCount() >= 1) {

            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Success.';
        } else {

            $response['status'] = 'error';
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; No scorenames exist. Create one.';
        }
        return $response;
    }


    public function createScorename($scorename_name)
    {

        $response = array();

        $segment_id = $_SESSION['segment_id'];
        $event_id = $_SESSION['event_id'];

        $stmt = $this->db->prepare('INSERT INTO scorenames(scorename_name,segment_id,event_id) VALUES(:scorename_name,:segment_id,:event_id)');


        $stmt->bindParam(':scorename_name', $scorename_name);
        $stmt->bindParam(':segment_id', $segment_id);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->execute();


        // check for successful creation
        if ($stmt->rowCount() == 1) {
            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Scorename created successfully.';
        } else {

            $response['status'] = 'error'; // could not create record
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Could not create scorename.';
        }
        return $response;

    }
}