<?php

function addrider($guest)
{

    $guest_name = $_GET['Rider'];
    $guest_number = 0;
    $horse_name = $_GET['Horse'];
    $country = "";
    $rs_id = $_SESSION['rs_id'];
    $segment_id = $rs_id;
    //important - set the segment_id for the rider in the arena


    $response = $guest->createGuest(
        $guest_name,
        $guest_number,
        $horse_name,
        $country,
        $segment_id);

    return $response;
}

session_start();

require_once 'inc/src/dbconfig.php';

/**
 *
 * http://localhost/dynamic_data/scoring.php?type=start&location=International%20Arena%201&Horse=Sandro%27s%20Touch&Rider=Erica%20Booth-Schwadron&City=Ann%20Arbor&State=MI
 *
 *
 * Variables sent for Ride in Arena
 * sent when all scribes have opened test for scoring
 *
 * type = start&location (International Arena 1)
 * Horse (Sandro's Touch)
 * Rider (Erica Booth-Schwadron
 * City (Ann Arbor)
 * State (MI)
 */
/**
 * Variables sent for Total Score
 *
 * http://localhost/dynamic_data/scoring.php?type=total&location=International%20Arena%201&E=74.265&H=73.887&C=72.500&M=72.250&B=72.920&Total=73.164
 *
 * type = total&location (International Area 1)
 * K E H C M B F (For each only if present 3 decimal places)
 * Total (Overall Percent)
 *
 *
 */

$uri = "localhost/dynamic_data";

//check for session variables Event must be set
echo "<h2>GET Values</h2>";

foreach ($_GET as $key => $value) {

    echo $key . " = " . $value . "<br />";

}

if (isset($_SESSION['event_name'])) {
    //these session variables exist or nothing can be done
    $event_id = $_SESSION['event_id'];
    $event_name = $_SESSION['event_name'];

    echo "<h2>Session Values</h2>";

    foreach ($_SESSION as $key => $value) {

        echo $key . " = " . $value . "<br />";

    }


    $segment = new \App\Scoreboard\ SEGMENTS($DB_con);
    $guest = new \App\Scoreboard\ GUESTS($DB_con);
    $result = new \App\Scoreboard\ RESULTS($DB_con);

    if ($_GET['type'] == "start") {

        $response = $segment->findSegment($_GET['location'], $event_id);

        echo "<h2>Segment Exist Response</h2>";

        foreach ($response['segment'] as $key => $value) {

            echo $key . " = " . $value . "<br />";

        }

        if ($response['status'] == 'Old Segment') {

            ;

        } else {

            //create a new segment
            $segment_name = $_GET['location'];
            $event_id = $_SESSION['event_id'];

            $t = date_create(NULL, timezone_open("America/Los_Angeles"));

            $start_time = date_format($t, 'g:i');

            //echo $start_time;

            $response = $segment->createSegment($segment_name, $event_id, $start_time);

            $_SESSION['rs_id'] = $response['rs_id'];
            $_SESSION['rs_name'] = $response['rs_name'];

            echo "<h2>Create Segment Response</h2>";

            foreach ($response as $key => $value) {

                echo $key . " = " . $value . "<br />";

            }

        }

        //now the logic whether the GET type is start or total


        $response = addrider($guest);

        $_SESSION['r_id'] = $response['r_id'];


    } elseif ($_GET['type'] == "total" && isset($_SESSION['r_id'])) {

        //insert scorecard and results connect with rider

        $score = new \App\Scoreboard\ SCORES($DB_con);

        $response = $score->findScores($_SESSION['r_id']);

        if ($response['status'] == 'success') {

            echo "<h2>Find Scores Response</h2>";

            echo $response['message'] . '</br>';

        }


        if ($response['status'] == 'error') {

            echo $response['message'];

            $response = $score->insertScores($_GET);

            //an array of Score IDs needed to create results array

            echo "<h2>Insert Scores Response</h2>";

            foreach ($response as $key => $value) {

                echo $key . " = " . $value . "<br />";

            }

            $response = $score->findScores($_SESSION['r_id']);

            //enter their results
            $numResults = count($_GET);
            $x = 0;


            foreach ($_GET as $key => $value) {
                if ($key == "type" || $key == "location" || $key == "Total") {
                    ;
                } else {
                    $data[] = array(
                        'event_id' => $_SESSION['event_id'],
                        'segment_id' => $_SESSION['rs_id'],
                        'guest_id' => $_SESSION['r_id'],
                        'score_id' => $response['scores'][$x],
                        'score_result' => $value //result from post
                    );
                    $x++;
                }
            }

            $response = $result->autoResults($data);
            $result->getAllResults('rs_id');

        }

    } else {

        $response = array();
        $response['status'] = 'Error';
        $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; No Rider Set.';

        echo "<h2>Error Response</h2>";

        foreach ($response as $key => $value) {

            echo $key . " = " . $value . "<br />";
        }

    }


} else {

    $response = array();
    $response['status'] = 'Error';
    $response['message'] = '<span class="fas fa-info-circle"></span> &nbsp; No Event Set.';

    echo "<h2>Error Response</h2>";

    foreach ($response as $key => $value) {

        echo $key . " = " . $value . "<br />";
    }

}
