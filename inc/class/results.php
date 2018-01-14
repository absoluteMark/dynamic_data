<?php
/**
 * Created by PhpStorm.
 * User: markslater
 * Date: 1/1/18
 * Time: 1:52 PM
 *
 * Class for score results
 * on each segment
 *
 *
 */

namespace App\Scoreboard;

use \PDO;  // <--- need by PhpStorm to find Methods of PDO


class RESULTS
{
    /**
     * @var PDO   <--- need by PhpStorm to find Methods of PDO
     */

    private $db;

    function __construct($DB_con)
    {
        $this->db = $DB_con;
    }

    public function numScoresPerSegment($sId)
    {

        $sql = "
        
        SELECT COUNT(DISTINCT s.score_name) 
        FROM scores s
        WHERE segment_id = :segmentID
        
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':segmentID', $sId);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_COLUMN);

        //print_r($res);

        return $res;

    }


    public function getResultsbyScore($Id)
    {

        $sql = "
        
        SELECT x.score_result, s.score_name
        FROM results x
        JOIN scores s ON x.score_id = s.score_id
        WHERE x.segment_id = :segmentID
        
        
        
        ";


    }


    public function getAvByName($sId)
    {

        $sql = "
        
        SELECT
          c.guest_name AS gn,c.horse_name AS hn,
          avg(x.score_result) AS av
        FROM results x
        JOIN guests c ON x.guest_id = c.guest_id
        JOIN scores f ON x.score_id = f.score_id
        WHERE x.segment_id = :segmentID
        GROUP BY c.guest_name
        ORDER BY av DESC
            
      
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':segmentID', $sId);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

        //print_r($res);

        $response['guests'] = $res;

        // check for success
        if ($stmt->rowCount() > 0) {

            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Success.';

        } else {

            $response['status'] = 'error';
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; No results exist. Create some.';
        }

        return $response;

    }


    public function getGuestIds($sId)
    {

        $sql = "
        
        SELECT DISTINCT g.guest_id
        FROM results x
        JOIN guests g ON x.guest_id = g.guest_id
        WHERE x.segment_id = :segmentID
        
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':segmentID', $sId);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $res;


    }


    public function getAllResults($sId)
    {

        $sql = "
        
        SELECT 
          x.score_result AS sr,f.score_name AS sn,
          c.guest_name AS gn,c.horse_name AS hn,
          s.segment_name AS sgn,e.event_name AS en,
          x.result_id AS rId,x.segment_id AS sId,x.guest_id AS gId,x.score_id AS scId
        FROM results x
        JOIN guests c ON x.guest_id = c.guest_id
        JOIN events e ON x.event_id = e.event_id
        JOIN segments s ON x.segment_id = s.segment_id
        JOIN scores f ON x.score_id = f.score_id
        WHERE x.segment_id = :segmentID
      
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':segmentID', $sId);
        $stmt->execute();
        $res = $stmt->fetchALL(PDO::FETCH_ASSOC);
        $response['results'] = $res;
        $response['unique'] = $this->numScoresPerSegment($sId);
        $response['names'] = $this->getGuestIds($sId);

        $keys = array_keys($response['names']);

        print_r($keys);

        foreach ($response['names'] as $key => $value) {
            print_r($this->getResultDetails($value));
        }


        // check for success
        if ($stmt->rowCount() > 0) {

            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Success.';

        } else {

            $response['status'] = 'error';
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; No results exist. Create some.';
        }


        return $response;

    }


    public function getResultDetails($gId)

    {

        $sql = "
        SELECT 
          f.score_name,x.score_result,x.result_id,x.score_id,
          g.guest_name, g.horse_name,
          e.event_name, s.segment_name
        FROM results x
        JOIN scores f ON x.score_id = f.score_id
        JOIN guests g ON x.guest_id = g.guest_id
        JOIN events e ON x.event_id = e.event_id
        JOIN segments s ON x.segment_id = s.segment_id
        WHERE x.guest_id = :guestID    
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':guestID', $gId);
        $stmt->execute();
        $res = $stmt->fetchALL(PDO::FETCH_ASSOC);
        $response['results'] = $res;

        $sql = "
        SELECT 
          g.guest_name,g.horse_name
        FROM guests g
        WHERE g.guest_id = :guestID    
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':guestID', $gId);
        $stmt->execute();
        $res = $stmt->fetchALL(PDO::FETCH_ASSOC);
        $response['guest'] = $res;

        //print_r($res);

        // check for success
        if ($stmt->rowCount() > 0) {

            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Success.';

        } else {

            $response['status'] = 'error';
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; No results exist. Create some.';
        }

        return $response;
    }


    public function insertResults($results_array)
    {

        $eventID = $_SESSION['event_id'];
        $segmentID = $_SESSION['segment_id'];
        $guestID = $_SESSION['guest_id'];

        $sql = "
        INSERT INTO results(score_result,score_id,event_id,segment_id,guest_id) 
        VALUES(:score_result,:score_id,:event_id,:segment_id,:guest_id)
    
        ";

        $stmt = $this->db->prepare($sql);
        foreach ($results_array as $result => $value) {
            $scoreID = $result;
            $score_result = $value;
            $stmt->bindParam(':score_result', $score_result);
            $stmt->bindParam(':score_id', $scoreID);
            $stmt->bindParam(':event_id', $eventID);
            $stmt->bindParam(':segment_id', $segmentID);
            $stmt->bindParam(':guest_id', $guestID);
            $stmt->execute();

        }

        $response['segment_id'] = $segmentID;

        if ($stmt->rowCount() >= 1) {

            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Success.';
        } else {

            $response['status'] = 'error';
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Some error occurred.';
        }

        return $response;

    }


    public function updateResults($data)
    {
        $count = count($data) - 2; //number of results to update
        $response = array();
        $upd = array();
        $keys = array_keys($data);
        $values = array_values($data);
        $i = 0;
        foreach ($keys as $key => $value) {
            $upd['keys'][$i] = $value;
            $i++;
        }
        $i = 0;
        foreach ($values as $key => $value) {
            $upd['values'][$i] = $value;
            $i++;
        }

        for ($i = 0; $i < $count; $i++) {


            $sql = "UPDATE results SET score_result = :sr
            WHERE result_id = :rId";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':sr', $upd['values'][$i], PDO::PARAM_STR);
            $stmt->bindParam(':rId', $upd['keys'][$i], PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $response['status'][$i] = 'success';
                $response['message'][$i] = '<span class="fas fa-check-circle"></span> &nbsp; Update successful.';
            } else {
                $response['status'][$i] = 'error'; // could not update record
                $response['message'][$i] = '<span class="fas fa-info-circle"></span> &nbsp; Nothing changed.';
            }

        }
        return $response;

    }


    public function getScoresBySeg($gId,$segmentId)
    {

        $response = array();

        $_SESSION['segment_id'] = $segmentId;
        $_SESSION['guest_id'] = $gId;


        $sql = "
        
        SELECT sc.score_name, score_id
        FROM scores sc 
        WHERE sc.segment_id = :sId;
        
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':sId', $segmentId, PDO::PARAM_STR);
        $stmt->execute();
        $res = $stmt->fetchALL(PDO::FETCH_ASSOC);
        $response['scores'] = $res;

        $sql = "
        SELECT 
          g.guest_name,g.horse_name
        FROM guests g
        WHERE g.guest_id = :guestID    
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':guestID', $gId);
        $stmt->execute();
        $res = $stmt->fetchALL(PDO::FETCH_ASSOC);
        $response['guest'] = $res;

        if ($stmt->rowCount() > 0) {
            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Update successful.';
        } else {
            $response['status'] = 'error'; // could not update record
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Nothing changed.';
        }

        return $response;


    }


}

