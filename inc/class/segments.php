<?php
/**
 * Created by PhpStorm.
 * User: markslater
 * Date: 12/31/17
 * Time: 7:40 PM
 */

class SEGMENTS
{
    private $db;

    function __construct($DB_con)
    {
        $this->db = $DB_con;
    }

    public function getSegments()
    {

        //$response = array();

        $eventID = $_SESSION['event_id'];

        $stmt = $this->db->prepare("SELECT segment_id, segment_name, start_time FROM segments WHERE event_id=:eventID ORDER BY start_time");
        $stmt->execute(array(':eventID' => $eventID));
        $res1 = $stmt->fetchALL(PDO::FETCH_ASSOC);

        $stmt = $this->db->prepare("SELECT event_name, location FROM events WHERE event_id=:eventID");
        $stmt->execute(array(':eventID' => $eventID));
        $res2 = $stmt->fetchALL(PDO::FETCH_ASSOC);

        $response['segments'] = $res1;
        $response['event'] = $res2;

        //print_r($res);

        // check for success
        if ($stmt->rowCount() >= 1) {

            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Success.';
        } else {

            $response['status'] = 'error';
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; No segments exist. Create one.';
        }
        return $response;
    }

    public function segment_detail($segmentId)
    {

        $response = array();

        $stmt = $this->db->prepare("SELECT * FROM segments WHERE segment_id=:segmentId");
        //$stmt->bindParam(':segmentId', $segmentId);
        $stmt->execute(array(':segmentId' => $segmentId));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);


        $response['events'] = $res;

        //print_r($res);

        // check for success
        if ($stmt->rowCount() == 1) {

            $_SESSION['segment_name'] = $res['segment_name'];
            $_SESSION['segment_id'] = $res['segment_id'];

            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Success.';
        } else {

            $response['status'] = 'error';
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Error.';
        }
        return $response;

    }

    public function createSegment($segment_name, $event_id, $start_time)
    {

        $response = array();

        //print_r($segment_name);
        //print_r($event_id);

        $stmt = $this->db->prepare('INSERT INTO segments(segment_name,event_id,start_time) VALUES(:segment_name,:event_id,:start_time)');
        $stmt->bindParam(':segment_name', $segment_name);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->bindParam(':start_time', $start_time);
        $res = $stmt->execute();

        $response['events'] = $res;

        // check for successful creation
        if ($stmt->rowCount() == 1) {
            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Segment created successfully.';
        } else {

            $response['status'] = 'error'; // could not create record
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Could not create class.';
        }
        return $response;

    }

    public function updateSegment($segmentName,$startTime,$segmentID){


        $sql = "UPDATE segments SET segment_name = :segmentName, 
            start_time = :startTime  
            WHERE segment_id = :segment_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':segmentName', $segmentName, PDO::PARAM_STR);
        $stmt->bindParam(':startTime', $startTime, PDO::PARAM_STR);
        $stmt->bindParam(':segment_id', $segmentID, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Update successful.';
        } else {
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Nothing changed.';
        }
        return $response;


    }


        $stmt->execute();



    }


}