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
        $response['guests'] = $res;


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


    public function createContestant($contestant_name, $contestant_number, $horse_name, $segment_id)
    {

        $response = array();

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


    public function guest_detail($contestantId)
    {

        $response = array();
        
        $sql = "
        
        SELECT c.contestant_name as 'Guest Name', c.horse_name as 'Horse', c.contestant_number as 'Number'
        FROM contestants c 
        WHERE c.contestant_id = :contestantId
        
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':contestantId' => $contestantId));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $response['guest'] = $res;

        //print_r($res);

        // check for success
        if ($stmt->rowCount() == 1) {

            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Success.';
        } else {

            $response['status'] = 'error';
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Error.';
        }
        return $response;

    }

    public function updateGuest($name,$horse,$number,$id){


        $sql = "UPDATE contestants SET contestant_name = :cName, 
            horse_name = :hName, contestant_number = :cNumber  
            WHERE contestant_id = :cId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cName', $name, PDO::PARAM_STR);
        $stmt->bindParam(':hName', $horse, PDO::PARAM_STR);
        $stmt->bindParam(':cNumber', $number, PDO::PARAM_STR);
        $stmt->bindParam(':cId', $id, PDO::PARAM_STR);
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

    public function deleteGuest($id) {

        $sql = "DELETE FROM contestants WHERE contestant_id =  :cId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cId', $id, PDO::PARAM_INT);
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
    
}