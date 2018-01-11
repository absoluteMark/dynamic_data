<?php


/**
 *
 * Brings up the show segment
 * selected
 *
 *
 *
 */

session_start();

if(isset($_GET['classId'])){

    $_SESSION['class_id'] = $_GET['classId'];

    /**
     *
     * Here the code to get the segment details.
     *
     *
     */


}

?>
            <div class="card-body" id="sub_panel_2">
                <h4 class="card-title text-primary">
                    <?php echo $_SESSION['class_name'];?>
                    <?php echo $_SESSION['class_id']; ?>
                </h4>
                <h5 class="card-subtitle mb-2">
                    Contestants
                </h5>
            </div>