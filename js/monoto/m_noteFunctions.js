// -----------------------------------------------------------------------------
// UI: init the notes view
// -----------------------------------------------------------------------------
function onReady()
{
    initialLoad = true;

    var functionName = "onReady";
    logToConsole(functionName, "Starting to load the site");

    // Show last action if there is one stored in the cookie
    showLastActionViaCookie();

    // CKEditor
    //
    initCKEditor();
    saveCKEditorHeightOnChange();

    // DataTable
    //
    initDataTable(); // initialize the DataTable
    // Add a click handler to the rows - this could be used as a callback
    $("#example tbody").click(function(event)
    {
        $(oTable.fnSettings().aoData).each(function ()
        {
            $(this.nTr).removeClass("row_selected");
        });
        $(event.target.parentNode).addClass("row_selected");

        //disableNoteSavingButton(); // disable button (no changes yet)
        $("#bt_deleteNote").prop("disabled",false); // enable the delete button
        $("#noteTitle").prop("disabled",false); // enable note title field
    });

    /*
    // doubleclick listener for datatable
    $("table tr").dblclick(function ()
    {
        logToConsole(functionName, "Doubleclick detected");
    });
    */

    // select a row, highlight it and get the data
    $("table tr").click(function ()
    {
        clickedTableID = $(this).closest("table").attr("id"); // check clicksource
        if(clickedTableID === "example") // trigge only datatable
        {
            // Get position of current data
            var sData = oTable.fnGetData( this );
            var aPos = oTable.fnGetPosition(this);

            // Get data array for this row
            //var aData = oTable.fnGetData( aPos[1] );
            curRow =sData[0];
            rowCount = oTable.fnSettings().fnRecordsTotal();
            currentRow = rowCount - curRow -1;

            // get amount of records after filter
            amountOfRecordsAfterFilter = oTable.fnSettings().fnRecordsDisplay();
            curRow =sData[1];

            // get all currently visible rows
            var filteredrows = $("#example").dataTable()._('tr', {"filter": "applied"});

            // go over all rows and get selected row
            for ( var i = 0; i < filteredrows.length; i++ )
            {
                if(filteredrows[i][1]=== curRow)
                {
                    curSelectedTableRow=i;
                    logToConsole("", "Clicked row: "+curSelectedTableRow);
                }
            }

            logToConsole("", "Loading note ID: "+sData[1]+ " with title: '"+sData[2]+"'");

            // update UI
            $("#noteID").val(sData[1]);   // fill id field
            $("#noteTitle").val(sData[2]); // fill title field
            $("#noteVersion").val(sData[5]); // fill version -  is hidden


            // set focus
            $("#searchField").focus(); // as arrow up/down needs focus to work

            // load note to ckeditor
            logToConsole("", "Trying to load note content to editor");
            CKEDITOR.instances.editor1.setData(sData[3],function()
            {
                // set data of editor to noteContent
                CKEDITOR.instances.editor1.setData(sData[3]); // #201

                // enable ckeditor
                enableCKEditorWriteMode();

                // disable save button
                disableNoteSavingButton();

                // show cancel button
                $("#bt_cancelNewNote").show();

                // enable cancel button
                $("#bt_cancelNewNote").prop("disabled",false);

                // On Change listener for CKEditor
                // to detect if note content has changed after loading a note
                //
                CKEDITOR.instances.editor1.on("change", function(ev)
                {
                    //logToConsole("", "Created a OnChangeListener for CKEditor");

                    // check if button is disabled - if so - enable it
                    $("#bt_saveNote").is(":disabled");
                    {
                        enableNoteSavingButton();
                    }
                });

                // On Change Listener for noteTitle
                // to detect if note title has changed after loading a note
                $("#noteTitle").on("change keyup paste", function(){
                    //console.log("NoteTitle changed");

                    // Enable or disable the save button
                    // based on the fact if the noteTitle is still > 0
                    var value = document.getElementById("noteTitle").value;
                    if (value.length > 0)
                    {
                        // check if button is disabled - if so - enable it
                        $("#bt_saveNote").is(":disabled");
                        {
                            enableNoteSavingButton();
                        }
                    }
                    else
                    {
                        // check if button is enabled - if so - disable it
                        $("#bt_saveNote").is(":enabled");
                        {
                            disableNoteSavingButton();
                        }
                    }

                });
            });

            // update button visibilty
            // show some buttons
            $("#bt_deleteNote").show(); // show delete button
            $("#bt_saveNote").show(); // show save button

            // hide some buttons and fields
            $("#newNoteTitle").hide(); // hide field for new note name
            $("#bt_createNewNote").hide(); // hide button to create a new note
            $("#bt_prepareNoteCreation").hide(); // hide button to create a new note
        }
    });


    // Search field
    //
    // configure a new search field & its event while typing
    $('#searchField').keyup(function(e) // keyup triggers on each key
    //$('#searchField').keypress(function() // keypress ignores all soft-keys
    {
        logToConsole("", "Keypress in search field");

        var code = (e.keyCode || e.which);
        logToConsole("", "Key__: "+code);

        // ignore some keys aka do nothing if it's:
        // Otherwise this would result in focus lost of an already selected note
        //
        // - 37 = left arrow
        // - 39 = left arrow
        // - 112 = F1
        // - 113 = F2 (must be ignored - as it is no filter content)
        // - 114 = F3
        // - 115 = F4
        // - 116 = F5
        // - 117 = F6
        // - 118 = F7
        // - 119 = F8
        // - 120 = F9
        // - 121 = F10
        // - 122 = F11
        // - 123 = F12
        if(code === 37 || code === 39 || code === 112 || code === 113 || code === 114 || code === 115 || code === 116 || code === 117 || code === 118 || code === 119 || code === 120 || code === 121 || code === 122 || code === 123 )
        {
            return;
        }

        // assuming it is a key / input we want
        // start processing ....

        // search the table
        oTable.fnFilter( $(this).val() );

        // get amount of records after filter
        amountOfRecordsAfterFilter = oTable.fnSettings().fnRecordsDisplay();



        // if there are multiple or no notes available after search
        // - reset ckeditor
        // - hide delete button

        switch(amountOfRecordsAfterFilter)
        {
            case 0: // there is 0 record in selection after processing search
                console.log("Got 0 results");
                // reset noteID field
                $("#noteID").val("");
                // reset noteVersion field
                $("#noteVersion").val("");

                // hide all buttons
                hideAllButtons();

                // reset content of note-editor
                CKEDITOR.instances.editor1.setData("");
                break;

            case 1: // there is one record in selection after processing search
                console.log("Got 1 result");

                $("#example tbody tr:eq(0)").click(); // select the only record left after search
                $("#example tbody tr:eq(0)").addClass("row_selected"); // change background as well
                CKEDITOR.config.readOnly = false; // enable the editor

                break;


            default: // there is > 1 record in selection after processing search
                console.log("Got > 1 result");

                // check if there is already one note selected or not
                var table = $('#example').DataTable();
                if (table.rows( '.row_selected' ).any() )
                {
                    console.log(">1 result BUT 1 is selected");
                    // one record is selected - editor should not be modified
                }
                else
                {
                    console.log("> 1 Result, no record selected");

                    // no note is selected - editor should be resetted
                    CKEDITOR.instances.editor1.setData(""); // reset content of note-editor
                    document.getElementById("noteTitle").value = ""; // reset  note title

                    // hide some UI items
                    $("#bt_deleteNote").hide(); // hide delete button
                    $("#bt_saveNote").hide(); // hide save buttons

                    // disable some UI items
                    $("#noteTitle").prop("disabled",true);

                    // show some UI items
                    $("#bt_prepareNoteCreation").show(); // show new button

                    // reset some items
                    $("#noteID").val("");
                    $("#noteVersion").val("");
                }

                break;
        }
        // finished reacting on results after filtering

    });

    // only reset if the editor is not already showing a note
    /*
    var currentNoteID = $("#noteID").val();
    if(currentNoteID == "") // editor is not showing a note
    {
        resetNotesUI();
    }
    */

    resetNotesUI();


    // set focus to search (shouldnt be needed anymore due to autofocus)
    $("#searchField").focus();
}



