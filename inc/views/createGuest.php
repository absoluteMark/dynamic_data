<?php

session_start();

?>


<div class="alert alert-info" role="alert">
    <div class="small text-right">Create a new Guest.</div>
</div>

<form role="form" id="guest-form">
    <div class="form-group">
        <input type="text" class="form-control" name="guest_name" id="guest_name" placeholder="Name" autofocus>
        <small id="guest_nameHelp" class="form-text text-muted">Give the guest a name</small>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="horse_name" id="horse_name" placeholder="Horse Name" autofocus>
        <small id="horse_nameHelp" class="form-text text-muted">Give the mode of transport a name</small>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="guest_number" id="guest_number" placeholder="Entry Number" autofocus>
        <small id="guest_numberHelp" class="form-text text-muted">Enter a custom unique reference number</small>
    </div>
</form>
<div class="d-flex justify-content-around" id="guest-buttons">

</div>

<div id="errorDiv" class="mt-4"></div>


