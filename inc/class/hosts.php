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

class HOSTS
{

    /**
     * @var PDO   <--- need by PhpStorm to find Methods of PDO
     */

    private $db;

    function __construct($DB_con)
    {
        $this->db = $DB_con;
    }

    public function getHosts($eventID)


    {

        $response = array();


        $sql = "

        SELECT h.name AS n,h.horse AS hn,h.host_id AS hId,
        h.subtitle AS sbtl,
        e.event_name As en,e.location AS loc
        FROM hosts h
        JOIN events e ON h.event_id = e.event_id
        WHERE h.event_id=:eventID
        
        
        ";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':eventID' => $eventID));
        $res = $stmt->fetchALL(PDO::FETCH_ASSOC);
        $response['hosts'] = $res;


        //print_r($res);

        // check for success
        if ($stmt->rowCount() >= 1) {

            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Success.';
        } else {

            $response['status'] = 'error';
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; No hosts exist. Create one.';
        }
        return $response;
    }


    public function createHost($name, $subtitle, $horse, $event_id)
    {

        $response = array();

        $stmt = $this->db->prepare('INSERT INTO hosts(name,subtitle,horse,event_id) VALUES(:hn,:hs,:horse,:event_id)');


        $stmt->bindParam(':hn', $name);
        $stmt->bindParam(':hs', $subtitle);
        $stmt->bindParam(':horse', $horse);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->execute();


        // check for successful creation
        if ($stmt->rowCount() == 1) {
            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Host created successfully.';
        } else {

            $response['status'] = 'error'; // could not create record
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Could not create host.';
        }
        return $response;

    }


    public function host_detail($hostID)
    {

        $response = array();

        $sql = "
        
        SELECT h.name as 'Host Name', h.subtitle as 'Subtitle', h.horse as 'Horse'
        FROM hosts h 
        WHERE h.host_id = :hostId
        
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':hostId' => $hostID));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $response['host'] = $res;


        $sql = "
        
        SELECT h.name as 'hn', h.subtitle as 'hs', h.horse as 'hhn',
        e.event_name as 'en',e.location as 'loc'
        FROM hosts h 
        JOIN events e ON h.event_id = e.event_id
        WHERE h.host_id = :hostId
        
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':hostId' => $hostID));
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

    public function updateHost($name,$horse,$subtitle,$id){


        $sql = "UPDATE hosts SET name = :hName, 
            horse = :hHorse, subtitle = :hSubtitle  
            WHERE host_id = :hId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':hName', $name, PDO::PARAM_STR);
        $stmt->bindParam(':hHorse', $horse, PDO::PARAM_STR);
        $stmt->bindParam(':hSubtitle', $subtitle, PDO::PARAM_STR);
        $stmt->bindParam(':hId', $id, PDO::PARAM_STR);
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

    public function deleteHost($id) {

        $sql = "DELETE FROM hosts WHERE host_id =  :hId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':hId', $id, PDO::PARAM_INT);
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