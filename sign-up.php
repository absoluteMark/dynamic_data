<?php
/**
 * Created by PhpStorm.
 * User: markslater
 * Date: 12/28/17
 * Time: 11:05 AM
 */

require_once 'inc/src/dbconfig.php';

if ($user->is_loggedin() != "") {
    $user->redirect('home.php');
}

if (isset($_POST['btn-signup'])) {
    $fname = trim($_POST['txt_fname']);
    $lname = trim($_POST['txt_lname']);
    $umail = trim($_POST['txt_umail']);
    $upass = trim($_POST['txt_upass']);

    if ($umail == "") {
        $error[] = "provide email id !";
    } else if (!filter_var($umail, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Please enter a valid email address !';
    } else if ($upass == "") {
        $error[] = "provide password !";
    } else if (strlen($upass) < 6) {
        $error[] = "Password must be at least 6 characters";
    } else {
        try {
            $stmt = $DB_con->prepare("SELECT user_email FROM users WHERE user_email=:umail");
            $stmt->execute(array(':umail' => $umail));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['user_email'] == $umail) {
                $error[] = "Sorry. Email already taken !";
            } else {
                if ($user->register($fname, $lname, $umail, $upass)) {
                    $user->redirect('sign-up.php?joined');
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

include 'inc/heads/head.phtml';

?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-md-offset-3" align="center">
                <img src="images/logo.png" alt="Absolute Live Scoreboard" class="logo"><br><br>
                <h4 class="mb-5">Live Scoreboard System</h4>
                <div class="form-container">
                    <form method="post">
                        <h2>Sign up</h2>
                        <hr/>
                        <?php
                        if (isset($error)) {
                            foreach ($error as $error) {
                                ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle"></i> &nbsp; <?php echo $error; ?>
                                </div>
                                <?php
                            }
                        } else if (isset($_GET['joined'])) {
                            ?>
                            <div class="alert alert-info">
                                <i class="fas fa-sign-in-alt"></i> &nbsp; Successfully registered <a
                                        href='<?php INC_PATH ?>index.php'>login</a> here
                            </div>
                            <?php
                        }
                        ?>
                        <div class="form-group">
                            <input type="text" class="form-control" name="txt_fname" placeholder="Enter First Name"
                                   value="<?php if (isset($error)) {
                                       echo $fname;
                                   } ?>"/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="txt_lname" placeholder="Enter Last Name"
                                   value="<?php if (isset($error)) {
                                       echo $lname;
                                   } ?>"/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="txt_umail" placeholder="Enter E-Mail ID"
                                   value="<?php if (isset($error)) {
                                       echo $umail;
                                   } ?>"/>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="txt_upass" placeholder="Enter Password"/>
                        </div>
                        <div class="clearfix"></div>
                        <hr/>
                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-primary" name="btn-signup">
                                <i class="fas fa-external-link-alt"></i>&nbsp;SIGN UP
                            </button>
                        </div>
                        <br/>
                        <label>Already have an account ? <a href="index.php">Sign In</a></label>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php

include 'inc/footers/footer.phtml';

?>