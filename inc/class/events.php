<?php
/**
 * Created by PhpStorm.
 * User: markslater
 * Date: 12/31/17
 * Time: 6:56 PM
 */


namespace App\Scoreboard;

use \PDO;  // <--- need by PhpStorm to find Methods of PDO

class EVENTS
{
    /**
     * @var PDO   <--- need by PhpStorm to find Methods of PDO
     */
    private $db;

    function __construct($DB_con)
    {
        $this->db = $DB_con;
    }

    public function get_all_events()
    {
        $response = array();

        $stmt = $this->db->prepare("SELECT * FROM events WHERE 1");
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_COLUMN, 1);

        $response['events'] = $res;

        // check for success
        if ($stmt->rowCount() > 0) {
            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Success.';
        } else {
            $response['status'] = 'error';
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Error.';
        }

        return $response;

    }

    public function event_details($name)
    {

        $response = array();

        $stmt = $this->db->prepare("SELECT * FROM events WHERE event_name=:ename");
        $stmt->execute(array(':ename' => $name));
        $res = $stmt->fetch(PDO::FETCH_ASSOC);


        $response['events'] = $res;

        //print_r($res);

        // check for success
        if ($stmt->rowCount() == 1) {
            $_SESSION['event_id'] = $res['event_id'];
            $_SESSION['event_name'] = $res['event_name'];
            $_SESSION['event_logo'] = $res['event_logo'];
            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Success.';
        } else {

            $response['status'] = 'error';
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Error.';
        }
        return $response;


    }

    public function add_event($event_array)
    {
        $response = array();

        $stmt = $this->db->prepare('INSERT INTO events(event_name,location) VALUES(:event_name,:location)');
        $stmt->bindParam(':event_name', $event_array['event_name']);
        $stmt->bindParam(':location', $event_array['event_location']);

        $stmt->execute();

        // check for successful registration
        if ($stmt->rowCount() == 1) {
            $response['status'] = 'success';
            $response['message'] = '<span class="fas fa-check-circle"></span> &nbsp; Event registered successfully.';
        } else {

            $response['status'] = 'error'; // could not register
            $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; Could not register event.';
        }

        return $response;
    }

    public function unsetEvent()
    {

        unset($_SESSION['event_id']);
        unset($_SESSION['event_name']);

        return true;

    }


}


