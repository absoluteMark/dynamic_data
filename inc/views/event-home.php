<?php

session_start();

?>


<div class="col-md-4">
    <div class="card">
        <div class="card-header" id="segment-header">
            <h3 class="float-left text-danger" id="mode">Live Mode</h3>
            <h3 class="text-right" id="segment-title">Segments</h3>
            <button id="segment-add" type="button" class="btn btn-warning btn-sm float-right" onclick="loadSegmentCreate()">Add</button>
        </div>
        <div class="card-body" id="segment-body">
            <div class="alert alert-info" role="alert">
                <div class="small text-right">Click + ALT to Edit</div>
            </div>

            <div class="list-group" id="segment-list">

            </div>
        </div>

    </div>
</div>

<div class="col-md-4">
    <div class="card">
        <div class="card-header" id="guest-header">

            <h3 class="text-right" id="guest-title">Guests</h3>
        </div>

        <div class="card-body" id="guest-body" style="height: 250px;">

            <div class="alert alert-info" role="alert" style="visibility: hidden" id="guest-alert">
                <div class="small text-right">Click + ALT to Edit</div>
            </div>


            <div class="list-group" id="guest-list">

            </div>
        </div>

    </div>
    <div class="card">
        <div class="card-header" id="host-header">

            <h3 class="text-right" id="host-title">Hosts</h3>
            <button type="button" class="btn btn-warning btn-sm float-right" id="host-button">Add</button>
        </div>

        <div class="card-body" id="host-body" style="height: 250px;">


            <div class="list-group" id="host-list">

            </div>
        </div>

    </div>
</div>

<div class="col-md-4">
    <div class="card">
        <div class="card-header" id="results-header">
            <h3 class="text-right" id="results-title">Results</h3>
        </div>

        <div class="card-body" id="results-body">

            <div class="list-group" id="results-list">

            </div>
        </div>

    </div>
</div>

