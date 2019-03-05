/** @namespace */
 var notesKeyHandler = {};

document.onkeyup = KeyCheck;


/**
 * @description general key-press handler for notes
 * @memberof notesKeyHandler
 * @event KeyUp
 * @param {event} e - Event / Key
 */
function KeyCheck(e)
{
    var KeyID = (window.event) ? event.keyCode : e.keyCode;

    console.log("KeyCheck ::: Key press: " + KeyID);

    switch(KeyID)
    {
        // ESC - reset the UI
        case 27:
            resetNotesUI();
            break;

        // Arrow Up
        case 38:
            // specialcase: if focus in search -> jump to first record in table
            if(document.activeElement.id === "searchField")
            {
                selectPreviousDataTableRow();
            }
            break;

        // Arrow Down
        case 40:
            // specialcase: if focus in search -> jump to first record in table
            if(document.activeElement.id === "searchField")
            {
                selectNextDataTableRow();
            }
            break;

        // DEL - delete selected note & reloads page
        case 46:
            var deleteID = $("#noteID").val();
            if(deleteID !== "")
            {
                deleteNote(); // execute delete function
            }
            break;


        // F2 - trigger maximize editor to fullscreen
        case 113:
            var noteID = $("#noteID").val();
            if(noteID !== "")
            {
                CKEDITOR.instances.editor1.execCommand( "maximize" );
            }
            else
            {
                console.log("nothing to mazimize - as no note is selected");
            }
            break;

        // F5 - Reload main page
        case 116:
            reloadCurrentPage(); // execute reload function
            break;

        // F9 - save
        case 120:
            saveNote();
            break;

    } // end switch
} // end function keycheck
