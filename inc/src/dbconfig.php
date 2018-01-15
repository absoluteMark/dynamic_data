<?php
/**
 * Created by PhpStorm.
 * User: markslater
 * Date: 12/28/17
 * Time: 10:35 AM
 */


session_start();

define('INC_PATH',dirname(__DIR__)."/");


$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "root";
$DB_name = "alp_scoreboard";


try
{
    $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
    $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

include_once INC_PATH.'class/class.user.php';
include_once INC_PATH.'class/events.php';
include_once INC_PATH.'class/segments.php';
include_once INC_PATH.'class/guests.php';
include_once INC_PATH.'class/scores.php';
include_once INC_PATH.'class/judges.php';
include_once INC_PATH.'class/results.php';
include_once INC_PATH.'class/hosts.php';


$user = new \App\Scoreboard\USER($DB_con);
