<!-- Modal -->
<div class="modal fade" id="contestantModal" tabindex="-1" role="dialog" aria-labelledby="contestantModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">New Contestant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="info">Add contestants to your show.</p>
                <form role="form" id="contestant-form">
                    <div class="form-group">
                        <input type="text" class="form-control" name="contestant_name" id="contestant_name" placeholder="Name" autofocus>
                        <small id="contestant_nameHelp" class="form-text text-muted">Give the contestant a name</small>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="horse_name" id="horse_name" placeholder="Horse Name" autofocus>
                        <small id="horse_nameHelp" class="form-text text-muted">Give the mode of transport a name</small>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="contestant_number" id="contestant_number" placeholder="Entry Number" autofocus>
                        <small id="contestant_numberHelp" class="form-text text-muted">Enter a custom unique reference number</small>
                    </div>
                </form>
                <div id="errorDiv3"></div>
            </div>
            <div class="modal-footer">
                <button type="button" id="closeBtn3" class="btn btn-secondary" data-dismiss="modal" onclick="modalClose()">Close</button>
                <button type="button" id="submitBtn3" class="btn btn-primary" onclick="createContestant()">Submit</button>
            </div>
        </div>
    </div>
</div>