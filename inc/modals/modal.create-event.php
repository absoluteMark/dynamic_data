<!-- Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">New Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form role="form" id="event-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <input type="text" class="form-control" name="event_name" id="event_name" placeholder="Event Name">
                        <small id="event_nameHelp" class="form-text text-muted">Give the event or show a name</small>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="event_location" id="event_location" placeholder="Event Location">
                        <small id="event_locationHelp" class="form-text text-muted">Location of event</small>
                    </div>
                    <div class="form-group">
                        <label for="bannerUpload">Banner Artwork</label>
                        <input type="file" class="form-control-file" id="bannerUpload">
                    </div>
                </form>
            <div id="errorDiv1"></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="closeBtn1" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="submitBtn1" class="btn btn-primary" onclick="submitEvent()">Create New Event</button>
            </div>
        </div>
    </div>
</div>