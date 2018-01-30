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


    public function getAvByName($sId)
    {

        $sql = "
        
        SELECT
          c.guest_name AS gn,c.horse_name AS hn,
          CAST(AVG(x.score_result) AS DECIMAL(10,3)) AS av,
          e.event_name AS en, e.location AS loc, s.segment_name AS sgn
        FROM results x
        JOIN guests c ON x.guest_id = c.guest_id
        JOIN scores f ON x.score_id = f.score_id
        JOIN events e ON x.event_id = e.event_id
        JOIN segments s ON x.segment_id = s.segment_id
        WHERE x.segment_id = :segmentID
        GROUP BY c.guest_id
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


    public function getGuestIds($sId,$n = 6)
    {

        $sql = "
        
        SELECT DISTINCT g.guest_id
        FROM results x
        JOIN guests g ON x.guest_id = g.guest_id
        WHERE x.segment_id = :segmentID
        ORDER BY g.guest_id DESC
        LIMIT $n
        
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':segmentID', $sId);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return $res;


    }


    public function getAllResults($sId,$x)
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
        //How many results
        $response['names'] = $this->getGuestIds($sId,$x);

        $num = 1;
        $fields = "";

        foreach ($response['names'] as $value) {

            $fields = $fields . "r" . $num . ",";
            $fields = $fields . "h" . $num . ",";

            $names = $this->getResultName($value);

            foreach ($names as $sn) {
                $res_keys = array();
                $res_keys = array_merge($res_keys, $sn);

                foreach ($res_keys as $keysn){

                    $fields = $fields . $keysn . $num . ",";

                }

            }

            $fields = $fields . "av" . $num . ",";
            $num++;

        }

        $fields = $fields . "en" . ",";
        $fields = $fields . "loc" . ",";
        $fields = $fields . "arena" . ",";
        $fields = $fields . "sgn" . ",";
        $fields = $fields . "end" . PHP_EOL;

        $values = "";

        foreach ($response['names'] as $value) {

            $details = $this->getResultDetails($value);

            $values = $values . $details['results'][0]['guest_name'] . ",";
            $values = $values . $details['results'][0]['horse_name'] . ",";

            $results = $this->getResult($value);

            $total = 0;

            foreach ($results as $sr) {
                $res_val = array();
                $res_val = array_merge($res_val, $sr);

                foreach ($res_val as $valsr){

                    $values = $values . $valsr . ",";
                    $total = $total + $valsr;

                }

            }

            $average = round($total / $response['unique'],3);
            $values = $values . $average . ",";
        }

        $values = $values . $_SESSION['event_name'] . ",";
        $values = $values . $details['results'][0]['location'] . ",";
        $values = $values . $_SESSION['rs_name'] . ",";
        $values = $values . $details['results'][0]['segment_name'] . ",";
        $values = $values . "end";


        $my_file = 'results_scroll.csv';
        $this_dir = dirname(__FILE__);
        $parent_dir = realpath($this_dir . '/..');
        $grandparent_dir = realpath($parent_dir . '/..');
        $target_path = $grandparent_dir . '/data_output/' . $my_file;
        $handle = fopen($target_path, 'w') or die('Cannot open file: ' . $my_file);
        fwrite($handle, $fields);
        fwrite($handle, $values);
        fclose($handle);


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


    public function getResult($gId)

    {

        $sql = "
        SELECT 
          x.score_result AS sr
        FROM results x
        WHERE x.guest_id = :guestID    
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':guestID', $gId);
        $stmt->execute();
        $res = $stmt->fetchALL(PDO::FETCH_COLUMN,0);
        $response[] = $res;


        return $response;
    }

    public function getResultName($gId)

    {

        $sql = "
        SELECT 
          f.score_name AS sn
        FROM results x
        JOIN scores f ON x.score_id = f.score_id
        WHERE x.guest_id = :guestID    
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':guestID', $gId);
        $stmt->execute();
        $res = $stmt->fetchALL(PDO::FETCH_COLUMN,0);

        $response[] = $res;


        return $response;
    }


    public function getResultDetails($gId)

    {

        $sql = "
        SELECT 
          f.score_name,x.score_result,x.result_id,x.score_id,
          g.guest_name, g.horse_name,
          e.event_name, s.segment_name, e.location
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
          g.guest_name AS gn,g.horse_name AS hn
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

        $datafields = array('event_id', 'segment_id', 'guest_id', 'score_id', 'score_result');

        $eventID = $_SESSION['event_id'];
        $segmentID = $results_array['segmentId'];
        $guestID = $results_array['guestId'];


        foreach ($results_array as $result => $value) {
            if ($result == "segmentId" || $result == "guestId") {
                ;
            } else {
                $data[] = array(
                    'event_id' => $eventID,
                    'segment_id' => $segmentID,
                    'guest_id' => $guestID,
                    'score_id' => $result,
                    '$score_result' => $value
                );
            }
        }


        function placeholders($text, $count = 0, $separator = ",")
        {
            $result = array();
            if ($count > 0) {
                for ($x = 0; $x < $count; $x++) {
                    $result[] = $text;
                }
            }

            return implode($separator, $result);
        }

        $this->db->beginTransaction(); // also helps speed up your inserts.
        $insert_values = array();
        foreach ($data as $d) {
            $question_marks[] = '(' . placeholders('?', sizeof($d)) . ')';
            $insert_values = array_merge($insert_values, array_values($d));
        }

        $sql = "INSERT INTO results (" . implode(",", $datafields) . ") VALUES " .
            implode(',', $question_marks);

        $stmt = $this->db->prepare($sql);
        try {
            $stmt->execute($insert_values);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $this->db->commit();

        $response = array();

        $response['status'] = 'success';

        return $response;

    }


    public function updateResults($data)
    {
        $count = count($data) - 2; //number of results to update
        $response = array();
        $status = 0;
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

            if ($stmt->rowCount() > 0 && $status < 1) {
                $response['status'] = 'success';
                $status = 1;
                $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Update successful';
            } elseif ($status < 1) {
                $response['status'] = 'error'; // could not update record
                $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Nothing changed';
            }

        }
        return $response;

    }


    public function getScoresBySeg($gId, $segmentId)
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

    public function autoResults($data)
    {

        $datafields = array('event_id', 'segment_id', 'guest_id', 'score_id', 'score_result');


        function placeholders2($text, $count = 0, $separator = ",")
        {
            $result = array();
            if ($count > 0) {
                for ($x = 0; $x < $count; $x++) {
                    $result[] = $text;
                }
            }

            return implode($separator, $result);
        }


        $insert_values = array();

        echo "<h2>Results Array Response</h2>";

        foreach ($data as $d) {
            $question_marks[] = '(' . placeholders2('?', sizeof($d)) . ')';
            $insert_values = array_merge($insert_values, array_values($d));

        }

        foreach ($question_marks as $key => $value) {

            echo $key . " = " . $value . "<br />";

        }

        foreach ($insert_values as $key => $value) {

            echo $key . " = " . $value . "<br />";

        }

        $sql = "INSERT INTO results (" . implode(",", $datafields) . ") VALUES " .
            implode(',', $question_marks);

        $stmt = $this->db->prepare($sql);
        try {
            $stmt->execute($insert_values);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $this->db->commit();

        $response = array();

        $response['status'] = 'success';

        return $response;

    }


}

