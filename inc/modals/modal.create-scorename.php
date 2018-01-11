<!-- Modal -->
<div class="modal fade" id="scorenameModal" tabindex="-1" role="dialog" aria-labelledby="scorenameModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scorenameModalLabel">New Show Scorename</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="info">Add scorenames.</p>
                <form role="form" id="scorename-form">
                    <div class="form-group">
                        <input type="text" class="form-control" name="scorename_name" id="scorename_name" placeholder="Title" autofocus>
                        <small id="scorenameHelp" class="form-text text-muted">Give the scorename a name</small>
                    </div>
                </form>
                <div id="errorDiv4"></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="closeBtn4" class="btn btn-secondary" data-dismiss="modal" onclick="modalClose()">Close</button>
                <button type="button" id="submitBtn4" class="btn btn-primary" onclick="createScorename()">Submit</button>
            </div>
        </div>
    </div>
</div>