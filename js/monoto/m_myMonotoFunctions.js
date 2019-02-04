// -----------------------------------------------------------------------------
// Ask the user if he reallys wants to delete all his events
// -----------------------------------------------------------------------------
function deleteAllMyUserEvents()
{
    console.log("deleteAllMyUserEvents ::: Dialog to delete all user events");

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
                $.cookie("lastAction", "Deleted all your event-entries."); // store last Action in cookie
                noty({text: "Deleted all events from log", type: "success"});
                location.reload();
            }
        },
        {addClass: "btn btn-danger", text: "Cancel", onClick: function($noty) {
            $noty.close();
            noty({text: "Aborted", type: "error"});
        }
    }
]
});
}


// -----------------------------------------------------------------------------
// Ask the user if he reallys wants to delete all his notes
// -----------------------------------------------------------------------------
function deleteAllMyUserNotes()
{
    console.log("deleteAllMyUserNotes ::: Dialog to delete all user notes");

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
                $.cookie("lastAction", "Deleted all your notes."); // store last Action in cookie
                noty({text: "Deleted all notes", type: "success"});
                location.reload();
            }
        },
        {
            addClass: "btn btn-danger", text: "Cancel", onClick: function($noty)
            {
                $noty.close();
                noty({text: "Aborted", type: "error"});
            }
        }
    ]
    });
}
