// -----------------------------------------------------------------------------
// Compare password change fields and enable or disable the update button
// -----------------------------------------------------------------------------
function validatePasswordChangeInput()
{
    console.debug("validatePasswordChangeInput ::: Started validating user input");

    if ($("#newPassword").val() === $("#newPasswordConfirm").val())
    {
            // password & passwordConfirm do match

            // now check if min length is reached
        if($("#newPassword").val().length > 7)
        {
                console.log("p.php ::: Passwords do match and min length is reached");
                $("#bt_doChangeUserPW").prop("disabled",false);
                // update status icon
                $("#passwordDiff").text("");
                $("#passwordDiff").append('<i class="fas fa-smile"></i>');
        }
        else
        {
            console.warn("p.php ::: Passwords do match but min length is not reached");
            $("#bt_doChangeUserPW").prop("disabled",true);
            // update status icon
            $("#passwordDiff").text("");
            $("#passwordDiff").append('<i class="far fa-frown"></i>');
        }
    }
    else
    {
        console.warn("p.php ::: Passwords do not match");
        $("#bt_doChangeUserPW").prop("disabled",true);
        // update status icon
        $("#passwordDiff").text("");
        $("#passwordDiff").append('<i class="far fa-frown"></i>');
    }

    console.debug("validatePasswordChangeInput ::: Finished validating user input");
}


// -----------------------------------------------------------------------------
// Ask the user if he reallys wants to delete all his events
// -----------------------------------------------------------------------------
function deleteAllMyUserEvents()
{
    console.debug("deleteAllMyUserEvents ::: Start Delete-All-My-User-Events-Dialog.");

    var x = noty({
        text: "Really delete all your events from log?",
        type: "confirm",
        dismissQueue: false,
        layout: "topRight",
        theme: "defaultTheme",
        buttons: [
            {addClass: "btn btn-primary", text: "Ok", onClick: function($noty) {
                $noty.close();
                $.post("inc/delMyUserEvents.php");
                createNoty("Deleted all events from log","success");
                location.reload();
            }
        },
        {addClass: "btn btn-danger", text: "Cancel", onClick: function($noty) {
            $noty.close();
            createNoty("Aborted","information");
        }
    }
    ]
    });

    console.debug("deleteAllMyUserEvents ::: Finished Delete-All-My-User-Events-Dialog.");
}


// -----------------------------------------------------------------------------
// Ask the user if he reallys wants to delete all his notes
// -----------------------------------------------------------------------------
function deleteAllMyUserNotes()
{
    console.debug("deleteAllMyUserNotes ::: Start Delete-All-My-User-Notes-Dialog.");

    var x = noty({
        text: "Really delete all your notes?",
        type: "confirm",
        dismissQueue: false,
        layout: "topRight",
        theme: "defaultTheme",
        buttons: [
        {
            addClass: "btn btn-primary", text: "Ok", onClick: function($noty)
            {
                $noty.close();
                $.post("inc/delMyUserNotes.php");
                createNoty("Deleted all notes","success");
                location.reload();
            }
        },
        {
            addClass: "btn btn-danger", text: "Cancel", onClick: function($noty)
            {
                $noty.close();
                createNoty("Aborted","information");
            }
        }
    ]
    });
    console.debug("deleteAllMyUserNotes ::: Finished Delete-All-My-User-Notes-Dialog.");
}


// -----------------------------------------------------------------------------
// Calculates password strength
// -----------------------------------------------------------------------------
function passwordStrength()
{
    console.debug("passwordStrength ::: Start");

    var strongRegex = new RegExp("^(?=.{10,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
    var mediumRegex = new RegExp("^(?=.{9,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
    var enoughRegex = new RegExp("(?=.{8,}).*", "g");

    curPasswordString = $("#newPassword").val();

    if (false == enoughRegex.test(curPasswordString)) // < 8 chars
    {
        console.log("passwordStrength ::: More characters");
        $('#passstrength').html('More Characters');
    }
    else if (strongRegex.test(curPasswordString))
    {
        console.log("passwordStrength ::: OK");
        $('#passstrength').className = 'ok';
        $('#passstrength').html('Strong!');
     }
     else if (mediumRegex.test(curPasswordString))
     {
        console.log("passwordStrength ::: alert");
        $('#passstrength').className = 'alert';
        $('#passstrength').html('Medium!');
     }
     else
     {
        console.log("passwordStrength ::: error");
        $('#passstrength').className = 'error';
        $('#passstrength').html('Weak!');
     }

    console.debug("passwordStrength ::: End");

    //return true;
}