<?php

session_start();

?>


<div class="alert alert-info" role="alert">
    <div class="small text-right">Create a new Guest.</div>
</div>

<form role="form" id="contestant-form">
    <div class="form-group">
        <input type="text" class="form-control" name="contestant_name" id="contestant_name" placeholder="Name" autofocus>
        <small id="contestant_nameHelp" class="form-text text-muted">Give the contestant a name</small>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="horse_name" id="horse_name" placeholder="Horse Name" autofocus>
        <small id="horse_nameHelp" class="form-text text-muted">Give the mode of transport a name</small>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="contestant_number" id="contestant_number" placeholder="Entry Number" autofocus>
        <small id="contestant_numberHelp" class="form-text text-muted">Enter a custom unique reference number</small>
    </div>
</form>
<div class="d-flex justify-content-around" id="guest-buttons">

</div>

<div id="errorDiv3" class="mt-4"></div>


