function enterResults(segmentID) {

    $.get("inc/views/results.list.php", function (view) { //important
        $('#results-body').html(view);
        $('#results-title').html("Individual Results");
        $('#results-alert').append('<div class="small text-right" id="hint">Click + ALT to Edit</div>');
        $('#resultsBtns').append('<button type="button" id="cancelBtnResults" class="btn btn-secondary btn-sm mr-2" ' +
            'onclick="getResultsTable(' + segmentID + ')">Back</button>');

        var div_target_id = 'results-list';

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
                            $('#' + div_target_id).append('<div onclick="if(event.altKey){resultsMain(' + val.gId + ',' + segmentID +')}else{selectResult(' + val.gId + ')}" id="guest' + val.gId + '" class="list-group-item list-group-item-action" data-toggle="list">' + val.gn + '</div>');

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

    });


}

function selectResult (guestID){



}

function resultsMain(guestID,segmentID) {

    $.ajax({
        type: 'POST',
        url: 'inc/src/ajax.main.results.php',
        data: {guestId: guestID},
        dataType: 'json',
        cache: false,
        success: function (data) {

            //alert("Status: " + data.status + "\nMessage: " + data.message);

            if (data.status === 'success') {
                $.get("inc/views/results.update.php", function (view) {
                    $('#results-body').html(view);

                    $.each(data.guest, function(index,val){ //Info current record
                        console.log(index, val);
                        var html = val.guest_name;
                        $('#results-alert').append(html);
                    });

                    var form_target_id = $('#results-update-form');
                    var i = 0;

                    if (data.results.length === 0){

                        //alert("No results");
                        loadResultsCreate(guestID,segmentID);

                    } else {

                        $.each(data.results, function (index, val) {
                            console.log(index, val);
                            var html = '<div class="form-group">\n' +
                                '<label for="' + val.score_id + '">' + val.score_name + '</label>\n' +
                                '<input type="text" class="form-control" name="' + val.result_id + '" id="' + val.result_id + '" value="' + data.results['' + i + '']['score_result'] + '">\n' +
                                '</div>';
                            $(form_target_id).append(html);
                            i = i + 1;
                        });

                        $(form_target_id).append('<div class="d-flex justify-content-around"><button type="button" id="cancelBtnResults" class="btn btn-secondary btn-sm mr-2" onclick="enterResults(' + segmentID + ')">Back</button>' +
                            '<button type="button" id="updateBtnResults" class="btn btn-primary btn-sm mr-2" onclick="resultsUpdate(' + guestID + ',' + segmentID + ')">Update</button>')

                    }

                })
            }
        },
        error: function () {
            alert('Error !')
        }
    });

}

function loadResultsCreate (guestID, segmentID) {

    $.ajax({
        type: 'POST',
        url: 'inc/src/ajax.create-result-form.php',
        data: {guestId: guestID, segmentId: segmentID},
        dataType: 'json',
        cache: false,
        success: function (data) {

            //alert("Status: " + data.status + "\nMessage: " + data.message);

            if (data.status === 'success') {
                $.get("inc/views/results.update.php", function (view) {
                    $('#results-body').html(view);

                    $.each(data.guest, function(index,val){ //Info current record
                        console.log(index, val);
                        var html = val.guest_name;
                        $('#results-alert').append(html);
                    });

                    var form_target_id = $('#results-update-form');

                    $.each(data.scores, function (index, val) {
                        console.log(index, val);
                        var html = '<div class="form-group">\n' +
                            '<label for="' + val.score_id + '">' + val.score_name + '</label>\n' +
                            '<input type="text" class="form-control" name="' + val.score_id + '" id="' + val.score_id + '" value="0.000">\n' +
                            '</div>';
                        $(form_target_id).append(html);
                    });

                    $(form_target_id).append('<div class="d-flex justify-content-around"><button type="button" id="cancelBtnResults" class="btn btn-secondary btn-sm mr-2" onclick="enterResults(' + segmentID + ')">Back</button>' +
                        '<button type="button" id="updateBtnResults" class="btn btn-primary btn-sm mr-2" onclick="createResult(' + guestID + ',' + segmentID + ')">Create</button>')

                })
            }
        },
        error: function () {
            alert('Error !')
        }
    });


}

function createResult(guestID,segmentID) {

    var data = $("#results-update-form").serialize();

    var errorDiv = $('#resultsErrorDiv');
    var updateBtn = $('#updateBtnResults');
    var backBtn = $('#cancelBtnResults');

    //alert(data);

    $.ajax({

        type: 'POST',
        async: true,
        url: 'inc/src/ajax.create-result.php',
        data: data,
        dataType: 'json',
        success: function (data) {

            $(updateBtn).attr('disabled', 'disabled');
            $(backBtn).attr('disabled', 'disabled');
            $(errorDiv).html('<div class="alert alert-info">Submitting ...</div>');
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

            }, 1500);
        },
        error: function () {
            alert('Error !')
        }
    });

    return false;


}

