<?php

session_start();

?>


<div class="alert alert-info" role="alert">
    <div class="small text-right">Create a Score category.</div>
</div>

<form role="form" id="scorename-form">
    <div class="form-group">
        <input type="text" class="form-control" name="scorename_name" id="scorename_name" placeholder="Title" autofocus>
        <small id="scorenameHelp" class="form-text text-muted">Give the score a name</small>
    </div>
</form>

<div class="d-flex justify-content-around">
    <button type="button" id="closeBtn2" class="btn btn-secondary" onclick="refreshSegmentList()">Back</button>
    <button type="button" id="submitBtn2" class="btn btn-primary" onclick="createScorename()">Submit</button>
</div>

<div id="errorDiv4" class="mt-4"></div>