// -----------------------------------------------------------------------------
// UI: hide all buttons
// -----------------------------------------------------------------------------
function hideAllButtons()
{
    var functionName = "hideAllButtons";
    logToConsole(functionName, "Hiding all buttons at once");

    // hide some UI items
    $("#bt_prepareNoteCreation").hide();
    $("#bt_cancelNewNote").hide();
    $("#bt_createNewNote").hide();
    $("#bt_deleteNote").hide(); // hide delete button
    $("#bt_saveNote").hide(); // hide save buttons
}


// -----------------------------------------------------------------------------
// UI: reset User-Interface
// resets the entire notes user-interface back to default
// -----------------------------------------------------------------------------
function resetNotesUI()
{
    var functionName = "resetNotesUI";
    logToConsole(functionName, "Resetting the Notes UserInterface");

    // UI
    //
    // show some elements
    $("#searchField").show(); // show search field
    $("#newNoteTitle").show();
    $("#bt_prepareNoteCreation").show();
    // hilde all UI buttons
    hideAllButtons();
    // show needed buttons
    $("#bt_prepareNoteCreation").show();
    // disable some items
    $("#noteTitle").prop("disabled",true);
    disableNoteSavingButton(); // button: save
    $("#bt_createNewNote").prop("disabled",true);
    $("#bt_cancelNewNote").prop("disabled",true);
    // enable some items
    $("#searchField").prop("disabled",false);
    // set some values
    $('#noteTitle').val("");
    $('#noteID').val(""); // hidden ID field
    $('#noteVersion').val(""); // hidden version field

    // dataTable
    //
    // show datatable
    $('#example').parents('div.dataTables_wrapper').first().show();
    // reset all datatable filter - to see all records of the table
    $('#example').dataTable().fnFilter('');
    // refresh the gui
    unmarkAllTableRows();
    // reset selected row number
    curSelectedTableRow=-1;

    // ckeditor 4.x
    //
    // reset editor content
    resetCKEditor();

    // test
    //document.activeElement.value = "";
    //document.activeElement.blur(); 								// lose focus from newNotetitle

    // set focus to search (shouldnt be needed anymore due to autofocus)
    $("#searchField").focus();
}







