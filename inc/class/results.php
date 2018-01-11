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

class RESULTS
{

    private $db;

    function __construct($DB_con)
    {
        $this->db = $DB_con;
    }

    public function getResultDetails($contestantId)

    {

        $sql = "
        SELECT 
          x.score_result,f.scorename_name,
          c.contestant_name,c.horse_name,
          s.segment_name,e.event_name,
          x.result_id,x.segment_id,x.contestant_id,x.scorename_id
        FROM results x
        JOIN contestants c ON x.contestant_id = c.contestant_id
        JOIN events e ON x.event_id = e.event_id
        JOIN segments s ON x.segment_id = s.segment_id
        JOIN scorenames f ON x.scorename_id = f.scorename_id
        WHERE x.contestant_id = :contestantID    
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':contestantID', $contestantId);
        $stmt->execute();
        $res = $stmt->fetchALL(PDO::FETCH_ASSOC);
        $response['results'] = $res;

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
        $contestantID = $_SESSION['contestant_id'];

        foreach ($results_array as $result => $value) {
            $scorenameID = $result;
            $score_result = $value;
            $stmt = $this->db->prepare('INSERT INTO results(score_result,scorename_id,event_id,segment_id,contestant_id) VALUES(:score_result,:scorename_id,:event_id,:segment_id,:contestant_id)');
            $stmt->bindParam(':score_result', $score_result);
            $stmt->bindParam(':scorename_id', $scorenameID);
            $stmt->bindParam(':event_id', $eventID);
            $stmt->bindParam(':segment_id', $segmentID);
            $stmt->bindParam(':contestant_id', $contestantID);
            $stmt->execute();

        }

        $response['segment_id']=$segmentID;

        if ($stmt->rowCount() >= 1) {

            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Success.';
        } else {

            $response['status'] = 'error';
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Some error occurred.';
        }

        return $response;

    }


    public function updateResults(){




    }

}