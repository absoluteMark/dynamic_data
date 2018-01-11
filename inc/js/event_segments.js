function segmentList() {

    /**
     *
     * Automatically loads
     * the segment index for
     * the selected event
     *
     * Creates dynamic links
     * for each segment found
     *
     */

    var div_target_id = 'segment-list';
    var div_results_id = 'results-list';


    $.ajax({
        type: 'POST',
        url: 'inc/src/ajax.get-segments.php',
        data: {name: "today"},
        datatype: 'json',
        cache: false,
        success: function (data) {

            console.log(data);

            //alert("Status: " + data.status + "\nMessage: " + data.message);

            setTimeout(function () {
                if (data.status === 'success') {
                    $('#' + div_target_id).html('');
                    $.each(data.segments, function (index, val) {
                        console.log(index, val);
                        $('#' + div_target_id).append('<div onclick="if(event.altKey){loadSegmentUpdate(' + val.segment_id + ')}else{segmentDetails(' + val.segment_id + ')}" id="segment' + val.segment_id +'" class="list-group-item list-group-item-action" data-toggle="list">' + val.segment_name + '</div>');
                    });
                    $('#' + div_results_id).html('');
                    $.each(data.segments, function (index, val) {
                        console.log(index, val);
                        $('#' + div_results_id).append('<div onclick="enterResults(' + val.segment_id + ')" id="segment' + val.segment_id +'" class="list-group-item list-group-item-action" data-toggle="list">' + val.segment_name + '</div>');
                    });

                } else {

                    $('#' + div_target_id).slideDown(200, function () {
                        $('#' + div_target_id).html('<div class="alert alert-danger">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                    });
                }
            }, 500);
        },
        error: function () {
            alert('Error !')
        }

    });




}

function loadSegmentUpdate(segmentID) {

    var form_target_id = "updateSegment-form";

    /**
     *
     * Ajax call - load selected segment details
     * populate form with values
     * add cancel and update and delete buttons
     * return and refresh segment-body
     *
     *
     */


    $.ajax({
        type: 'GET',
        url: 'inc/src/ajax.main.segment.php',
        data: {segmentId: segmentID},
        datatype: 'json',
        cache: false,
        success: function (data) {

            //alert("Status: " + data.status + "\nMessage: " + data.message);

            if (data.status === 'success') {
                $.get("inc/views/updateSegment.php", function (view) {
                    $('#segment-body').html(view);
                    $('#mode').html('Edit Mode').toggleClass('text-danger text-primary');

                    $.each(data.events, function (index, val) {
                        console.log(index, val);
                        var html = '<div class="form-group">\n' +
                            '<label for="' + val + '">' + index + '</label>\n' +
                            '<input type="text" class="form-control" name="' + index + '" id="' + index + '" value="' + val + '">\n' +
                            '</div>';
                        $('#' + form_target_id).append(html);
                    });

                    $('#' + form_target_id).append('<button type="button" id="cancelSegment" class="btn btn-secondary mr-2" onclick="refreshSegmentList()">Cancel</button>');
                    $('#' + form_target_id).append('<button type="button" id="deleteSegment" class="btn btn-danger mr-2" onclick="segmentDelete()">Delete</button>');
                    $('#' + form_target_id).append('<button type="button" id="updateSegment" class="btn btn-primary mr-2" onclick="segmentUpdate()">Update</button>');
                })
            }
        },
        error: function () {
            alert('Error !')
        }

    })

}

function refreshSegmentList(){

    $('#segment-body').html("<div class=\"list-group\" id=\"segment-list\"></div>");
    $('#mode').html('Live Mode').toggleClass('text-danger text-primary');
    segmentList();

}

function createSegment() {

    /**
     *
     * Creates a new segment record
     * called by the modal submit button.
     *
     * @returns {boolean}
     */

    var data = $("#segment-form").serialize();

    //alert(data);

    $.ajax({

        type: 'POST',
        async: true,
        url: 'inc/src/ajax.create-segment.php',
        data: data,
        dataType: 'json',
        success: function (data) {

            console.log(data);

            $('button#submitBtn2').html('Creating segment ...').attr('disabled', 'disabled');
            $('button#closeBtn2').attr('disabled', 'disabled');
            $('.modal-body').css('opacity', '.5');

            setTimeout(function () {

                if (data.status === 'success') {

                    $('#errorDiv2').slideDown(200, function () {
                        $('#errorDiv2').html('<div class="alert alert-info">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                        $("#segment-form")[0].reset();

                    });
                } else {
                    $('#errorDiv2').slideDown(200, function () {
                        $('#errorDiv2').html('<div class="alert alert-danger">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                    });
                }

                $('button#submitBtn2').html('Create Another ?').removeAttr('disabled');
                $('button#closeBtn2').removeAttr('disabled');
                $('.modal-body').css('opacity', '');

            }, 500);
        },
        error: function () {
            alert('Error !')
        }
    });

    return false;
}

function segmentMain(segmentId) {

    /**
     *
     * Loads the segment panel
     * for the selected segment
     *
     *
     * Editing: Should load updateSegment Modal
     * if it does not exist (see example above)
     *
     *
     * @param segmentId
     */

    var div_target_id = 'guest-list';


    $.ajax({
        type: 'GET',
        url: 'inc/src/ajax.main.segment.php',
        data: {segmentId: segmentId},
        datatype: 'json',
        cache: false,
        success: function (data) {

            //alert("Status: " + data.status + "\nEvent ID: " + data.events['event_id']);



            $("#guest-title").html('Segment Details');
            $("#guest-list").html('Some guests');

            segmentDetails();

        },
        error: function () {
            alert('Error !')
        }
    });


}

function modalClose() {

    /**
     *
     * Refreshes the segment panel
     * on close of the modal
     *
     */


    $.ajax({
        type: 'GET',
        url: 'inc/src/ajax.main.segment.php',
        data: {segmentId: "refresh"},
        datatype: 'json',
        cache: false,
        success: function (data) {
            //alert("Status: " + data.status + "\nEvent ID: " + data.events['event_id']);

            $.get("inc/views/segments.php", function (data) {
                $('#card-body').html(data);
            });

            segmentDetails();

            $('button#submitBtn4').html('Submit');
            $('button#submitBtn3').html('Submit');
            $('button#submitBtn2').html('Submit');
            $('button#submitBtn1').html('Submit');

        },
        error: function () {
            alert('Error !')
        }
    });

}

function eventMain() {

    /**
     *
     * Simply re-loads the event panel
     * necessary for the back link
     *
     *
     */

    $("#main").load('inc/views/event-home.php');
    segmentList();

}

function segmentUpdate(){

    /**
     *
     * Creates a new segment record
     * called by the modal submit button.
     *
     * @returns {boolean}
     */

    var data = $("#updateSegment-form").serialize();

    //alert(data);

    $.ajax({

        type: 'POST',
        async: true,
        url: 'inc/src/ajax.update-segment.php',
        data: data,
        dataType: 'json',
        success: function (data) {

            console.log(data);

            $('button#updateSegment').html('Updating segment ...').attr('disabled', 'disabled');
            $('button#cancelSegment').attr('disabled', 'disabled');
            $('.modal-body').css('opacity', '.5');

            setTimeout(function () {

                if (data.status === 'success') {

                    $('#errorDiv2').slideDown(200, function () {
                        $('#errorDiv5').html('<div class="alert alert-info">' + data.message + '</div>')
                            .delay(3000).slideUp(100);

                    });
                } else {
                    $('#errorDiv2').slideDown(200, function () {
                        $('#errorDiv5').html('<div class="alert alert-danger">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                    });
                }

                $('button#updateSegment').html('Update').removeAttr('disabled');
                $('button#cancelSegment').removeAttr('disabled');
                $('.modal-body').css('opacity', '');

            }, 500);
        },
        error: function () {
            alert('Error !')
        }
    });

    //return false;



}

function segmentDelete(){




}
