function enterResults(segmentID){

    var div_target_id = 'results-list';


    $.ajax({
        type: 'POST',
        url: 'inc/src/ajax.get-contestants.php',
        data: {segmentID: segmentID},
        datatype: 'json',
        cache: false,
        success: function (data) {

            console.log(data);

            //alert("Status: " + data.status + "\nMessage: " + data.message);

            setTimeout(function () {
                if (data.status === 'success') {
                    $('#' + div_target_id).html('').slideDown();
                    $.each(data.joined, function (index, val) {
                        console.log(index, val);
                        $('#' + div_target_id).append('<div onclick="resultsMain(' + val.contestant_id + ')" class="list-group-item list-group-item-action" data-toggle="list">' + val.contestant_name + '</div>');
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

function resultsMain(contestant_id) {

    var div_target_id = 'scoreErrorDiv';

    $.ajax({
        type: 'GET',
        url: 'inc/src/ajax.main.results.php',
        data: {contestantId: contestant_id},
        datatype: 'json',
        cache: false,
        success: function (data) {

            //alert("Status: " + data.status + "\nMessage: " + data.message);

            if (data.status === 'success') {
                $.get("inc/views/results.php", function (view) {
                    $('#results-body').html(view);

                    var form_target_id = 'results-form';
                    var i = 0;

                    $.each(data.results, function (index, val) {
                        console.log(index, val);
                        var html = '<div class="form-group">\n' +
                            '<label for="' + val.scorename_id + '">' + val.scorename_name + '</label>\n' +
                            '<input type="text" class="form-control" name="' + val.scorename_id + '" id="' + val.scorename_id + '" placeholder="' + data.results['' + i + '']['score_result'] + '">\n' +
                            '</div>';
                        $('#' + form_target_id).append(html);
                        i = i + 1;
                    });
                    /**
                     *
                     * If result already populated, then submit button must trigger
                     * update results - not create results
                     *
                     *
                     */

                    $('#' + form_target_id).append('<button type="button" id="submitScore" class="btn btn-danger mr-2" onclick="createResult()">Submit</button>');
                    $('#' + form_target_id).append('<button type="button" id="updateScore" class="btn btn-primary" onclick="eventMain()" disabled>Update</button>');

                    $("#card-title").html('Contestant Overlay');
                    $("#segmentLink").remove();
                    $("#card-menu").append("<a href='#' class='card-link' id='resultsLink' onclick='segmentMain(" + data.results['segment_id'] + ")'>Back</a>");
                    $("#card-header").append("<h6 class=\"card-title text-right text-success\" id='resultsName'>Rider: " + data.results[0]['contestant_name'] + "</h6>");
                    $("#card-header").append("<h6 class=\"card-title text-right text-success\" id='resultsHorse'>Horse: " + data.results[0]['horse_name'] + "</h6>");
                    $("#card-footer").append("<span class=\"sm text-success\" id='resultsID'> Contestant ID: " + data.results[0]['contestant_id'] + "</span>");
                })
            } else {
                $.get("inc/views/results.php", function (view) {
                    $('#card-body').html(view);
                    $("#resultsSubtitle").html("No Results");
                    $('#' + div_target_id).slideDown(200, function () {
                        $('#' + div_target_id).html('<div class="alert alert-danger">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                    });
                })
            }

        },
        error: function () {
            alert('Error !')
        }
    });

}

function getScorenames() {


    var div_target_id = 'scorenames-list';


    $.ajax({
        type: 'POST',
        url: 'inc/src/ajax.get-scorenames.php',
        data: {name: "today"},
        datatype: 'json',
        cache: false,
        success: function (data) {

            console.log(data);

            //alert("Status: " + data.status + "\nMessage: " + data.message);

            setTimeout(function () {
                if (data.status === 'success') {
                    $('#' + div_target_id).html('');
                    $.each(data.events, function (index, val) {
                        console.log(index, val);
                        $('#' + div_target_id).append('<div class="list-group-item list-group-item-action">' + val.scorename_name + '</div>');
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

    getJudges();

}

function getJudges() {


    var div_target_id = 'judges-list';


    $.ajax({
        type: 'POST',
        url: 'inc/src/ajax.get-judges.php',
        data: {name: "today"},
        datatype: 'json',
        cache: false,
        success: function (data) {

            console.log(data);

            //alert("Status: " + data.status + "\nMessage: " + data.message);

            setTimeout(function () {
                if (data.status === 'success') {
                    $('#' + div_target_id).html('');
                    $.each(data.events, function (index, val) {
                        console.log(index, val);
                        $('#' + div_target_id).append('<div class="list-group-item list-group-item-action">' + val.judge_name + '</div>');
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

function createScorename() {

    var data = $("#scorename-form").serialize();

    //alert(data);

    $.ajax({

        type: 'POST',
        async: true,
        url: 'inc/src/ajax.create-scorename.php',
        data: data,
        dataType: 'json',
        success: function (data) {

            console.log(data);

            $('button#submitBtn4').html('Creating scorename ...').attr('disabled', 'disabled');
            $('button#closeBtn4').attr('disabled', 'disabled');
            $('.modal-body').css('opacity', '.5');

            setTimeout(function () {

                if (data.status === 'success') {

                    $('#errorDiv4').slideDown(200, function () {
                        $('#errorDiv4').html('<div class="alert alert-info">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                        $("#scorename-form")[0].reset();
                    });
                } else {
                    $('#errorDiv4').slideDown(200, function () {
                        $('#errorDiv4').html('<div class="alert alert-danger">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                    });
                }

                $('button#submitBtn4').html('Create Another ?').removeAttr('disabled');
                $('button#closeBtn4').removeAttr('disabled');
                $('.modal-body').css('opacity', '');

            }, 500);
        },
        error: function () {
            alert('Error !')
        }
    });
    return false;


}

function createResult() {

    var data = $("#results-form").serialize();

    //alert(data);

    $.ajax({

        type: 'POST',
        async: true,
        url: 'inc/src/ajax.create-result.php',
        data: data,
        dataType: 'json',
        success: function (data) {

            console.log(data);

            //alert(data.segment_id);

            segmentMain(data.segment_id);


        },
        error: function () {
            $('#scoreErrorDiv').slideDown(200, function () {
                $('#scoreErrorDiv').html('<div class="alert alert-danger">Could not connect to Database !</div>')
                    .delay(3000).slideUp(100);
            });
        }

    });

    return false;


}
