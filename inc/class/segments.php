<?php
/**
 * Created by PhpStorm.
 * User: markslater
 * Date: 12/31/17
 * Time: 7:40 PM
 */

namespace App\Scoreboard;

use \PDO;  // <--- need by PhpStorm to find Methods of PDO

class SEGMENTS
{
    /**
     * @var PDO   <--- need by PhpStorm to find Methods of PDO
     */

    private $db;

    function __construct($DB_con)
    {
        $this->db = $DB_con;
    }

    public function getSegments()
    {

        $eventID = $_SESSION['event_id'];

        $sql = "
        
        SELECT s.segment_id, s.segment_name, e.event_name, 
        e.location, TIME_FORMAT(s.start_time,'%H:%i') as start_time ,
        e.event_id
        FROM segments s
        JOIN events e ON s.event_id = e.event_id 
        WHERE s.event_id=:eventID 
        ORDER BY start_time
        
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':eventID' => $eventID));
        $res = $stmt->fetchALL(PDO::FETCH_ASSOC);

        $response['segments'] = $res;

        //print_r($res);

        // check for success
        if ($stmt->rowCount() > 0) {

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

        $stmt = $this->db->prepare("SELECT segment_name as 'Segment Name', TIME_FORMAT(start_time,'%H:%i') as 'Start Time' FROM segments WHERE segment_id=:segmentId");
        $stmt->execute(array(':segmentId' => $segmentId));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $response['events'] = $res;

        //print_r($res);

        // check for success
        if ($stmt->rowCount() == 1) {

            $_response['segment_name'] = $res['segment_name'];
            $_response['segment_id'] = $res['segment_id'];

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

        $response['added'] = $res;

        // check for successful creation
        if ($stmt->rowCount() == 1) {
            $response['rs_id'] = $this->db->lastInsertId();
            $response['rs_name'] = $segment_name;
            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Segment created successfully.';
        } else {

            $response['status'] = 'error'; // could not create record
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Could not create segment.';
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
            $response['status'] = 'error'; // could not update record
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Nothing changed.';
        }
        return $response;


    }

    public function deleteSegment($segment_id) {

        $sql = "DELETE FROM segments WHERE segment_id =  :segment_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':segment_id', $segment_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Purge successful.';
        } else {
            $response['status'] = 'error'; // could not delete record
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Nothing changed.';
        }
        return $response;


    }

    /**
     * @param $name
     * @param $eid
     * @return array
     */
    public function findSegment($name, $eid)
    {

        $response = array();

        $stmt = $this->db->prepare("SELECT * FROM segments WHERE segment_name = :sgn AND event_id = :eid");
        $stmt->execute(array(':sgn' => $name, ':eid' => $eid));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);


        $response['segment'] = $res;

        //print_r($res);

        // check for success
        if ($stmt->rowCount() > 0) {
            $_SESSION['rs_id'] = $res['segment_id'];
            $_SESSION['rs_name'] = $res['segment_name'];
            $response['status'] = 'Old Segment';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Segment already exists.';
        } else {
            $_SESSION['rs_id'] = "";
            $_SESSION['rs_name'] = "";
            $response['status'] = 'New Segment';
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Segment does not exist.';
        }
        return $response;


    }


}