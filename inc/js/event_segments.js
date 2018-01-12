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

            setTimeout(function () {
                if (data.status === 'success') {
                    $('#' + div_target_id).html('');
                    $.each(data.segments, function (index, val) {
                        console.log(index, val);
                        $('#' + div_target_id).append('<div onclick="if(event.altKey){loadSegmentUpdate(' + val.segment_id + ')}else{segmentDetails(' + val.segment_id + ')}" id="segment' + val.segment_id + '" class="list-group-item list-group-item-action" data-toggle="list">' + val.start_time + ' - ' + val.segment_name + '</div>');
                    });
                    $('#' + div_results_id).html('');
                    $.each(data.segments, function (index, val) {
                        console.log(index, val);
                        $('#' + div_results_id).append('<div onclick="enterResults(' + val.segment_id + ')" id="segment' + val.segment_id + '" class="list-group-item list-group-item-action" data-toggle="list">' + val.segment_name + '</div>');
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

function loadSegmentCreate() {

    $.get("inc/views/createSegment.php", function (view) {
        $('#segment-body').html(view);
        $('#mode').html('Edit Mode').toggleClass('text-danger text-primary');
        $('#segment-title').html('Create');


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
                    $('#segment-title').html('Update');

                    $.each(data.events, function (index, val) {
                        console.log(index, val);
                        var html = '<div class="form-group">\n' +
                            '<label for="' + val + '">' + index + '</label>\n' +
                            '<input type="text" class="form-control" name="' + index + '" id="' + index + '" value="' + val + '">\n' +
                            '</div>';
                        $('#' + form_target_id).append(html);
                    });

                    $('#' + form_target_id).append('<div class="d-flex justify-content-around"><button type="button" id="cancelSegment" class="btn btn-secondary btn-sm mr-2" onclick="refreshSegmentList()">Back</button>' +
                        '<button type="button" id="updateSegment" class="btn btn-primary btn-sm mr-2" onclick="segmentUpdate(' + segmentID + ')">Update</button>' +
                        '<button type="button" id="deleteSegment" class="btn btn-sm btn-danger" onclick="segmentDelete(' + segmentID + ')">Delete</button></div>')

                })
            }
        },
        error: function () {
            alert('Error !')
        }

    })

}

function refreshSegmentList() {

    $('#segment-body').html("<div class=\"list-group\" id=\"segment-list\"></div>");
    $('#mode').html('Live Mode').toggleClass('text-danger text-primary');
    $('#segment-title').html('Segments');
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

            $('button#submitBtn2').attr('disabled', 'disabled');
            $('button#closeBtn2').attr('disabled', 'disabled');
            $('#errorDiv2').html('<div class="alert alert-info">Processing ...</div>')
            $('#errorDiv2').slideDown(200).delay(200);

            setTimeout(function () {

                if (data.status === 'success') {

                    $('#errorDiv2').show(200, function () {
                        $('#errorDiv2').html('<div class="alert alert-success">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                        $("#segment-form")[0].reset();

                    });
                } else {
                    $('#errorDiv2').show(200, function () {
                        $('#errorDiv2').html('<div class="alert alert-danger">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                    });
                }

                $('button#submitBtn2').html('Create Another ?').removeAttr('disabled');
                $('button#closeBtn2').removeAttr('disabled');


            }, 500);
        },
        error: function () {
            alert('Error !')
        }
    });

    return false;
}

function modalClose() {

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

function segmentUpdate(segmentID) {

    /**
     *
     * Creates a new segment record
     * called by the modal submit button.
     *
     * @returns {boolean}
     */

    var data = $("#updateSegment-form").serialize();

    data = data + '&segmentId=' + segmentID;

    //alert(data);

    $.ajax({

        type: 'POST',
        async: true,
        url: 'inc/src/ajax.update-segment.php',
        data: data,
        dataType: 'json',
        success: function (data) {

            console.log(data);


            $('button#updateSegment').attr('disabled', 'disabled');
            $('button#cancelSegment').attr('disabled', 'disabled');
            $('button#deleteSegment').attr('disabled', 'disabled');
            $('#errorDiv5').html('<div class="alert alert-info">Attempting update ...</div>')
            $('#errorDiv5').slideDown(200).delay(200);

            setTimeout(function () {

                if (data.status === 'success') {

                    $('#errorDiv5').show(200, function () {
                        $('#errorDiv5').html('<div class="alert alert-success">' + data.message + '</div>')
                            .delay(3000).slideUp(100);

                    });
                } else {
                    $('#errorDiv5').show(200, function () {
                        $('#errorDiv5').html('<div class="alert alert-danger">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                    });
                }

                $('button#updateSegment').html('Update').removeAttr('disabled');
                $('button#cancelSegment').removeAttr('disabled');
                $('button#deleteSegment').removeAttr('disabled');

            }, 1500);
        },
        error: function () {
            alert('Error !')
        }
    });

    return false;


}

function segmentDelete(segmentID) {

    var data = $("#updateSegment-form").serialize();

    data = data + '&segmentId=' + segmentID;

    var error_div = $('#errorDiv5');
    var buttons = $('button');

    var answer = confirm("Are you sure ?");

    if (answer === true) {


        $.ajax({

            type: 'POST',
            async: true,
            url: 'inc/src/ajax.delete-segment.php',
            data: data,
            dataType: 'json',
            success: function (data) {

                console.log(data);



                $(buttons).attr('disabled', 'disabled');

                $(error_div).html('<div class="alert alert-warning">Purging data ...</div>').slideDown(200).delay(500);



                    if (data.status === 'success') {

                        $(error_div).show(200, function () {
                            $(error_div).html('<div class="alert alert-success">' + data.message + '</div>')
                                .delay(500).slideUp(100);

                        });

                    } else {
                        $(error_div).show(200, function () {
                            $(error_div).html('<div class="alert alert-danger">' + data.message + '</div>')
                                .delay(500).slideUp(100);
                        });
                    }

                    $(buttons).removeAttr('disabled');

                setTimeout(function()
                {
                    refreshSegmentList();

                },500);



            },
            error: function () {
                alert('Error !')
            }
        });


    }


}
