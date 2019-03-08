/** @namespace */
 var profile = {};


/**
 * @description initializes the profile-events DataTable
 * @memberof profile
 */
function initProfileEventsDataTable()
{
    console.debug("initProfileEventsDataTable ::: Start");

     // init
     $('#myDataTable').DataTable( {
         "bSort": false, // dont sort - trust the sql-select and its sort-order
         "sPaginationType": "simple_numbers",
         "iDisplayLength" : 10,
         "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
     } );

     console.log("initProfileEventsDataTable ::: Finished initializing the events DataTable");

     console.debug("initProfileEventsDataTable ::: Stop");
}


/**
 * @description realizes a password change
 * @memberof profile
 */
function doChangeProfilePassword()
{
    console.debug("doChangeProfilePassword ::: Start");

    var password = $("#newPassword").val();

    var jqxhr = $.post( "inc/profileChangeUserPW.php", { password: password }, function()
    {
        console.log("doChangeProfilePassword ::: successfully updated user password");
    })
    .done(function()
    {
        console.log("doChangeProfilePassword ::: done");
        createNoty("Successfully changed password","success");

        // reset password fields
        $("#newPassword").val("");
        $("#newPasswordConfirm").val("");

        // disable button
        $("#bt_continue").prop("disabled",true);
    })
    .fail(function(jqxhr, textStatus, errorThrown)
    {
        console.error("doChangeProfilePassword ::: $.post failed");
        console.log(jqxhr);
        console.log(textStatus);
        console.log(errorThrown);

        createNoty("Updating password failed", "error");
    })
    .always(function()
    {
        // doing nothing so far
    });

    console.debug("doChangeProfilePassword ::: Stop");
}


/**
 * @description realizes a profile language change
 * @memberof profile
 */
function doChangeProfileLanguage()
{
    console.debug("doChangeProfileLanguage ::: Start");

    var language = $( "#s_languageSelector option:selected" ).text();
    console.log("doChangeProfileLanguage ::: User selected new language:" + language);

    var jqxhr = $.post( "inc/profileChangeUserLanguage.php", { language: language }, function()
    {
        console.log("doChangeProfileLanguage ::: successfully updated user password");
    })
    .done(function()
    {
        console.log("doChangeProfileLanguage ::: done");
        createNoty("Successfully changed language","success");
    })
    .fail(function(jqxhr, textStatus, errorThrown)
    {
        console.error("doChangeProfileLanguage ::: $.post failed");
        console.log(jqxhr);
        console.log(textStatus);
        console.log(errorThrown);

        createNoty("Changing language failed", "error");
    })
    .always(function()
    {
        // doing nothing so far
    });

    console.debug("doChangeProfileLanguage ::: Stop");
}


/**
 * @description deletes all events from the current user account
 * @memberof profile
 */
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
            {
                addClass: "btn btn-primary", text: "Ok", onClick: function($noty)
                {
                    $noty.close();
                    $.post("inc/profileDeleteMyUserEvents.php");
                    createNoty("Deleted all events from log","success");
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

    console.debug("deleteAllMyUserEvents ::: Finished Delete-All-My-User-Events-Dialog.");
}


/**
 * @description deletes all notes from the current user account
 * @memberof profile
 */
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
                $.post("inc/profileDeleteMyUserNotes.php");
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
