// -----------------------------------------------------------------------------
// UI: button "Save"
// disables the save button
// -----------------------------------------------------------------------------
function disableNoteSavingButton()
{
    console.log("disableNoteSavingButton ::: Disabling save note button");

    $("#bt_saveNote").prop("disabled",true);
}


// -----------------------------------------------------------------------------
// UI: button "Save"
// enables the save button if it has a title
// -----------------------------------------------------------------------------
function enableNoteSavingButton()
{
    console.log("enableNoteSavingButton ::: Enabling save note button");

    // check if note title length is > 0
    // as saving is only allowed with a title
    var currentTitle = $("#noteTitle").val();
    if ( currentTitle.length > 0)
    {
        $("#bt_saveNote").prop("disabled",false);
    }
}


// -----------------------------------------------------------------------------
// DataTable: unselect/unmark all rows in table
// -----------------------------------------------------------------------------
function unmarkAllTableRows()
{
    console.log("unmarkAllTableRows ::: Removing the selected attribute from all table rows");

    $(oTable.fnSettings().aoData).each(function () // unselect all records
    {
        $(this.nTr).removeClass("row_selected");
    });
}


// -----------------------------------------------------------------------------
// CKEditor: show the status of the editor
// -----------------------------------------------------------------------------
function printCKEditorStatus()
{
    console.log("printCKEditorStatus ::: CKEditor status is: "+CKEDITOR.status);
}


