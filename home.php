<?php

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