function resultsUpdate(guestID,segmentID){

    var data = $("#results-update-form").serialize();
    data = data + '&segmentId=' + segmentID;
    data = data + '&guestId=' + guestID;

    var errorDiv = $('#resultsErrorDiv');
    var updateBtn = $('#updateBtnResults');
    var backBtn = $('#cancelBtnResults');

    //alert(data);

    $.ajax({

        type: 'POST',
        async: true,
        url: 'inc/src/ajax.update-result.php',
        data: data,
        dataType: 'json',
        success: function (data) {

            console.log(data);


            $(updateBtn).attr('disabled', 'disabled');
            $(backBtn).attr('disabled', 'disabled');
            $(errorDiv).html('<div class="alert alert-info">Attempting update ...</div>');
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

            }, 1500);
        },
        error: function () {
            alert('Error !')
        }
    });

    return false;


}

function resultsList() {

    /**
     *
     * Default results-body view
     * called by segmentList()
     *
     */

    $.get("inc/views/results.list.php", function (view) { //important
        $('#results-body').html(view);
        $('#results-title').html("Results");
        $('#results-alert').append('<div class="small text-right" id="hint">Choose a Segment</div>');
        var content = $('#results-list');

        $.ajax({
            type: 'POST',
            url: 'inc/src/ajax.get-segments.php',
            data: {name: "today"},
            dataType: 'json',
            cache: false,
            success: function (data) {

                setTimeout(function () {
                    if (data.status === 'success') {
                        $(content).html('');
                        $.each(data.segments, function (index, val) {
                            console.log(index, val);
                            $(content).append('<div onclick="getResultsTable(' + val.segment_id + ')" id="segment' + val.segment_id + '" class="list-group-item list-group-item-action" data-toggle="list">' + val.segment_name + '</div>');
                        });

                    } else {

                        $(content).slideDown(200, function () {
                            $(content).html('<div class="alert alert-danger">' + data.message + '</div>')
                                .delay(3000).slideUp(100);
                        });
                    }
                }, 500);
            },
            error: function () {
                alert('Error !')
            }

        });
    });


}

function getResultsTable(segmentID) {

    /**
     *
     * Loads basic AVG results table by name
     *
     */

    $.ajax({
        type: 'POST',
        url: 'inc/src/ajax.get-guest-results.php',
        data: {segmentId: segmentID},
        dataType: 'json',
        cache: false,
        success: function (data) {

            //alert("Status: " + data.status + "\nMessage: " + data.message);

            if (data.status === 'success') {
                $.get("inc/views/results.table.php", function (view) {
                    $('#results-body').html(view);
                    $('#results-title').html('Results Table');
                    $('#results-alert').append('<div class="small text-right" id="hint">List of Results</div>');
                    $('#a-link').append('<a class=\"nav-link\" href=\"#\" onclick="enterResults(' + segmentID + ')">Individual Results</a>');
                    $('#resultsBtns').append('<button type="button" id="cancelBtnResults" class="btn btn-secondary btn-sm mr-2" ' +
                        'onclick="resultsList()">Back</button>');

                    var table = $('#results-table');
                    var html = "<thead class='thead-dark'>" +
                        "<th scope='col'>Name</th>" +
                        "<th scope='col'>Horse</th>" +
                        "<th scope='col'>Average</th>" +
                        "</thead>" +
                        "<tbody>";
                    $(table).append(html);

                    $.each(data.guests, function (index, val) {
                        console.log(index, val);
                        $(table).append("<tr><th scope='row'>" + val.gn + "</th>" +
                            "<td>" + val.hn + "</td>" +
                            "<td>" + val.av + "</td></tr>");
                    });

                    $(table).append("</tbody>");

                })
            } else {
                $.get("inc/views/results.table.php", function (view) {
                    $('#results-body').html(view);
                    $("#results-title").html("No Results");
                    $('#resultsAlert').append('<div class="small text-right" id="hint">No Results Exist</div>');
                    $('#a-link').append('<a class=\"nav-link\" href=\"#\" onclick="enterResults(' + segmentID + ')">Update Results</a>');
                    $('#resultsBtns').append('<button type="button" id="cancelBtnResults" class="btn btn-secondary btn-sm mr-2" ' +
                        'onclick="resultsList()">Back</button>');

                });
            }
        },

        error: function () {
            alert('Error !');
        }

    });


}