// -----------------------------------------------------------------------------
// UI: button: "new"
// Prepars the User-Interface for the note creation process
// -----------------------------------------------------------------------------
function prepareNewNoteStepOne()
{
    var functionName = "prepareNewNoteStepOne";
    logToConsole(functionName, "");

    // first of all ... reset the User-Interface
    resetNotesUI();

    // Search: disable and fadeout
    $("#searchField").prop("disabled",true); // disable search-field
    $("#searchField").fadeOut(1000); // hide search field
    //$("#searchField").hide(); // hide search field

    // note title: enable and focus
    $("#noteTitle").prop("disabled",false);  // enable note-title field
    $("#noteTitle").focus(); // set focus to note title

    // new note button:
    $("#bt_createNewNote").prop("disabled",true); // disable create-note button
    $("#bt_createNewNote").show(); // show create-note button
    $("#bt_prepareNoteCreation").hide();

    // enable cancel buttons
    $("#bt_cancelNewNote").prop("disabled",false);
    $("#bt_cancelNewNote").show();

    // hide save button
    $("#bt_saveNote").hide();

    // hide datatable
    $("#example").parents("div.dataTables_wrapper").first().hide();

    // Enable read-write of editor
    enableCKEditorWriteMode();
}


// -----------------------------------------------------------------------------
// UI: Triggered via "noteTitle" during "New Note creation"
// -----------------------------------------------------------------------------
function prepareNewNoteStepTwo()
{
    var functionName = "prepareNewNoteStepTwo";
    logToConsole(functionName, "");

    var noteTitle = document.myform.noteTitle.value;

    // if Save-Button is visible we can not be in note-creation mode
    if( $("#bt_saveNote").is(":visible"))
    {
        if(noteTitle.length > 0) //
        {
            enableNoteSavingButton();
        }
        else
        {
            disableNoteSavingButton();
        }
    }
    else // we are in Note-Creation mode
    {
        if(noteTitle.length > 0) // & save button nicht sichtbar
        {
            $("#bt_createNewNote").prop("disabled",false);
            $("#bt_createNewNote").show();
        }
        else
        {
            $("#bt_createNewNote").prop("disabled",true);
        }
    }
}


