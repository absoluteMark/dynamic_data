<?php


/**
 *
 * Includes a file that connects to the
 * database and the USER class which stores the
 * functions used to test if a client User
 * (e.g. a person using a web browser on their phone)
 * is authorized to access the app.
 *
 * If successful, a user_session id and
 * user_fname are added to the $_SESSION, which are
 * server side stored variables and therefore not
 * recorded locally on the computer, keeping the
 * data secure. In addition those variables are
 * automatically destroyed when the user closes
 * their browser, and time out due to inactivity.
 *
 * The function on the top of every page simply asks
 * whether user_session has been set.
 *
 * The database on the server also contains
 * the encrypted password details of any
 * user. And the database requires a password
 * to connect to it, query and retrieve data.
 *
 *
 */


require_once 'inc/src/dbconfig.php';

if ($user->is_loggedin() != "") {
    $user->redirect('home.php');
}

/**
 *
 * The $_POST call to the server is triggered
 * when the user fills in their details and
 * clicks on the submit button.
 *
 * It sends your username or email along with
 * your password entry up to the server where
 * the function in the USER class file login()
 * checks those details against the ones it
 * pulls from the database, using a PDO prepared
 * statement.
 *
 * If your data fails to validate then an error
 * message displays under each form input line
 * with the problem.
 *
 * When the page first loads, neither $_POST or
 * $error are set, and therefore the page loads
 * with the blank form, formatted to the brand.
 *
 * Head section and Footer section are kept
 * in separate files, as they keep most of
 * the same code for all page loads throughout
 * the site.
 *
 * Importantly they download the style sheets
 * for Bootstrap CSS, the jQuery library and
 * the custom Javascript built for the site.
 *
 *
 *
 *
 *
 */




if (isset($_POST['btn-login'])) {
    $uname = $_POST['txt_uname_email'];
    $umail = $_POST['txt_uname_email'];
    $upass = $_POST['txt_password'];

    if ($user->login($uname, $umail, $upass)) {
        $user->redirect('home.php');
    } else {
        $error = "Wrong Details !";
    }
}

include INC_PATH . '/heads/head.phtml';

?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-md-offset-3" align="center">
                <img src="images/logo.png" alt="Absolute Live Scoreboard" width="200" height="200"><br><br>
                <h4 class="mb-5">Live Data Solution</h4>

                <div class="form-container">
                    <form method="post">
                        <h2>Sign in</h2>
                        <hr/>
                        <?php
                        if (isset($error)) {
                            ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle"></i> &nbsp; <?php echo $error; ?> !
                            </div>
                            <?php
                        }
                        ?>
                        <div class="form-group">
                            <input type="text" class="form-control" name="txt_uname_email"
                                   placeholder="Username or E mail ID"
                                   required/>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="txt_password" placeholder="Your Password"
                                   required/>
                        </div>
                        <div class="clearfix"></div>
                        <hr/>
                        <div class="form-group">
                            <button type="submit" name="btn-login" class="btn btn-block btn-primary">
                                <i class="fas fa-sign-in-alt"></i>&nbsp;SIGN IN
                            </button>
                        </div>
                        <br/>
                        <label>Don't have account yet ? <a href="sign-up.php">Sign Up</a></label>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php

include 'inc/footers/newfooter.phtml';

?>