<?php
/**
 * Created by PhpStorm.
 * User: markslater
 * Date: 1/1/18
 * Time: 1:47 PM
 *
 *
 * Class for guests
 * or participants or guests
 * on the show segment
 *
 *
 */

class GUESTS
{
    private $db;

    function __construct($DB_con)
    {
        $this->db = $DB_con;
    }

    public function getGuests($segmentID)


    {

        $response = array();

        $eventID = $_SESSION['event_id'];


        $sql = "

        SELECT c.guest_name,c.horse_name,c.guest_id,e.event_name,e.location,s.segment_name
        FROM guests c
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
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; No guests exist. Create one.';
        }
        return $response;
    }


    public function createGuest($guest_name, $guest_number, $horse_name, $segment_id)
    {

        $response = array();

        $event_id = $_SESSION['event_id'];

        $stmt = $this->db->prepare('INSERT INTO guests(guest_number,guest_name,horse_name,segment_id,event_id) VALUES(:guest_number,:guest_name,:horse_name,:segment_id,:event_id)');

        $stmt->bindParam(':guest_number', $guest_number);
        $stmt->bindParam(':guest_name', $guest_name);
        $stmt->bindParam(':horse_name', $horse_name);
        $stmt->bindParam(':segment_id', $segment_id);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->execute();


        // check for successful creation
        if ($stmt->rowCount() == 1) {
            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Guest created successfully.';
        } else {

            $response['status'] = 'error'; // could not create record
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Could not create guest.';
        }
        return $response;

    }


    public function guest_detail($guestId)
    {

        $response = array();

        $sql = "
        
        SELECT c.guest_name as 'Guest Name', c.horse_name as 'Horse', c.guest_number as 'Number'
        FROM guests c 
        WHERE c.guest_id = :guestId
        
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':guestId' => $guestId));
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


        $sql = "UPDATE guests SET guest_name = :gName, 
            horse_name = :hName, guest_number = :gNumber  
            WHERE guest_id = :gId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':gName', $name, PDO::PARAM_STR);
        $stmt->bindParam(':hName', $horse, PDO::PARAM_STR);
        $stmt->bindParam(':gNumber', $number, PDO::PARAM_STR);
        $stmt->bindParam(':gId', $id, PDO::PARAM_STR);
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

        $sql = "DELETE FROM guests WHERE guest_id =  :gId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':gId', $id, PDO::PARAM_INT);
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