// -----------------------------------------------------------------------------
// UI: button "create"
// Prepare New Note creation Step 2
// -----------------------------------------------------------------------------
function createNewNote()
{
    var functionName = "createNewNote";
    logToConsole(functionName, "");

    var newNoteTitle = $("#noteTitle").val();

    // cleanup title - replace all characters except
    // - numbers
    // - letters
    // - space
    // - underscore,
    // - pipe and
    // - -
    newNoteTitle = newNoteTitle.replace(/[^a-zA-Z0-9-._äüößÄÜÖ?!|/ ]/g, '');

    // get note content if defined
    var newNoteContent = CKEDITOR.instances.editor1.getData();

    // cleanup note content replace...
    newNoteContent=newNoteContent.replace(/\'/g,'&#39;');

    // if we have a note title - create the new note (content is not needed so far)
    if (newNoteTitle.length > 0)
    {
        // check if user defined note-content or not. Add placeholder text if empty
        if(newNoteContent.length === 0)
        {
            // define dummy content for new note - as user didnt
            newNoteContent = "This is a <b>placeholder</b> for missing content while note-creation.";
        }

        // call create script
        $.post("inc/noteNew.php", { newNoteTitle: newNoteTitle, newNoteContent: newNoteContent } );

        // FIXME
        // Adding this 'alert' seems to fix a timing issues
        // which might happen with some setups/browsers i.e. Firefox in my case
        alert("Created the note: "+newNoteTitle);

        // store last Action in cookie
        $.cookie("lastAction", "Note "+newNoteTitle+" created.");
    }
    else
    {
        var n = noty({text: "Error: No note title", type: "error"});
    }
}



// -----------------------------------------------------------------------------
// UI: button "save"
// used to store changes on an selected note
// -----------------------------------------------------------------------------
function saveNote()
{
    var functionName = "saveNote";
    logToConsole(functionName, "");

    // get the note id
    var modifiedNoteID = document.myform.noteID.value;

    // get the note title
    var modifiedNoteTitle = document.myform.noteTitle.value;

    var modifiedNoteContent = CKEDITOR.instances.editor1.getData();

    // get current save-counter/version
    var modifiedNoteCounter = document.myform.noteVersion.value;

    // replace: '   with &#39; // cleanup note content
    modifiedNoteContent.replace(/'/g , "&#39;");

    // if we have a note-id - save the change to db
    if((modifiedNoteID.length > 0) && (modifiedNoteID != 'ID'))
    {
        $.post("inc/noteUpdate.php", { modifiedNoteID: modifiedNoteID, modifiedNoteTitle: modifiedNoteTitle, modifiedNoteContent: modifiedNoteContent, modifiedNoteCounter: modifiedNoteCounter  } );
        var n = noty({text: "Note saved", type: "success"});

        // store last Action in cookie
        $.cookie("lastAction", "Note "+modifiedNoteTitle+" saved.");

        // reload the notes page
        reloadAllNotes();
    }
    else // should never happen as the save button is not always enabled.
    {
        var n = noty({text: "Error: Missing ID reference", type: "error"});
    }
}


// -----------------------------------------------------------------------------
// UI: button "delete"
// Used to delete a selected note
// -----------------------------------------------------------------------------
function deleteNote()
{
    var functionName = "deleteNote";
    logToConsole(functionName, "");

    var deleteID = $("#noteID").val();
    var deleteTitle = $("#noteTitle").val();
    var deleteContent = $("#editor1").val();

    if ((deleteID.length > 0) && (deleteID != "" ))
    {
        // confirm dialog
        var x = noty({
            text: "Really delete this note?",
            type: "confirm",
            dismissQueue: false,
            layout: "topRight",
            theme: "defaultTheme",
            buttons: [
                {addClass: "btn btn-primary", text: "Ok", onClick: function($noty) {
                    $noty.close();
                    $.post("inc/noteDelete.php", { deleteID: deleteID, deleteTitle: deleteTitle, deleteContent: deleteContent } );
                    $.cookie("lastAction", "Note "+deleteID+" deleted.");  // store last Action in cookie

                    // delete it in ui
                    var anSelected = fnGetSelected( oTable );
                    oTable.fnDeleteRow( anSelected[0] );

                    // FIXME
                    // reload, as we would otherwise see
                    // an empty table in some cases (whyever)
                    location.reload();
                }
            },
            {
                addClass: "btn btn-danger", text: "Cancel", onClick: function($noty)
                {
                    $noty.close();
                    noty({text: "Aborted", type: "error"});

                    // reload the notes page
                    reloadAllNotes();
                }
            }
            ]
        });
}

// Data to identify note-to-delete are missing.
// should never happen as the delete button is disabled if no note is selected
// but can happen if user tried to delete via DEL key-press
else
{
    if(deleteID == "") // user most likely pressed DEL key without selected note
    {
        //var n = noty({text: "Error: Unable to delete as no note is selected", type: "error"});
        logToConsole(functionName, "Unable to delete a note as no note was selected.");
    }
    else
    {
        var n = noty({text: "Error: Unable to delete", type: "error"});
    }
}
}


// -----------------------------------------------------------------------------
// UI: button "Save"
// enables the save button after a note was changed
// -----------------------------------------------------------------------------
function enableNoteSavingButton()
{
    var functionName = "enableNoteSavingButton";
    logToConsole(functionName, "Enabling the save button for notes");

    // check if note title length is > 0
    // as saving is only allowed with a title
    var currentTitle = $("#noteTitle").val();
    if ( currentTitle.length > 0)
    {
        $("#bt_saveNote").prop("disabled",false);
        //$('#bt_saveNote').prop('title', 'Note changed, saving is enabled now');
    }
}


// -----------------------------------------------------------------------------
// UI: button "Save"
// disables the save button
// -----------------------------------------------------------------------------
function disableNoteSavingButton()
{
    var functionName = "disableNoteSavingButton";
    logToConsole(functionName, "Disabling the save button for notes");

    $("#bt_saveNote").prop("disabled",true);
    //$('#bt_saveNote').prop('title', 'Note unchanged, saving is disabled');
}


// -----------------------------------------------------------------------------
// UI: Reload all notes
// -----------------------------------------------------------------------------
function reloadAllNotes()
{
    var functionName = "reloadAllNotes";
    logToConsole(functionName, "");

    var loc = window.location;
    window.location = loc.protocol + "//" + loc.host + loc.pathname + loc.search;
}


// -----------------------------------------------------------------------------
// Cookie: is something written in the cookie as lastAction?
// if yes - show it as a noty notification & reset the value
// -----------------------------------------------------------------------------
function showLastActionViaCookie()
{
    var functionName = "showLastActionViaCookie";

    if($.cookie("lastAction") != "")
    {
        logToConsole(functionName, "Cookie contains: '" + $.cookie("lastAction") + "' as last action");

        var n = noty({text: $.cookie("lastAction"), type: 'notification'});

        // unset the cookie - as we want to display the lastAction only once
        $.cookie("lastAction", "");
    }
}


// -----------------------------------------------------------------------------
// DataTable: initialize Table
// -----------------------------------------------------------------------------
function initDataTable()
{
    var functionName = "initDataTable";
    logToConsole(functionName, "Initializing the DataTable");

    oTable = $('#example').dataTable(
        {
            "oLanguage": {
                "sProcessing": "<img src='../images/loading.gif'>",
                "bProcessing": true,
                "sEmptyTable": "You have 0 notes so far", // displayed if table is initial empty
                "sZeroRecords": "Found no matches for this search" // displayed if table is filtered to 0 matching records
            },
            "deferRender":    true,
            "dom": 'irt<"clear">',
            "paging":         false,
            "aaSorting": [[ 4, "desc" ]], // default sorting
            "aoColumnDefs": [ // disable sorting for all visible columns - as it breaks keyboard navigation
                { "bSortable": false, "aTargets": [ 1 ] },
                { "bSortable": false, "aTargets": [ 2 ] },
                { "bSortable": false, "aTargets": [ 3 ] },
                { "bSortable": false, "aTargets": [ 4 ] }
            ],
            "aoColumns"   : [ /* visible columns */
                { "bSearchable": false, "bVisible": false }, /* manually defined row id */
                { "bSearchable": false, "bVisible": false, "sWidth": "5%" }, /* note-id */
                { "bSearchable": true, "bVisible": true, "sWidth": "100%" },/* note-title */
                { "bSearchable": true, "bVisible": false},   /* note-content */
                { "bSearchable": false, "bVisible": false}, /* note-modification date */
                { "bSearchable": false, "bVisible": false}, /* save-count */
            ],
        }
    );

}


// -----------------------------------------------------------------------------
// DataTable: select and mark a single row in table
// -----------------------------------------------------------------------------
function selectAndMarkTableRow(currentRow)
{
    var functionName = "selectAndMarkTableRow";
    logToConsole(functionName, "Selecting and marking the row: "+currentRow);

    // select the top record
    $("#example tbody tr:eq("+currentRow+")").click();

    // change background as well (mark as selected)
    $("#example tbody tr:eq("+currentRow+")").addClass("row_selected");
}


// -----------------------------------------------------------------------------
// DataTable: unselect/unmark all rows in table
// -----------------------------------------------------------------------------
function unmarkAllTableRows()
{
    var functionName = "unmarkAllTableRows";
    logToConsole(functionName, "Removing the selected attribute from all table rows");

    $(oTable.fnSettings().aoData).each(function () // unselect all records
    {
        $(this.nTr).removeClass("row_selected");
    });
}


// -----------------------------------------------------------------------------
// DataTable:
// -----------------------------------------------------------------------------
function updateCurrentPosition(valueChange)
{
    var functionName = "updateCurrentPosition";
    logToConsole(functionName, "");

    // get amount of notes in table
    amountOfRecordsAfterFilter = oTable.fnSettings().fnRecordsDisplay();
    logToConsole(functionName, "notes in selection:"+amountOfRecordsAfterFilter);

    if (typeof curSelectedTableRow === "undefined")
    {
        //console.log("...initializing curSelectedTableRow to -1");
        curSelectedTableRow=-1;
    }

    curSelectedTableRow=curSelectedTableRow+valueChange;

    if(curSelectedTableRow < 0)  // doesnt make sense -> jump to last row
    {
        curSelectedTableRow=amountOfRecordsAfterFilter-1;
    }

    // doesnt make sense -> jump to last row
    if(curSelectedTableRow > amountOfRecordsAfterFilter-1)
    {
        curSelectedTableRow=0;
    }

    // update UI
    unmarkAllTableRows();
    selectAndMarkTableRow(curSelectedTableRow);
}


// -----------------------------------------------------------------------------
// DataTable: select next row
// -----------------------------------------------------------------------------
function selectNextRow()
{
    var functionName = "selectNextRow";
    logToConsole(functionName, "");
    updateCurrentPosition(1);
}


// -----------------------------------------------------------------------------
// DataTable: select the upper row
// -----------------------------------------------------------------------------
function selectUpperRow()
{
    var functionName = "selectUpperRow";
    logToConsole(functionName, "");
    updateCurrentPosition(-1);
}




// -----------------------------------------------------------------------------
// CKEditor: initialize Editor
// -----------------------------------------------------------------------------
function initCKEditor()
{
    var functionName = "initCKEditor";
    logToConsole(functionName, "Initializing the CKEditor");

    // Defining the editor height
    monotoEditorHeight = 300; // setting a default value, in case there is non stored in localStorage
    if(typeof(Storage)!=="undefined") // if localStorage is supported
    {
        monotoEditorHeight = window.localStorage.getItem("monotoEditorHeight");
        logToConsole(functionName, "CKEditor height is set in localStorage to: "+monotoEditorHeight);
    }
    else
    {
        logToConsole(functionName, "CKEditor height is set to default value of: "+monotoEditorHeight);
    }

    // START CKEDITOR
    CKEDITOR.replace( "editor1",
    {
        // show all editor commands
        //console.log(CKEDITOR.instances.editor1.commands);

        // key press handling
        on:
        {
            instanceReady: function()
            {
                CKEDITOR.instances.editor1.setReadOnly(true); // set RO mode
                CKEDITOR.config.toolbarCanCollapse = true; /* Enable collapse function for toolbar */
            },

            blur: function(event)
            {
                console.log("CKEditor lost focus");
                CKEDITOR.instances.editor1.execCommand( "toolbarCollapse", false ); // #241
            },


            focus: function(event)
            {
                console.log("CKEditor got focus");
                CKEDITOR.instances.editor1.execCommand( "toolbarCollapse", true ); // #241
            },


            key: function(e)
            {
                setTimeout(function()
                {
                    // #236 - KeyPress handling for ckeditor
                    //console.log("CKEditor: key pressed");

                    //console.log(e.data.keyCode);
                    switch(e.data.keyCode)
                    {
                        case 27: // ESC: - reset
                            logToConsole(functionName, "ESC");
                            //resetNotesUI();
                            // set focus back to searchField
                            $("#searchField").focus(); // as arrow up/down needs focus to work
                            break;

                        // F2 - trigger maximize editor to fullscreen
                        case 113:
                            logToConsole(functionName, "F2");
                            CKEDITOR.instances.editor1.execCommand( "maximize" );
                            break;

                        //default:
                            //logToConsole(functionName, "Unused Keypress in ckeditor");
                    }

                },1);
            } // end: key

        }, // end: on



        // other things
        //
        enterMode: CKEDITOR.ENTER_BR,
        height: monotoEditorHeight,
        toolbarCanCollapse: true, // enable collapse option
        toolbarStartupExpanded : false,  // define collapsed as default
        //removePlugins: 'elementspath',
        toolbar:
        [
            { name: 'tools',       items : [ 'Maximize' ] },
            { name: 'basicstyles', items : [ 'Bold','Italic','Strike','RemoveFormat' ] },
            { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','Blockquote' ] },
            { name: 'insert',      items : [ 'Link','Image','Table','HorizontalRule','SpecialChar' ] },
            { name: 'styles',      items : [ 'Styles','Format' ] },
            { name: 'document',    items : [ 'Source' ] }
        ]
    });

    printCKEditorStatus();
}


// -----------------------------------------------------------------------------
// CKEditor: handle editor height
// -----------------------------------------------------------------------------
function saveCKEditorHeightOnChange()
{
    var functionName = "saveCKEditorHeightOnChange";
    logToConsole(functionName, "Saving CKEditor height (to localStorage), after it has changed");

    CKEDITOR.on("instanceReady",function(ev)
    {
        ev.editor.on("resize",function(reEvent)
        {
            // get new height of editor window
            editorHeight = ev.editor.ui.space( "contents" ).getStyle( "height" );
            logToConsole("", "CKEditor resized to: "+editorHeight);

            //save to localstorage
            window.localStorage.setItem("monotoEditorHeight", editorHeight);
        });
    });

    printCKEditorStatus();
}


// -----------------------------------------------------------------------------
// CKEditor: enable READ-WRITE mode of the editor
// -----------------------------------------------------------------------------
function enableCKEditorWriteMode()
{
    var functionName = "enableCKEditorWriteMode";
    logToConsole(functionName, "CKEditor: Set editor to RW");

    //CKEDITOR.config.readOnly = false; // enable the editor
    //CKEDITOR.config.setReadOnly = false; // disable the editor
    CKEDITOR.instances.editor1.setReadOnly( false );

    printCKEditorStatus();
}


// -----------------------------------------------------------------------------
// CKEditor: enable READ-ONLY mode of the editor
// -----------------------------------------------------------------------------
function disableCKEditorWriteMode()
{
    var functionName = "disableCKEditorWriteMode";
    logToConsole(functionName, "CKEditor: Set editor to RO");

    CKEDITOR.config.readOnly = true;
    //CKEDITOR.config.setReadOnly = true;

    // throws error:
    // >> Uncaught TypeError: Cannot read property 'setReadOnly' of undefined
    // but is needed as disabling the editor on Reset/ESC only works like that
    //
    //CKEDITOR.instances["editor1"].setReadOnly( true );

    // setReadOnly = true works here only if its not initial
    if(initialLoad === false)
    {
        CKEDITOR.instances.editor1.setReadOnly( true );
    }
    initialLoad = false;

    //CKEDITOR.instances["editor1"].config.readOnly = true;


    /*
    CKEDITOR.on("loaded", function(event)
    {
        console.log("LOADED********************* REALLY SETTING READ_ONLY MODE");
        CKEDITOR.instances["editor1"].setReadOnly(true);
    });


    CKEDITOR.on("instanceReady", function(event)
    {
        console.log("********************* REALLY SETTING READ_ONLY MODE");
        CKEDITOR.instances["editor1"].setReadOnly(true);
    });
    */

    printCKEditorStatus();
}


// -----------------------------------------------------------------------------
// CKEditor: resets the content of the editor
// -----------------------------------------------------------------------------
function resetCKEditor()
{
    var functionName = "resetCKEditor";
    logToConsole(functionName, "Resetting CKEditor");

    // empty the editor
    CKEDITOR.instances.editor1.setData("");

    printCKEditorStatus();

    // Enable Read-Only mode
    disableCKEditorWriteMode();
}


// -----------------------------------------------------------------------------
// CKEditor: show the status of the editor
// -----------------------------------------------------------------------------
function printCKEditorStatus()
{
    var functionName = "printCKEditorStatus";
    logToConsole(functionName, "Editor status is: "+CKEDITOR.status);
}
