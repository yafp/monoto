/** @namespace */
 var profile = {};

/**
 * @description Offer an option to delete all events from users account
 * @memberof profile
 * @author Florian Poeck <yafp@users.noreply.github.com>
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
            {addClass: "btn btn-primary", text: "Ok", onClick: function($noty) {
                $noty.close();
                $.post("inc/profileDeleteMyUserEvents.php");
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


/**
 * @description Offer an option to delete all notes from users account
 * @memberof profile
 * @author Florian Poeck <yafp@users.noreply.github.com>
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