// -----------------------------------------------------------------------------
// CKEditor: initialize Editor
// -----------------------------------------------------------------------------
function initCKEditor()
{
    console.log("initCKEditor ::: Initializing the CKEditor");

    // Defining the editor height
    var monotoEditorHeight = 300; // setting a default value, in case there is non stored in localStorage
    if(typeof(Storage)!=="undefined") // if localStorage is supported
    {
        monotoEditorHeight = window.localStorage.getItem("monotoEditorHeight");
        console.log("initCKEditor ::: CKEditor height is set in localStorage to: "+monotoEditorHeight);
    }
    else
    {
        console.log("initCKEditor ::: CKEditor height is set to default value of: "+monotoEditorHeight);
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
                return;
            },

            /*
            // has issues: if focus is in editor and i click to notetitle, it jumps directly back to editor.
            blur: function(event)
            {
                console.log("initCKEditor ::: CKEditor lost focus");
                CKEDITOR.instances.editor1.execCommand( "toolbarCollapse", false ); // #241
                return;
            },
            */

            focus: function(event)
            {
                console.log("initCKEditor ::: CKEditor got focus");
                CKEDITOR.instances.editor1.execCommand( "toolbarCollapse", true ); // #241
                return;
            },

            resize: function(event)
            {
                // get new height of editor window
                editorHeight = event.editor.ui.space( "contents" ).getStyle( "height" );
                console.log("saveCKEditorHeightOnChange ::: CKEditor resized to: "+editorHeight);

                //save to localstorage
                window.localStorage.setItem("monotoEditorHeight", editorHeight);
            },

            key: function(e)
            {
                setTimeout(function()
                {
                    // #236 - KeyPress handling for ckeditor
                    //console.log(e.data.keyCode);
                    switch(e.data.keyCode)
                    {
                        // TAB -
                        case 9:
                            console.log("initCKEditor ::: Pressed TAB in CKEditor");
                            // if CREATE button is visible -> give it focus
                            if( $("#bt_createNewNote").is(":visible"))
                            {
                                $("#bt_createNewNote").focus();
                                console.log("initCKEditor ::: Setting focus to create button");
                                break;
                            }

                            // if SAVE button is visible -> give it focus
                            if( $("#bt_saveNote").is('[disabled=disabled]'))
                            {
                                $("#bt_saveNote").focus();
                                console.log("initCKEditor ::: Setting focus to save button");
                                break;
                            }

                            // any other case
                            $("#noteTitle").focus();
                            console.log("initCKEditor ::: Setting focus to note title");
                            break;

                        case 27: // ESC: - reset
                            console.log("initCKEditor ::: Pressed ESC in CKEditor");
                            //resetNotesUI();
                            // set focus back to searchField
                            $("#searchField").focus(); // as arrow up/down needs focus to work
                            break;

                        // F2 - trigger maximize editor to fullscreen
                        case 113:
                            console.log("initCKEditor ::: Pressed F2 in CKEditor");
                            CKEDITOR.instances.editor1.execCommand( "maximize" );
                            break;




                        //default:
                            //console.log("initCKEditor ::: Default case");
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
            { name: "tools",       items : [ "Maximize" ] },
            { name: "basicstyles", items : [ "Bold","Italic","Strike","RemoveFormat" ] },
            { name: "paragraph",   items : [ "NumberedList","BulletedList","-","Outdent","Indent","Blockquote" ] },
            { name: "insert",      items : [ "Link","Image","Table","HorizontalRule","SpecialChar" ] },
            { name: "styles",      items : [ "Styles","Format" ] },
            { name: "document",    items : [ "Source" ] }
        ]
    });

    printCKEditorStatus();
}


// -----------------------------------------------------------------------------
// CKEditor: handle editor height
// -----------------------------------------------------------------------------
function saveCKEditorHeightOnChange()
{
    /*
    console.log("saveCKEditorHeightOnChange ::: Saving CKEditor height (to localStorage), after it has changed");

    CKEDITOR.on("instanceReady",function(ev)
    {
        ev.editor.on("resize",function(reEvent)
        {
            // get new height of editor window
            editorHeight = ev.editor.ui.space( "contents" ).getStyle( "height" );
            //console.log("saveCKEditorHeightOnChange ::: CKEditor resized to: "+editorHeight);

            //save to localstorage
            window.localStorage.setItem("monotoEditorHeight", editorHeight);
        });
    });

    printCKEditorStatus();
    */
}


// -----------------------------------------------------------------------------
// CKEditor: enable READ-WRITE mode of the editor
// -----------------------------------------------------------------------------
function enableCKEditorWriteMode()
{
    console.log("enableCKEditorWriteMode ::: Trying to set CKEditor to RW");

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
    console.log("disableCKEditorWriteMode ::: Trying to set CKEditor to RO");

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

    printCKEditorStatus();
}


// -----------------------------------------------------------------------------
// CKEditor: resets the content of the editor
// -----------------------------------------------------------------------------
function resetCKEditor()
{
    console.log("resetCKEditor ::: Resetting CKEditor");

    // empty the editor
    CKEDITOR.instances.editor1.setData("");

    printCKEditorStatus();

    // Enable Read-Only mode
    disableCKEditorWriteMode();
}


// -----------------------------------------------------------------------------
// UI: hide all buttons
// -----------------------------------------------------------------------------
function hideAllButtons()
{
    console.log("hideAllButtons ::: Hiding all buttons at once");

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
    console.log("resetNotesUI ::: Starting to reset the Notes UserInterface");

    // UI
    //
    // show some elements
    $("#searchField").show();
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
    $("#searchField").val("");
    $("#noteTitle").val("");
    $("#noteID").val(""); // hidden ID field
    $("#noteVersion").val(""); // hidden version field

    // dataTable
    //
    // show datatable
    $("#example").parents("div.dataTables_wrapper").first().show();
    // reset all datatable filter - to see all records of the table
    $("#example").dataTable().fnFilter("");
    // refresh the gui
    unmarkAllTableRows();
    // reset selected row number
    curSelectedTableRow=-1;

    // ckeditor 4.xy
    //
    // reset editor content
    resetCKEditor();

    // set focus to search (shouldnt be needed anymore due to autofocus)
    $("#searchField").focus();

    console.log("resetNotesUI ::: Finished resetting the Notes UserInterface");
}


// -----------------------------------------------------------------------------
// UI: button: "new"
// Prepars the User-Interface for the note creation process
// -----------------------------------------------------------------------------
function prepareNewNoteStepOne()
{
    console.log("prepareNewNoteStepOne ::: Preparing note creation - Step 1");

    // first of all ... reset the User-Interface
    resetNotesUI();

    // Search: disable and fadeout
    $("#searchField").prop("disabled",true); // disable search-field
    $("#searchField").fadeOut(1000); // hide search field

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
    // Upate:
    // moved that to onChange of note title.
    // as enabling editor while creating a new note  makes only sense
    // if there is a noteTitle
    //
    //enableCKEditorWriteMode();
}


// -----------------------------------------------------------------------------
// UI: Triggered via "noteTitle" during "New Note creation"
// -----------------------------------------------------------------------------
function prepareNewNoteStepTwo()
{
    console.log("prepareNewNoteStepTwo ::: Preparing note creation - Step 2");

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
        if(noteTitle.length > 0) // user entered a new note title > enable create button & editor
        {
            $("#bt_createNewNote").prop("disabled",false);
            $("#bt_createNewNote").show();
            enableCKEditorWriteMode(); // Enable CKEDitor ReadWrite mode
        }
        else
        {
            $("#bt_createNewNote").prop("disabled",true);
            disableCKEditorWriteMode(); // Keep CKEditor in ReadOnly mode
        }
    }
}


