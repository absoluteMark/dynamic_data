<!-- Modal -->
<div class="modal fade" id="segmentModal" tabindex="-1" role="dialog" aria-labelledby="segmentModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="segmentModalLabel">New Show Segment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="info">Add segments to your show.</p>
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
                <div id="errorDiv2"></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="closeBtn2" class="btn btn-secondary" data-dismiss="modal" onclick="eventMain()">Close</button>
                <button type="button" id="submitBtn2" class="btn btn-primary" onclick="createSegment()">Submit</button>
            </div>
        </div>
    </div>
</div>