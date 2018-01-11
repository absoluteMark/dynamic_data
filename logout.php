<?php
/**
 * Created by PhpStorm.
 * User: markslater
 * Date: 12/28/17
 * Time: 11:36 AM
 */


 session_start();

 if (!isset($_SESSION['user'])) {
     header("Location: index.php");
 } else if(isset($_SESSION['user'])!="") {
     header("Location: home.php");
 }

 if (isset($_GET['logout'])) {
     unset($_SESSION['user']);
     session_unset();
     session_destroy();
     header("Location: index.php");
     exit;
 }

if (isset($_GET['index'])) {
    unset($_SESSION['event_name']);
    unset($_SESSION['event_id']);
    header("Location: index.php");
    exit;
}

if (isset($_GET['event'])) {

    /**
     *
     * Takes us back to the Event Panel
     * from the Segment Panel
     *
     */

    exit;
}