// -----------------------------------------------------------------------------
// UI: button "create"
// Prepare New Note creation Step 2
// -----------------------------------------------------------------------------
function createNewNote()
{
    console.log("createNewNote ::: Creating a new note");

    var newNoteTitle = $("#noteTitle").val();

    // cleanup title - replace all characters except
    //
    // - numbers
    // - letters
    // - space
    // - underscore,
    // - pipe and
    // - -
    //newNoteTitle = newNoteTitle.replace(/[^a-zA-Z0-9-._äüößÄÜÖ?!|/ ]/g, "");
    //newNoteTitle = newNoteTitle.replace(/(^a-zA-Z0-9-._äüößÄÜÖ?!|/ )/g, "";);
    newNoteTitle = newNoteTitle.replace(/([^A-Za-z0-9äöüÄÖÜß.|!?_-])\w+ /g);

    // get note content if defined
    var newNoteContent = CKEDITOR.instances.editor1.getData();

    // cleanup note content replace...
    newNoteContent=newNoteContent.replace(/\'/g,"&#39;");

    // if we have a note title - create the new note (content is not needed so far)
    if (newNoteTitle.length > 0)
    {
        // check if user defined note-content or not. Add placeholder text if empty
        if(newNoteContent.length === 0)
        {
            newNoteContent = "This is a <b>placeholder</b> for missing content while note-creation.";
        }

        // FIXME
        // Adding this 'alert' seems to fix a timing issues as alert interrupts the execution
        // which might happen with some setups/browsers i.e. Firefox in my case
        //
        alert("Created the note: '" + newNoteTitle + "'.");

        // store last Action in cookie
        $.cookie("lastAction", "Note '"+newNoteTitle+"' created.");
    }
    else
    {
        var n = noty({text: "Error: No note title", type: "error"});
    }
}


// -----------------------------------------------------------------------------
// UI: button "create"
// Prepare New Note creation Step 2
// -----------------------------------------------------------------------------
function createNewNoteEnhanced()
{
    console.log("createNewNoteEnhanced ::: Creating a new note");

    var newNoteTitle = $("#noteTitle").val();

    // cleanup title - replace all characters except
    //
    // - numbers
    // - letters
    // - space
    // - underscore,
    // - pipe and
    // - -
    //newNoteTitle = newNoteTitle.replace(/[^a-zA-Z0-9-._äüößÄÜÖ?!|/ ]/g, "");
    //newNoteTitle = newNoteTitle.replace(/([^A-Za-z0-9äöüÄÖÜß.|!?_-])\w+ /g);
    newNoteTitle = newNoteTitle.replace(/([A-Za-z0-9äöüÄÖÜß._-|!?])\w+ /g, "");

    // get note content if defined
    var newNoteContent = CKEDITOR.instances.editor1.getData();

    // cleanup note content replace...
    newNoteContent=newNoteContent.replace(/\'/g,"&#39;");

    // if we have a note title - create the new note (content is not needed so far)
    if (newNoteTitle.length > 0)
    {
        // check if user defined note-content or not. Add placeholder text if empty
        if(newNoteContent.length === 0)
        {
            newNoteContent = "This is a <b>placeholder</b> for missing content while note-creation.";
        }


        var jqxhr = $.post( "inc/noteNew.php", { newNoteTitle: newNoteTitle, newNoteContent : newNoteContent}, function()
        {
            //alert( "success" );
            //alert("Created the note____________: '" + newNoteTitle + "'.");
        })
        .done(function()
        {
            //alert("Note creation done.");
            $.cookie("lastAction", "Note '<b>"+newNoteTitle+"</b>' created.");
            console.log("------------------------------");
            console.log("DONE");
            console.log(jqxhr.textStatus);
            console.log(jqxhr.data);
            console.log("------------------------------");
        })
        .fail(function(jqxhr, textStatus, errorThrown)
        {
            alert("Note creation failed.");
            $.cookie("lastAction", "Failed creating the note '"+newNoteTitle+"'.");

            console.log("------------------------------");
            console.log("FAIL");
            console.log(jqxhr);
            console.log(textStatus);
            console.log(errorThrown);
            console.log("------------------------------");
        })
        .always(function()
        {
            // doing nothing so far
        });


        // FIXME
        // Adding this 'alert' seems to fix a timing issues as alert interrupts the execution
        // which might happen with some setups/browsers i.e. Firefox in my case
        //
        //alert("Created the note: '" + newNoteTitle + "'.");

        // store last Action in cookie
        //$.cookie("lastAction", "Note '"+newNoteTitle+"' created.");
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
    console.log("saveNote ::: Saving an existing note");

    if ($("#bt_saveNote").is(":disabled"))
    {
        console.log("saveNote ::: Save button is disabled (ignoring save cmd).");
    }
    else
    {
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
            $.cookie("lastAction", "Note '"+modifiedNoteTitle+"' saved.");

            // reload the notes page
            reloadCurrentPage();
        }
        else // should never happen as the save button is not always enabled.
        {
            var n = noty({text: "Error: Missing ID reference", type: "error"});
        }
    }
}


// -----------------------------------------------------------------------------
// UI: button "delete"
// Used to delete a selected note
// -----------------------------------------------------------------------------
function deleteNote()
{
    console.log("deleteNote ::: Delete note dialog");

    var deleteID = $("#noteID").val();
    var deleteTitle = $("#noteTitle").val();
    var deleteContent = $("#editor1").val();

    if ((deleteID.length > 0) && (deleteID != "" ))
    {
        // confirm dialog
        var x = noty({
            text: "Do you really want to delete the note '" + deleteTitle +"'?",
            type: "confirm",
            dismissQueue: false,
            layout: "topRight",
            theme: "defaultTheme",
            buttons: [
                {addClass: "btn btn-primary", text: "Ok", onClick: function($noty) {
                    $noty.close();
                    $.post("inc/noteDelete.php", { deleteID: deleteID, deleteTitle: deleteTitle, deleteContent: deleteContent } );
                    $.cookie("lastAction", "Note '"+deleteID+"' deleted.");  // store last Action in cookie

                    // delete it in ui
                    var anSelected = fnGetSelected( oTable );
                    oTable.fnDeleteRow( anSelected[0] );

                    redrawTable();
                    resetNotesUI();
                }
            },
            {
                addClass: "btn btn-danger", text: "Cancel", onClick: function($noty)
                {
                    $noty.close();
                    noty({text: "Aborted", type: "error"});

                    redrawTable();
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
    if(deleteID === "") // user most likely pressed DEL key without selected note
    {
        console.log("deleteNote ::: Unable to delete a note as no note was selected.");
    }
    else
    {
        var n = noty({text: "Error: Unable to delete", type: "error"});
    }
}
}


// -----------------------------------------------------------------------------
// UI: Reload all notes
// -----------------------------------------------------------------------------
function reloadCurrentPage()
{
    console.log("reloadCurrentPage ::: Reloading the current page");

    var loc = window.location;
    window.location = loc.protocol + "//" + loc.host + loc.pathname + loc.search;
}



// -----------------------------------------------------------------------------
// Cookie: is something written in the cookie as lastAction?
// if yes - show it as a noty notification & reset the value
// -----------------------------------------------------------------------------
function showLastActionViaCookie()
{
    console.log("showLastActionViaCookie ::: Checking for last action cookie");

    if($.cookie("lastAction") !== "")
    {
        console.log("showLastActionViaCookie ::: Cookie contains: '" + $.cookie("lastAction") + "' as last action");

        var n = noty({text: $.cookie("lastAction"), type: 'notification'});

        // unset the cookie - as we want to display the lastAction only once
        $.cookie("lastAction", "");
    }
}


// -----------------------------------------------------------------------------
// DataTable: Redraw table
// -----------------------------------------------------------------------------
function redrawTable()
{
    console.log("redrawTable ::: Redrawing DataTable");

    var oTable = $('#example').dataTable();
    oTable.fnDraw();
}


// -----------------------------------------------------------------------------
// DataTable: initialize Table
// -----------------------------------------------------------------------------
function initDataTable()
{
    console.log("initDataTable ::: Initializing the DataTable");

    oTable = $("#example").dataTable(
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
    console.log("selectAndMarkTableRow ::: Selecting and marking the row: "+currentRow);

    // select the top record
    $("#example tbody tr:eq("+currentRow+")").click();

    // change background as well (mark as selected)
    $("#example tbody tr:eq("+currentRow+")").addClass("row_selected");
}


// -----------------------------------------------------------------------------
// DataTable: update the current selected row
// -----------------------------------------------------------------------------
function updateCurrentPosition(valueChange)
{
    console.log("updateCurrentPosition ::: Updating the current position in datatable");

    // get amount of notes in table
    var amountOfRecordsAfterFilter = oTable.fnSettings().fnRecordsDisplay();
    console.log("updateCurrentPosition ::: Currently showing " + amountOfRecordsAfterFilter + " notes in datatable.");

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
    console.log("selectNextRow ::: Selecting next row in dataTable");
    updateCurrentPosition(1);
}


// -----------------------------------------------------------------------------
// DataTable: select the upper row
// -----------------------------------------------------------------------------
function selectUpperRow()
{
    console.log("selectUpperRow ::: Selecting previous row in dataTable");
    updateCurrentPosition(-1);
}


// -----------------------------------------------------------------------------
// UI: init the notes view
// -----------------------------------------------------------------------------
function onReady()
{
    initialLoad = true;

    console.log("onReady ::: Starting to initializing the notes view");

    // Show last action if there is one stored in the cookie
    showLastActionViaCookie();

    // CKEditor
    //
    initCKEditor();
    //saveCKEditorHeightOnChange();

    // DataTable
    //
    initDataTable(); // initialize the DataTable
    var curSelectedTableRow = -1;
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
        console.log("onReady ::: DoubleClick on DataTable);
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
            var curRow =sData[0];
            var rowCount = oTable.fnSettings().fnRecordsTotal();
            currentRow = rowCount - curRow -1;

            // get amount of records after filter
            var amountOfRecordsAfterFilter = oTable.fnSettings().fnRecordsDisplay();
            curRow =sData[1];

            // get all currently visible rows
            var filteredrows = $("#example").dataTable()._("tr", {"filter": "applied"});

            // go over all rows and get selected row
            for ( var i = 0; i < filteredrows.length; i++ )
            {
                if(filteredrows[i][1]=== curRow)
                {
                    curSelectedTableRow=i;
                    console.log("onReady ::: Clicked row: "+curSelectedTableRow);
                }
            }

            console.log("onReady ::: Loading note ID: "+sData[1]+ " with title: '"+sData[2]+"'");

            // update UI
            $("#noteID").val(sData[1]);   // fill id field
            $("#noteTitle").val(sData[2]); // fill title field
            $("#noteVersion").val(sData[5]); // fill version -  is hidden


            // set focus
            $("#searchField").focus(); // as arrow up/down needs focus to work

            // load note to ckeditor
            console.log("onReady ::: Trying to load note content to editor");
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
                    console.log("onReady ::: Created a OnChangeListener for CKEditor");

                    // check if button is disabled - if so - enable it
                    if($("#bt_saveNote").is(":disabled"))
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
                        if($("#bt_saveNote").is(":disabled"))
                        {
                            enableNoteSavingButton();
                        }
                    }
                    else
                    {
                        // check if button is enabled - if so - disable it
                        if($("#bt_saveNote").is(":enabled"))
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
    $("#searchField").keyup(function(e) // keyup triggers on each key
    //$('#searchField').keypress(function() // keypress ignores all soft-keys
    {
        var code = (e.keyCode || e.which);

        console.log("onReady ::: Keypress: "+code);

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
            console.log("onReady ::: Ignoring some key inputs");
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
                console.log("onReady ::: Got > 1 result");

                // check if there is already one note selected or not
                var table = $("#example").DataTable();
                if (table.rows( '.row_selected' ).any() )
                {
                    console.log("onReady ::: > 1 result BUT 1 is selected");
                    // one record is selected - editor should not be modified
                }
                else
                {
                    console.log("onReady ::: > 1 result and no record selected");

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

    resetNotesUI();


    // set focus to search (shouldnt be needed anymore due to autofocus)
    $("#searchField").focus();
}
