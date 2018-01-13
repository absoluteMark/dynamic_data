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

class SCORES
{
    private $db;

    function __construct($DB_con)
    {
        $this->db = $DB_con;
    }

    public function getScores($sId)

    {

        $response = array();

        $eventID = $_SESSION['event_id'];

        $stmt = $this->db->prepare("SELECT s.score_name FROM scores s WHERE event_id=:eventID && segment_id=:segmentID");
        $stmt->execute(array(':eventID' => $eventID, ':segmentID' => $sId));
        $res = $stmt->fetchALL(PDO::FETCH_ASSOC);

        $response['scores'] = $res;

        //print_r($res);

        // check for success
        if ($stmt->rowCount() > 0) {

            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Success.';
        } else {

            $response['status'] = 'error';
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; No scores exist. Create one.';
        }
        return $response;
    }


    public function createScore($name)
    {

        $response = array();

        $segment_id = $_SESSION['segment_id'];
        $event_id = $_SESSION['event_id'];

        $stmt = $this->db->prepare('INSERT INTO scores(score_name,segment_id,event_id) VALUES(:score_name,:segment_id,:event_id)');


        $stmt->bindParam(':score_name', $name);
        $stmt->bindParam(':segment_id', $segment_id);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->execute();


        // check for successful creation
        if ($stmt->rowCount() == 1) {
            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Score created successfully.';
        } else {

            $response['status'] = 'error'; // could not create record
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Could not create score.';
        }
        return $response;

    }
}