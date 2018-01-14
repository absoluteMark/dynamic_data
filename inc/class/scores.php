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

namespace App\Scoreboard;

use \PDO;  // <--- need by PhpStorm to find Methods of PDO

class SCORES
{
    /**
     * @var PDO   <--- need by PhpStorm to find Methods of PDO
     */

    private $db;

    function __construct($DB_con)
    {
        $this->db = $DB_con;
    }

    public function getScores($sId)

    {

        $response = array();

        $eventID = $_SESSION['event_id'];

        $stmt = $this->db->prepare("SELECT s.score_id, s.score_name FROM scores s WHERE event_id=:eventID && segment_id=:segmentID");
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


    public function createScore($name,$sId)
    {

        $response = array();

        $event_id = $_SESSION['event_id'];

        $stmt = $this->db->prepare('INSERT INTO scores(score_name,segment_id,event_id) VALUES(:score_name,:segment_id,:event_id)');


        $stmt->bindParam(':score_name', $name);
        $stmt->bindParam(':segment_id', $sId);
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


    public function score_detail($scoreId)
    {

        $response = array();

        $sql = "
        
        SELECT s.score_name as 'Score Name'
        FROM scores s 
        WHERE s.score_id = :scoreId
        
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(':scoreId' => $scoreId));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $response['score'] = $res;

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

    public function updateScore($name,$id){


        $sql = "UPDATE scores SET score_name = :scName   
            WHERE score_id = :scId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':scName', $name, PDO::PARAM_STR);
        $stmt->bindParam(':scId', $id, PDO::PARAM_STR);
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

    public function deleteScore($id) {

        $sql = "DELETE FROM scores WHERE score_id =  :scId";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':scId', $id, PDO::PARAM_INT);
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