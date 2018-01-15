<?php

session_start();

?>


<div class="alert alert-info" role="alert">
    <div class="small text-right">Add an Event Host, or Interviewee.</div>
</div>

<form role="form" id="host-form">
    <div class="form-group">
        <input type="text" class="form-control" name="name" id="name" placeholder="Name" autofocus>
        <small id="hostHelp" class="form-text text-muted">Give them a name</small>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="subtitle" id="subtitle" placeholder="Subtitle">
        <small id="hostHelp" class="form-text text-muted">Give them a name</small>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="horse" id="horse" placeholder="Horse Name">
        <small id="hostHelp" class="form-text text-muted">Give them a name</small>
    </div>
</form>

<div class="d-flex justify-content-around" id="host-buttons">
</div>

<div id="errorDiv" class="mt-4"></div>


