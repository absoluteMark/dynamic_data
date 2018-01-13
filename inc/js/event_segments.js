/**
 *
 * Segment CRUD functions
 *
 *
 */

function segmentList() {

    var div_target_id = 'segment-list';
    var div_results_id = 'results-list';


    $.ajax({
        type: 'POST',
        url: 'inc/src/ajax.get-segments.php',
        data: {name: "today"},
        dataType: 'json',
        cache: false,
        success: function (data) {

            setTimeout(function () {
                if (data.status === 'success') {
                    $('#' + div_target_id).html('');
                    $.each(data.segments, function (index, val) {
                        console.log(index, val);
                        $('#' + div_target_id).append('<div onclick="if(event.altKey){loadSegmentUpdate(' + val.segment_id + ')}else{refreshGuestList(' + val.segment_id + ')}" id="segment' + val.segment_id + '" class="list-group-item list-group-item-action" data-toggle="list">' + val.start_time + ' - ' + val.segment_name + '</div>');
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

    var form_target_id = "update-form";


    $.ajax({
        type: 'GET',
        url: 'inc/src/ajax.main.segment.php',
        data: {segmentId: segmentID},
        dataType: 'json',
        cache: false,
        success: function (data) {

            //alert("Status: " + data.status + "\nMessage: " + data.message);

            if (data.status === 'success') {
                $.get("inc/views/update.php", function (view) {
                    $('#segment-body').html(view);
                    $('#mode').html('Edit Mode').toggleClass('text-danger text-primary');
                    $('#segment-title').html('Update Segment');

                    $('#alert').append('<div class="small text-right" id="hint">Update the Segment Details</div>');

                    $.each(data.events, function (index, val) {
                        console.log(index, val);
                        var html = '<div class="form-group">\n' +
                            '<label for="' + val + '">' + index + '</label>\n' +
                            '<input type="text" class="form-control" name="' + index + '" id="' + index + '" value="' + val + '">\n' +
                            '</div>';
                        $('#' + form_target_id).append(html);
                    });

                    $('#guest-link').append('<a class=\"nav-link\" href=\"#\" onclick=\"loadGuestCreate(' + segmentID + ')\">Add Guest</a>');
                    $('#score-link').append('<a class=\"nav-link\" href=\"#\" onclick=\"loadScoreCreate(' + segmentID + ')\">Add Score</a>');

                    $('#' + form_target_id).append('<div class="d-flex justify-content-around"><button type="button" id="cancelBtn" class="btn btn-secondary btn-sm mr-2" onclick="refreshSegmentList()">Back</button>' +
                        '<button type="button" id="updateBtn" class="btn btn-primary btn-sm mr-2" onclick="segmentUpdate(' + segmentID + ')">Update</button>' +
                        '<button type="button" id="deleteBtn" class="btn btn-sm btn-danger" onclick="segmentDelete(' + segmentID + ')">Delete</button></div>')

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


    var data = $("#segment-form").serialize();
    var errorDiv = $('#errorDiv2');
    var submitBtn = $('#submitBtn2');
    var closeBtn = $('#closeBtn2');

    //alert(data);

    $.ajax({

        type: 'POST',
        async: true,
        url: 'inc/src/ajax.create-segment.php',
        data: data,
        dataType: 'json',
        success: function (data) {

            console.log(data);

            $(submitBtn).attr('disabled', 'disabled');
            $(closeBtn).attr('disabled', 'disabled');
            $(errorDiv).html('<div class="alert alert-info">Processing ...</div>')
            $(errorDiv).slideDown(200).delay(200);

            setTimeout(function () {

                if (data.status === 'success') {

                    $(errorDiv).show(200, function () {
                        $(errorDiv).html('<div class="alert alert-success">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                        $("#segment-form")[0].reset();

                    });
                } else {
                    $(errorDiv).show(200, function () {
                        $(errorDiv).html('<div class="alert alert-danger">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                    });
                }

                $(submitBtn).html('Create Another ?').removeAttr('disabled');
                $(closeBtn).removeAttr('disabled');


            }, 500);
        },
        error: function () {
            alert('Error !')
        }
    });

    return false;
}

function segmentUpdate(segmentID) {

    var data = $("#update-form").serialize();
    data = data + '&segmentId=' + segmentID;

    var errorDiv = $('#errorDiv');
    var updateBtn = $('#updateBtn');
    var backBtn = $('#cancelBtn');
    var deleteBtn = $('#deleteBtn');

    //alert(data);

    $.ajax({

        type: 'POST',
        async: true,
        url: 'inc/src/ajax.update-segment.php',
        data: data,
        dataType: 'json',
        success: function (data) {

            console.log(data);


            $(updateBtn).attr('disabled', 'disabled');
            $(backBtn).attr('disabled', 'disabled');
            $(deleteBtn).attr('disabled', 'disabled');
            $(errorDiv).html('<div class="alert alert-info">Attempting update ...</div>')
            $(errorDiv).slideDown(200).delay(200);

            setTimeout(function () {

                if (data.status === 'success') {

                    $(errorDiv).show(200, function () {
                        $(errorDiv).html('<div class="alert alert-success">' + data.message + '</div>')
                            .delay(3000).slideUp(100);

                    });
                } else {
                    $(errorDiv).show(200, function () {
                        $(errorDiv).html('<div class="alert alert-danger">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                    });
                }

                $(updateBtn).html('Update').removeAttr('disabled');
                $(backBtn).removeAttr('disabled');
                $(deleteBtn).removeAttr('disabled');

            }, 1500);
        },
        error: function () {
            alert('Error !')
        }
    });

    return false;


}

function segmentDelete(segmentID) {

    var data = $("#update-form").serialize();

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

                setTimeout(function () {
                    refreshSegmentList();

                }, 500);


            },
            error: function () {
                alert('Error !')
            }
        });


    }


}

/**
 *
 *
 * Guest CRUD functions
 *
 * @param segmentID,contestantID
 */

function refreshGuestList(segmentID) {

    var div_target_id = 'guest-list';

    $('#guest-title').html('Guests');

    $.ajax({
        type: 'POST',
        url: 'inc/src/ajax.get-guests.php',
        data: {segmentID: segmentID},
        dataType: 'json',
        cache: false,
        success: function (data) {

            console.log(data);

            //alert("Status: " + data.status + "\nMessage: " + data.message);

            setTimeout(function () {
                if (data.status === 'success') {
                    $('#' + div_target_id).html('').slideDown();
                    $.each(data.guests, function (index, val) {
                        console.log(index, val);
                        $('#' + div_target_id).append('<div onclick="if(event.altKey){loadGuestUpdate(' + val.guest_id + ',' + segmentID +')}else{selectGuest(' + val.guest_id + ')}" id="guest' + val.guest_id + '" class="list-group-item list-group-item-action" data-toggle="list">' + val.guest_name + '</div>');

                    });

                } else {

                    $('#' + div_target_id).slideDown(200, function () {
                        $('#' + div_target_id).html('<div class="alert alert-danger">' + data.message + '</div>')

                    });
                }
            }, 500);
        },
        error: function () {
            alert('Error !')
        }

    });


}

function loadGuestUpdate(guestID,segmentID) {

    var form_target_id = "update-form";

    $.ajax({
        type: 'POST',
        url: 'inc/src/ajax.main.guest.php',
        data: {guestId: guestID},
        dataType: 'json',
        cache: false,
        success: function (data) {

            //alert("Status: " + data.status + "\nMessage: " + data.message);

            if (data.status === 'success') {
                $.get("inc/views/update.php", function (view) {
                    $('#segment-body').html(view);
                    $('#mode').html('Edit Mode').toggleClass('text-danger text-primary');
                    $('#segment-title').html('Update Guest');

                    $('#alert').append('<div class="small text-right" id="hint">Update the Guest Details</div>');


                    $.each(data.guest, function (index, val) {
                        console.log(index, val);
                        var html = '<div class="form-group">\n' +
                            '<label for="' + val + '">' + index + '</label>\n' +
                            '<input type="text" class="form-control" name="' + index + '" id="' + index + '" value="' + val + '">\n' +
                            '</div>';
                        $('#' + form_target_id).append(html);
                    });

                    $('#guest-link').append('<a class=\"nav-link\" href=\"#\" onclick=\"loadGuestCreate(' + segmentID + ')\">Add Guest</a>');
                    $('#score-link').append('<a class=\"nav-link\" href=\"#\" onclick=\"loadScoreCreate(' + segmentID + ')\">Add Score</a>');

                    $('#' + form_target_id).append('<div class="d-flex justify-content-around"><button type="button" id="cancelBtn" class="btn btn-secondary btn-sm mr-2" onclick="refreshSegmentList(' + segmentID + ')">Back</button>' +
                        '<button type="button" id="updateBtn" class="btn btn-primary btn-sm mr-2" onclick="guestUpdate(' + guestID + ',' + segmentID + ')">Update</button>' +
                        '<button type="button" id="deleteBtn" class="btn btn-sm btn-danger" onclick="guestDelete(' + guestID + ',' + segmentID + ')">Delete</button></div>')

                })
            }
        },
        error: function () {
            alert('Error !')
        }

    })



}

function createGuest(segmentID) {

    var errorDiv = $('#errorDiv3');
    var submitBtn = $('#submitBtn3');
    var closeBtn = $('#closeBtn3');
    var form = $('#guest-form');

    var data = $(form).serialize();
    data = data + '&segmentId=' + segmentID;


    $.ajax({

        type: 'POST',
        async: true,
        url: 'inc/src/ajax.create-guest.php',
        data: data,
        dataType: 'json',
        success: function (data) {

            console.log(data);

            $(submitBtn).attr('disabled', 'disabled');
            $(closeBtn).attr('disabled', 'disabled');
            $(errorDiv).html('<div class="alert alert-info">Processing ...</div>')
            $(errorDiv).slideDown(200).delay(200);


            setTimeout(function () {

                if (data.status === 'success') {

                    $(errorDiv).show(200, function () {
                        $(errorDiv).html('<div class="alert alert-success">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                        $(form)[0].reset();

                    });
                } else {
                    $(errorDiv).show(200, function () {
                        $(errorDiv).html('<div class="alert alert-danger">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                    });
                }

                $(submitBtn).html('Create Another ?').removeAttr('disabled');
                $(closeBtn).removeAttr('disabled');


            }, 500);
        },
        error: function () {
            alert('Error !')
        }
    });

    refreshGuestList(segmentID);


}

function loadGuestCreate(segmentID) {



    $.get("inc/views/createGuest.php", function (view) {
        $('#segment-body').html(view);
        $('#mode').html('Edit Mode').toggleClass('text-danger text-primary');
        $('#segment-title').html('Create Guest');
        var buttons = $('#guest-buttons');

        $(buttons).append('<button type=\"button\" id=\"closeBtn3\" class=\"btn btn-secondary\" ' +
            'onclick=\"loadSegmentUpdate(' + segmentID + ')\">Back</button>');

        $(buttons).append('<button type=\"button\" id=\"submitBtn3\" class=\"btn btn-primary\" ' +
            'onclick=\"createGuest(' + segmentID + ')\">Submit</button>');

    });

    refreshGuestList(segmentID);

}

function guestUpdate(guestID,segmentID) {

    var data = $("#update-form").serialize();
    data = data + '&gId=' + guestID;

    var errorDiv = $('#errorDiv');
    var updateBtn = $('#updateBtn');
    var backBtn = $('#cancelBtn');
    var deleteBtn = $('#deleteBtn');

    //alert(data);

    $.ajax({

        type: 'POST',
        async: true,
        url: 'inc/src/ajax.update-guest.php',
        data: data,
        dataType: 'json',
        success: function (data) {

            console.log(data);


            $(updateBtn).attr('disabled', 'disabled');
            $(backBtn).attr('disabled', 'disabled');
            $(deleteBtn).attr('disabled', 'disabled');
            $(errorDiv).html('<div class="alert alert-info">Attempting update ...</div>')
            $(errorDiv).slideDown(200).delay(200);

            setTimeout(function () {

                if (data.status === 'success') {

                    $(errorDiv).show(200, function () {
                        $(errorDiv).html('<div class="alert alert-success">' + data.message + '</div>')
                            .delay(3000).slideUp(100);

                    });
                } else {
                    $(errorDiv).show(200, function () {
                        $(errorDiv).html('<div class="alert alert-danger">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                    });
                }

                $(updateBtn).html('Update').removeAttr('disabled');
                $(backBtn).removeAttr('disabled');
                $(deleteBtn).removeAttr('disabled');

            }, 1500);
        },
        error: function () {
            alert('Error !')
        }
    });

    refreshGuestList(segmentID);


}

function guestDelete(guestID,segmentID) {



}

function selectGuest(guestID) {


}

/**
 *
 * Score CRUD functions
 *
 *
 */

function loadScoreCreate(segmentID) {

    $.get("inc/views/createScore.php", function (view) {
        $('#segment-body').html(view);
        $('#mode').html('Edit Mode').toggleClass('text-danger text-primary');
        $('#segment-title').html('Create Score');

        var buttons = $('#score-buttons');

        $(buttons).append('<button type=\"button\" id=\"closeBtn\" class=\"btn btn-secondary\" ' +
            'onclick=\"loadSegmentUpdate(' + segmentID + ')\">Back</button>');

        $(buttons).append('<button type=\"button\" id=\"submitBtn\" class=\"btn btn-primary\" ' +
            'onclick=\"createScore(' + segmentID + ')\">Submit</button>');

    });

    refreshScoreList(segmentID);

}

function loadScoreUpdate(scoreID,segmentID) {

    var form_target_id = "update-form";

    $.ajax({
        type: 'POST',
        url: 'inc/src/ajax.main.score.php',
        data: {scoreId: scoreID},
        dataType: 'json',
        cache: false,
        success: function (data) {

            //alert("Status: " + data.status + "\nMessage: " + data.message);

            if (data.status === 'success') {
                $.get("inc/views/update.php", function (view) {
                    $('#segment-body').html(view);
                    $('#mode').html('Edit Mode').toggleClass('text-danger text-primary');
                    $('#segment-title').html('Update Score');

                    $('#alert').append('<div class="small text-right" id="hint">Update the Score Details</div>');


                    $.each(data.score, function (index, val) {
                        console.log(index, val);
                        var html = '<div class="form-group">\n' +
                            '<label for="' + val + '">' + index + '</label>\n' +
                            '<input type="text" class="form-control" name="' + index + '" id="' + index + '" value="' + val + '">\n' +
                            '</div>';
                        $('#' + form_target_id).append(html);
                    });

                    $('#guest-link').append('<a class=\"nav-link\" href=\"#\" onclick=\"loadGuestCreate(' + segmentID + ')\">Add Guest</a>');
                    $('#score-link').append('<a class=\"nav-link\" href=\"#\" onclick=\"loadScoreCreate(' + segmentID + ')\">Add Score</a>');

                    $('#' + form_target_id).append('<div class="d-flex justify-content-around"><button type="button" id="cancelBtn" class="btn btn-secondary btn-sm mr-2" onclick="refreshSegmentList(' + segmentID + ')">Back</button>' +
                        '<button type="button" id="updateBtn" class="btn btn-primary btn-sm mr-2" onclick="scoreUpdate(' + scoreID + ',' + segmentID + ')">Update</button>' +
                        '<button type="button" id="deleteBtn" class="btn btn-sm btn-danger" onclick="scoreDelete(' + scoreID + ',' + segmentID + ')">Delete</button></div>')

                })
            }
        },
        error: function () {
            alert('Error !')
        }

    })


}

function createScore(segmentID) {

    var errorDiv = $('#errorDiv');
    var submitBtn = $('#submitBtn');
    var closeBtn = $('#closeBtn');
    var form = $('#score-form');

    var data = $(form).serialize();
    data = data + '&segmentId=' + segmentID;

    //alert(data);

    $.ajax({

        type: 'POST',
        async: true,
        url: 'inc/src/ajax.create-score.php',
        data: data,
        dataType: 'json',
        success: function (data) {

            console.log(data);

            $(submitBtn).attr('disabled', 'disabled');
            $(closeBtn).attr('disabled', 'disabled');
            $(errorDiv).html('<div class="alert alert-info">Processing ...</div>')
            $(errorDiv).slideDown(200).delay(200);


            setTimeout(function () {

                if (data.status === 'success') {

                    $(errorDiv).show(200, function () {
                        $(errorDiv).html('<div class="alert alert-success">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                        $(form)[0].reset();

                    });
                } else {
                    $(errorDiv).show(200, function () {
                        $(errorDiv).html('<div class="alert alert-danger">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                    });
                }

                $(submitBtn).html('Create Another ?').removeAttr('disabled');
                $(closeBtn).removeAttr('disabled');


            }, 500);
        },
        error: function () {
            alert('Error !')
        }
    });

    refreshScoreList(segmentID);

}

function refreshScoreList(segmentID) {

    var div_target_id = 'guest-list';

    $('#guest-title').html('Scores');

    $.ajax({
        type: 'POST',
        url: 'inc/src/ajax.get-scores.php',
        data: {segmentId: segmentID},
        dataType: 'json',
        cache: false,
        success: function (data) {

            console.log(data);

            //alert("Status: " + data.status + "\nMessage: " + data.message);

            setTimeout(function () {
                if (data.status === 'success') {
                    $('#' + div_target_id).html('');
                    $.each(data.scores, function (index, val) {
                        console.log(index, val);
                        $('#' + div_target_id).append('<div onclick="if(event.altKey){loadScoreUpdate(' + val.score_id + ',' + segmentID +')}" id="score' + val.score_id + '" class="list-group-item list-group-item-action" data-toggle="list">' + val.score_name + '</div>');
                    });

                } else {

                    $('#' + div_target_id).slideDown(200, function () {
                        $('#' + div_target_id).html('<div class="alert alert-danger">' + data.message + '</div>')

                    });
                }
            }, 500);
        },
        error: function () {
            alert('Error !')
        }


    });

}

function scoreUpdate(scoreID,segmentID){

    var data = $("#update-form").serialize();
    data = data + '&scId=' + scoreID;

    var errorDiv = $('#errorDiv');
    var updateBtn = $('#updateBtn');
    var backBtn = $('#cancelBtn');
    var deleteBtn = $('#deleteBtn');

    //alert(data);

    $.ajax({

        type: 'POST',
        async: true,
        url: 'inc/src/ajax.update-score.php',
        data: data,
        dataType: 'json',
        success: function (data) {

            console.log(data);


            $(updateBtn).attr('disabled', 'disabled');
            $(backBtn).attr('disabled', 'disabled');
            $(deleteBtn).attr('disabled', 'disabled');
            $(errorDiv).html('<div class="alert alert-info">Attempting update ...</div>')
            $(errorDiv).slideDown(200).delay(200);

            setTimeout(function () {

                if (data.status === 'success') {

                    $(errorDiv).show(200, function () {
                        $(errorDiv).html('<div class="alert alert-success">' + data.message + '</div>')
                            .delay(3000).slideUp(100);

                    });
                } else {
                    $(errorDiv).show(200, function () {
                        $(errorDiv).html('<div class="alert alert-danger">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                    });
                }

                $(updateBtn).html('Update').removeAttr('disabled');
                $(backBtn).removeAttr('disabled');
                $(deleteBtn).removeAttr('disabled');

            }, 1500);
        },
        error: function () {
            alert('Error !')
        }
    });

    refreshScoreList(segmentID);

}

function scoreDelete(scoreID,segmentID){



}
