<?php

session_start();

?>


<div class="col-md-4">
    <div class="card">
        <div class="card-header" id="segment-header">
            <h3 class="float-left text-danger" id="mode">Live Mode</h3>
            <h3 class="text-right" id="segment-title" onclick="loadSegmentCreate()">Segments</h3>
        </div>
        <div class="card-body" id="segment-body">


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


            <div class="list-group" id="guest-list">

            </div>
        </div>

    </div>
    <div class="card">
        <div class="card-header" id="host-header">

            <h3 class="text-right" id="host-title">Hosts</h3>
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

