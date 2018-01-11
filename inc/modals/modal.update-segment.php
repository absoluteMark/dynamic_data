<!-- Modal -->
<div class="modal fade" id="segmentUpdateModal" tabindex="-1" role="dialog" aria-labelledby="segmentUpdateModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="segmentUpdateModalLabel">Update Segment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="info">Update current Segment details</p>
                <form role="form" id="updateSegment-form">
                    <div class="form-group">
                        <input type="text" class="form-control" name="segment_name" id="segment_name" placeholder="Title" autofocus>
                        <small id="segmentnameHelp" class="form-text text-muted">Update the Name</small>
                    </div>
                    <div class="form-group">
                        <input type="time" class="form-control" name="start_time" id="start_time">
                        <small id="starttimeHelp" class="form-text text-muted">Update the Start-time</small>
                    </div>
                </form>
                <div id="errorDiv5"></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="closeBtn5" class="btn btn-secondary" data-dismiss="modal" onclick="eventMain()">Close</button>
                <button type="button" id="submitBtn5" class="btn btn-primary" onclick="updateSegment()">Update</button>
            </div>
        </div>
    </div>
</div>