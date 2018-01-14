<?php

session_start();

?>


<div class="alert alert-info" role="alert">
    <div class="small text-right">Create a new Segment</div>
</div>
<form role="form" id="segment-form">
    <div class="form-group">
        <input type="text" class="form-control" name="segment_name" id="segment_name" placeholder="Title" autofocus>
        <small id="segmentnameHelp" class="form-text text-muted">Give the segment a name</small>
    </div>
    <div class="form-group">
        <input type="time" class="form-control" name="start_time" id="start_time">
        <small id="starttimeHelp" class="form-text text-muted">Start-time</small>
    </div>
</form>
<div class="d-flex justify-content-around">
    <button type="button" id="closeBtn" class="btn btn-secondary" onclick="refreshSegmentList()">Back</button>
    <button type="button" id="submitBtn" class="btn btn-primary" onclick="createSegment()">Submit</button>
</div>

<div id="errorDiv" class="mt-4"></div>


