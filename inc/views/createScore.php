<?php

session_start();

?>


<div class="alert alert-info" role="alert">
    <div class="small text-right">Create a new Score title.</div>
</div>

<form role="form" id="score-form">
    <div class="form-group">
        <input type="text" class="form-control" name="score_name" id="score_name" placeholder="Title" autofocus>
        <small id="scoreHelp" class="form-text text-muted">Give the score a name</small>
    </div>
</form>

<div class="d-flex justify-content-around" id="score-buttons">
</div>

<div id="errorDiv4" class="mt-4"></div>


