/*
KEY PRESS SCRIPT - via: http://www.geekpedia.com/tutorial138_Get-key-press-event-using-JavaScript.html
KeyPress Codes: http://www.cambiaresearch.com/articles/15/javascript-char-codes-key-codes
*/

// -----------------------------------------------------------------------------
// KeyPresses: genereal key press handler for notes
// -----------------------------------------------------------------------------
document.onkeyup = KeyCheck;
function KeyCheck(e)
{
    var functionName = "KeyCheck";

    var KeyID = (window.event) ? event.keyCode : e.keyCode;
    logToConsole(functionName, "___" + KeyID + "___");

    switch(KeyID)
    {
        // ESC - reset the UI
        case 27:
            logToConsole(functionName, "Keypress: ESC");
            resetNotesUI();
            break;

        // Arrow Up
        case 38:
            // specialcase: if focus in search -> jump to first record in table
            if(document.activeElement.id == "searchField")
            {
                logToConsole(functionName, "Keypress: Arrow Up");
                selectUpperRow();
            }
            break;

        // Arrow Down
        case 40:
            // specialcase: if focus in search -> jump to first record in table
            if(document.activeElement.id == "searchField")
            {
                logToConsole(functionName, "Keypress: Arrow Down");
                selectNextRow();
            }
            break;

        // DEL - delete selected note & reloads page
        case 46:
            console.log("Keypress: DEL (general)");
            var deleteID = $("#noteID").val();
            if(deleteID !== "")
            {
                deleteNote(); // execute delete function
            }
            break;


        // F2 - trigger maximize editor to fullscreen
        case 113:
            logToConsole(functionName, "Keypress: F2");
            console.log("F2 from this code is broken - as note content gets lost.");
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
            logToConsole(functionName, "Keypress: F5");
            reloadAllNotes(); // execute reload function
            break;


        // F9 - save
        case 120:
            logToConsole(functionName, "Keypress: F9");
            tryToSaveNote();
            break;


    } // end switch
} // end function keycheck


// -----------------------------------------------------------------------------
// KeyPresses: try to save the current selected monoto note
// -----------------------------------------------------------------------------
function tryToSaveNote()
{
    functionName = "tryToSaveNote";
    logToConsole(functionName, "");

    if ($("#bt_saveNote").is(":disabled"))
    {
        logToConsole(functionName, "Save button is disabled (ignoring save cmd).");
    }
    else
    {
        saveNote();    // execute save function
    }
}
