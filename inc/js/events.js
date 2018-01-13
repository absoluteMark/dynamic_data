document.getElementById("demo").innerHTML = "Version 1.0.0 Alpha";

$(document).ready(function () {

    /**
     *
     * Get a list of events for the user to choose
     *
     * Function that gets the list of Events
     * for the home page and populates the option
     * values of the form
     *
     *
     * Fires when home.php page is loaded
     *
     * */

    var list_target_id = 'list-target';

    if ($('#eventModal').length) {

    } else {
        $.get("inc/modals/modal.create-event.php", function (data) {
            $('body').prepend(data);
        });
    }

    $.ajax({
        type: 'POST',
        url: 'inc/src/ajax.get-events.php',
        data: {name: "today"},
        datatype: 'json',
        cache: false,
        success: function (data) {
            //alert("Status: " + data.status + "\nMessage: " + data.events);
            $('#' + list_target_id).html('<option value="Choose">Choose event...</option>');
            $.each(data.events, function (index, val) {
                console.log(index, val);
                $('#' + list_target_id).append('<option value="' + val + '">' + val + '</option>');
            });

        },
        error: function () {
            alert('Error !')
        }
    });


});

$(document).ready(function () {

    /**
     *
     * Loads the event panel
     *
     * Fires when option box in the form
     * is selected, thereby changing the
     * properties of the FORM block with
     * id="list-target" in the <body> or <view>
     * of home.php.
     *
     *
     *
     */


    $("select#list-target").change(function () {
        var selectedEvent = $("#list-target option:selected").val();

        //alert("You have selected the event - " + selectedEvent);

        $.ajax({
            type: 'GET',
            url: 'inc/src/ajax.main.event.php',
            data: {name: selectedEvent},
            datatype: 'json',
            cache: false,
            success: function (data) {
                //alert("Status: " + data.status + "\nEvent ID: " + data.events['event_id']);

                $.get("inc/views/event-home.php", function (data) {
                    $('#main').html(data);
                });


            },
            error: function () {
                alert('Error !')
            }
        });

        segmentList()

    });
});

function submitEvent() {

    /**
     *
     *
     * Creates a new event
     *
     * @returns {boolean}
     *
     */

    var data = $("#event-form").serialize();


    $.ajax({

        type: 'POST',
        async: true,
        url: 'inc/src/ajax.create-event.php',
        data: data,
        dataType: 'json',
        success: function (data) {

            console.log(data);

            $('button#submitBtn1').html('Registering event ...').attr('disabled', 'disabled');
            $('button#closeBtn1').attr('disabled', 'disabled');
            $('.modal-body').css('opacity', '.5');

            setTimeout(function () {

                if (data.status === 'success') {

                    $('#errorDiv1').slideDown(200, function () {
                        $('#errorDiv1').html('<div class="alert alert-info">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                        $("#event-form")[0].reset();
                    });
                } else {
                    $('#errorDiv1').slideDown(200, function () {
                        $('#errorDiv1').html('<div class="alert alert-danger">' + data.message + '</div>')
                            .delay(3000).slideUp(100);
                    });
                }

                $('button#submitBtn1').html('Create Another ?').removeAttr('disabled');
                $('button#closeBtn1').removeAttr('disabled');
                $('.modal-body').css('opacity', '');

            }, 500);
        },
        error: function () {
            alert('Error !')
        }
    });
    return false;
}




