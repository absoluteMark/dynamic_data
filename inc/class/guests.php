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

namespace App\Scoreboard;

use \PDO;  // <--- need by PhpStorm to find Methods of PDO

class GUESTS
{

    /**
     * @var PDO   <--- need by PhpStorm to find Methods of PDO
     */

    private $db;

    function __construct($DB_con)
    {
        $this->db = $DB_con;
    }

    public function getGuests($segmentID)


    {

        $response = array();

        $eventID = $_SESSION['event_id'];

        //print_r($eventID);


        $sql = "

        SELECT c.guest_name AS gn,c.horse_name AS hn,c.guest_id AS gId,
        e.event_name As en,e.location AS loc,s.segment_name AS sgn
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


    public function createGuest($guest_name, $guest_number, $horse_name, $country, $segment_id)
    {

        $response = array();

        $event_id = $_SESSION['event_id'];

        $stmt = $this->db->prepare('INSERT INTO guests(guest_number,guest_name,horse_name,country,segment_id,event_id) VALUES(:guest_number,:guest_name,:horse_name,:country,:segment_id,:event_id)');

        $stmt->bindParam(':guest_number', $guest_number);
        $stmt->bindParam(':guest_name', $guest_name);
        $stmt->bindParam(':horse_name', $horse_name);
        $stmt->bindParam(':country', $country);
        $stmt->bindParam(':segment_id', $segment_id);
        $stmt->bindParam(':event_id', $event_id);

        try {
            $this->db->beginTransaction();
            $stmt->execute();
            $response['r_id'] = $this->db->lastInsertId();
            $this->db->commit();

        } catch(PDOExecption $e) {
            $this->db->rollback();
            print "Error!: " . $e->getMessage() . "</br>";
        }


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
        
        SELECT c.guest_name as 'Guest Name', c.horse_name as 'Horse', 
        c.guest_number as 'Number',c.country as 'Country'
        FROM guests c 
        WHERE c.guest_id = :guestId
        
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':guestId' => $guestId));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $response['guest'] = $res;


        $sql = "
        
        SELECT c.guest_name as 'gn', c.horse_name as 'hn', 
        c.country, c.guest_number as num,
        s.segment_name as sgn,e.event_name as en ,e.location as loc
        FROM guests c 
        JOIN segments s ON c.segment_id = s.segment_id
        JOIN events e ON c.event_id = e.event_id
        WHERE c.guest_id = :guestId
        
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':guestId' => $guestId));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $response['full-details'] = $res;

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

    public function updateGuest($update_array){

        $name = $update_array['Guest_Name'];
        $horse = $update_array['Horse'];
        $number = $update_array['Number'];
        $country = $update_array['Country'];
        $id = $update_array['gId'];


        $sql = "UPDATE guests SET guest_name = :gName, 
            horse_name = :hName, guest_number = :gNumber ,country = :gCountry 
            WHERE guest_id = :gId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':gName', $name, PDO::PARAM_STR);
        $stmt->bindParam(':hName', $horse, PDO::PARAM_STR);
        $stmt->bindParam(':gNumber', $number, PDO::PARAM_STR);
        $stmt->bindParam(':gCountry', $country, PDO::PARAM_STR);
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


    public function findRider($name)
    {

        $response = array();

        $stmt = $this->db->prepare("SELECT * FROM guests WHERE guest_name=:gn");
        $stmt->execute(array(':gn' => $name));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);


        $response['guest'] = $res;

        //print_r($res);

        // check for success
        if ($stmt->rowCount() > 0) {
            $_SESSION['r_id'] = $res['guest_id'];
            $_SESSION['r_name'] = $res['guest_name'];
            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Success.';
        } else {

            $response['status'] = 'error';
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Error.';
        }
        return $response;


    }

}