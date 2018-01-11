<?php
/**
 * Created by PhpStorm.
 * User: markslater
 * Date: 1/1/18
 * Time: 1:47 PM
 *
 *
 * Class for contestants
 * or participants or guests
 * on the show segment
 *
 *
 */

class CONTESTANTS
{
    private $db;

    function __construct($DB_con)
    {
        $this->db = $DB_con;
    }

    public function getContestants($segmentID)


    {

        $response = array();

        $eventID = $_SESSION['event_id'];
        //$segmentID = $_SESSION['segment_id'];


        $sql = "

        SELECT c.contestant_name,c.horse_name,c.contestant_id,e.event_name,e.location,s.segment_name
        FROM contestants c
        JOIN events e ON c.event_id = e.event_id
        JOIN segments s ON c.segment_id = s.segment_id
        WHERE c.event_id=:eventID && c.segment_id=:segmentID
        
        
        ";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':eventID' => $eventID, ':segmentID' => $segmentID));
        $res = $stmt->fetchALL(PDO::FETCH_ASSOC);
        $response['joined'] = $res;


        //print_r($res);

        // check for success
        if ($stmt->rowCount() >= 1) {

            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Success.';
        } else {

            $response['status'] = 'error';
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; No contestants exist. Create one.';
        }
        return $response;
    }


    public function createContestant($contestant_name, $contestant_number, $horse_name)
    {

        $response = array();

        $segment_id = $_SESSION['segment_id'];
        $event_id = $_SESSION['event_id'];

        $stmt = $this->db->prepare('INSERT INTO contestants(contestant_number,contestant_name,horse_name,segment_id,event_id) VALUES(:contestant_number,:contestant_name,:horse_name,:segment_id,:event_id)');

        $stmt->bindParam(':contestant_number', $contestant_number);
        $stmt->bindParam(':contestant_name', $contestant_name);
        $stmt->bindParam(':horse_name', $horse_name);
        $stmt->bindParam(':segment_id', $segment_id);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->execute();


        // check for successful creation
        if ($stmt->rowCount() == 1) {
            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Contestant created successfully.';
        } else {

            $response['status'] = 'error'; // could not create record
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Could not create contestant.';
        }
        return $response;

    }


}