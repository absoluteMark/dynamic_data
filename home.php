<?php
/**
 * Created by PhpStorm.
 * User: markslater
 * Date: 12/28/17
 * Time: 11:10 AM
 *
 *
 * This page loads after the user passes
 * the password validation stage.
 *
 * Otherwise they are redirected to index.php
 * which is the sign-in page.
 *
 *
 * Includes: dbconfig.php
 * and the 'User' class, that is included in that file.
 *
 * The PHP makes a call to the server and attempts to
 * connect with the database, using the credentials
 * stored in this file!
 *
 *
 * dbconfig.php connects with the server
 * database, verifying permission to access
 * the tables stored there.
 *
 * It adds the functions contained in class.user.php
 * which all relate to user operations. Hence we can
 * immediately use the function to check if the user
 * is properly logged in - which simply asks if the
 * Session variable user_session has been set.
 *
 * That session variable is set by another class.user
 * function which is called when index.php is loaded.
 *
 *
 *
 */

include_once 'inc/src/dbconfig.php';

if (!$user->is_loggedin()) {
    $user->redirect('index.php');
}

$user_id = $_SESSION['user_session'];
$user_fname = $_SESSION['user_fname'];

if (isset($_SESSION['event_name'])) {

    include 'inc/heads/newhead.phtml';

} else {

    include 'inc/heads/head.phtml';
}

include INC_PATH . "menus/pre-menu.php";


?>

    <div class="container-fluid">
        <div class="row" id="main" >
            <div class="col-md-8 offset-md-2">

                <div class="form-container">
                    <h2>Welcome : <?php print($user_fname); ?></h2>
                    <hr/>
                    <form role="form">
                        <div class="form-group">
                            <label for="events">Please select an event from the list below.</label>
                            <select class="form-control form-control-lg" name="events" id="list-target">
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php


include 'inc/footers/footer.phtml';


?>