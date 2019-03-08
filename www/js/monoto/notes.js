/** @namespace */
 var notes = {};


/**
 * @name disableNoteSavingButton
 * @description disables the save button
 * @memberof notes
 */
function disableNoteSavingButton()
{
    console.log("disableNoteSavingButton ::: Disabling save note button");

    $("#bt_saveNote").prop("disabled",true);
}


/**
 * @name enableNoteSavingButton
 * @description enables the save button if it has a title (button "Save")
 * @memberof notes
 */
function enableNoteSavingButton()
{
    console.log("enableNoteSavingButton ::: Enabling save note button");

    // check if note title length is > 0, as saving is only allowed with a title
    var currentTitle = $("#noteTitle").val();
    if ( currentTitle.length > 0)
    {
        $("#bt_saveNote").prop("disabled",false);
        console.log("enableNoteSavingButton ::: Enabled the save note button");
    }
    else
    {
        console.log("enableNoteSavingButton ::: Save button was already enabled, nothing to do.");
    }
}


/**
 * @name unmarkAllDataTableRows
 * @description unselect/unmark all rows in DataTable
 * @memberof notes
 */
function unmarkAllDataTableRows()
{
    console.log("unmarkAllDataTableRows ::: Removing the selected attribute from all table rows");

    if(initialLoad === false) // only if it isnt the initial load of the page
    {
        oTable.rows().every( function ( rowIdx, tableLoop, rowLoop )
        {
            var data = this.data();
            //console.log(data);

            // remove class row_selected
            $("#myDataTable tbody tr:eq("+rowIdx+")").removeClass("row_selected");

            // remove class selected
            $("#myDataTable tbody tr:eq("+rowIdx+")").removeClass("selected");
        } );

        // reset info about amount of records
        var table = $('#myDataTable').DataTable();
        table.rows().deselect();
    }
}


/**
 * @name selectSingleDataTableRow
 * @description selects and clicks a single row in the notes DataTable
 * @memberof notes
 * @param {string} rowNumber - the number of the selected row
 */
function selectSingleDataTableRow(rowNumber)
{
    console.debug("selectSingleDataTableRow ::: Start");

    unmarkAllDataTableRows();

    console.log("selectSingleDataTableRow ::: Trying to click row: " + rowNumber );
    $(oTable.row(rowNumber).node()).click();

    console.debug("selectSingleDataTableRow ::: Stop");
}



/**
 * @name printCKEditorStatus
 * @description shows the status of CKEditor
 * @memberof notes
 */
function printCKEditorStatus()
{
    console.debug("printCKEditorStatus ::: Start");
    console.log("printCKEditorStatus ::: CKEditor status is: "+CKEDITOR.status);
    console.debug("printCKEditorStatus ::: Stop");
}


/**
 * @name initCKEditor
 * @description initialize CKEditor
 * @memberof notes
 */
function initCKEditor()
{
    console.debug("initCKEditor ::: Start");
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
                            if( $("#bt_saveNote").is("[disabled=disabled]"))
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
    console.debug("initCKEditor ::: Stop");
}


/**
 * @name saveCKEditorHeightOnChange
 * @description handles resize events of CKEditor
 * @memberof notes
 */
function saveCKEditorHeightOnChange()
{
    console.debug("saveCKEditorHeightOnChange ::: Start");

    console.log("saveCKEditorHeightOnChange ::: Saving CKEditor height (to localStorage), after it has changed");

    CKEDITOR.on("instanceReady",function(ev)
    {
        ev.editor.on("resize",function(reEvent)
        {
            // get new height of editor window
            editorHeight = ev.editor.ui.space( "contents" ).getStyle( "height" );

            console.log("saveCKEditorHeightOnChange ::: CKEditor resized to: "+editorHeight);

            //save to localstorage
            window.localStorage.setItem("monotoEditorHeight", editorHeight);
        });
    });

    printCKEditorStatus();

    console.debug("saveCKEditorHeightOnChange ::: Stop");
}


/**
 * @name enableCKEditorWriteMode
 * @description enable READ-WRITE mode of CKEditor
 * @memberof notes
 */
function enableCKEditorWriteMode()
{
    console.debug("enableCKEditorWriteMode ::: Start");

    console.log("enableCKEditorWriteMode ::: Trying to set CKEditor to RW");

    CKEDITOR.instances.editor1.setReadOnly( false );

    printCKEditorStatus();

    console.debug("enableCKEditorWriteMode ::: Stop");
}


/**
 * @name disableCKEditorWriteMode
 * @description enable READ-ONLY mode of CKEditor
 * @memberof notes
 */
function disableCKEditorWriteMode()
{
    console.debug("disableCKEditorWriteMode ::: Start");

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

    printCKEditorStatus();

    console.debug("disableCKEditorWriteMode ::: Stop");
}


/**
 * @name resetCKEditor
 * @description resets the content of CKEditor
 * @memberof notes
 */
function resetCKEditor()
{
    console.debug("resetCKEditor ::: Start");

    console.log("resetCKEditor ::: Resetting CKEditor");

    // empty the editor
    CKEDITOR.instances.editor1.setData("");

    printCKEditorStatus();

    // Enable Read-Only mode
    disableCKEditorWriteMode();

    console.debug("resetCKEditor ::: Stop");
}


/**
 * @name hideAllButtons
 * @description hide all buttons in the notes interface
 * @memberof notes
 */
function hideAllButtons()
{
    console.debug("hideAllButtons ::: Start");

    console.log("hideAllButtons ::: Hiding all buttons at once");

    // hide the buttons
    $("#bt_prepareNoteCreation").hide();
    $("#bt_cancelNewNote").hide();
    $("#bt_createNewNote").hide();
    $("#bt_deleteNote").hide();
    $("#bt_saveNote").hide();

    console.debug("hideAllButtons ::: Stop");
}


/**
 * @name resetNotesUI
 * @description reset User-Interface (UI) back to defaults
 * @memberof notes
 */
function resetNotesUI()
{
    console.debug("resetNotesUI ::: Start");

    console.log("resetNotesUI ::: Starting to reset the Notes UserInterface");

    // UI
    //
    // hide all UI buttons
    hideAllButtons();

    // show some elements
    $("#searchField").fadeIn(500); // fade in search field (if needed)
    $("#newNoteTitle").show();
    $("#bt_prepareNoteCreation").show();
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


    // DataTable
    //
    // enable DataTable
    $("#myDataTable").prop("disabled",false); // enable DataTable
    $("#myDataTable").fadeIn(500); // fade in DataTable (if needed)
    $("#myDataTable_info").fadeIn(500); // hide DataTable info about records

    // reset all DataTable filter - to see all records of the table
    if(initialLoad === false)
    {
        oTable.search("").draw();
    }

    // unmark all DataTable records
    unmarkAllDataTableRows();

    // CKEditor
    //
    // reset editor content
    resetCKEditor();

    // reset datatable current position
    curSelectedTableRow = -1;

    // set focus to search (shouldnt be needed anymore due to autofocus)
    $("#searchField").focus();

    console.log("resetNotesUI ::: Finished resetting the Notes UserInterface");

    console.debug("resetNotesUI ::: Stop");
}


/**
 * @name prepareNewNoteStepOne
 * @description prepars the User-Interface for the note creation process
 * @memberof notes
 */
function prepareNewNoteStepOne()
{
    console.debug("prepareNewNoteStepOne ::: Start");

    console.log("prepareNewNoteStepOne ::: Preparing note creation - Step 1");

    // first of all ... reset the User-Interface
    resetNotesUI();

    // Search: disable and fadeout
    $("#searchField").prop("disabled",true); // disable search-field
    $("#searchField").fadeOut(500); // hide search field

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
    $("#myDataTable").prop("disabled",true); // disable datatable
    $("#myDataTable").fadeOut(500); // hide  DataTablesearch field
    $("#myDataTable_info").fadeOut(500); // hide DataTable info about records

    console.debug("prepareNewNoteStepOne ::: Stop");
}


/**
 * @name prepareNewNoteStepTwo
 * @description triggered via "noteTitle" during "New Note creation"
 * @memberof notes
 */
function prepareNewNoteStepTwo()
{
    console.debug("prepareNewNoteStepTwo ::: Start");

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
    console.debug("prepareNewNoteStepTwo ::: Stop");
}



/**
 * @name reloadAllNotesFromDB
 * @description redraws the notes DataTable
 * @memberof notes
 */
function reloadAllNotesFromDB()
{
    console.debug("reloadAllNotesFromDB ::: Start");

    console.log("reloadAllNotesFromDB ::: Trying to load all user notes from server");

    // destroy old datatable
    $( "#myDataTable" ).DataTable().destroy();
    $( "myDataTable" ).empty();

    // re-init datatable
    initDataTable(userLanguage);

    console.log("reloadAllNotesFromDB ::: Finished loading all user notes from server");

    console.debug("reloadAllNotesFromDB ::: Stop");
}


/**
 * @name createNewNote
 * @description creates a new note via post to inc/noteNew.php
 * @memberof notes
 */
function createNewNote()
{
    console.debug("createNewNote ::: Start");

    console.log("createNewNote ::: Creating a new note");

    var newNoteTitle = $("#noteTitle").val();
    console.log("createNewNote ::: User note title: '" + newNoteTitle + "'.");

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
    //newNoteTitle = newNoteTitle.replace(/([A-Za-z0-9äöüÄÖÜß._ #-|!?])\w+ /g, "");
    newNoteTitle = newNoteTitle.replace(/([^A-Za-z0-9äöüÄÖÜß.|!?\+\-\_\ +])\w+  /g);
    console.log("createNewNote ::: Cleaned note title to: '" + newNoteTitle + "'.");

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
            newNoteContent = "This is a placeholder for missing content while note-creation.";
        }

        var jqxhr = $.post( "inc/noteNew.php", { newNoteTitle: newNoteTitle, newNoteContent : newNoteContent}, function()
        {
            console.log("createNewNote ::: success creating new note");
        })
        .done(function()
        {
            console.log("createNewNote ::: done");

            // reload all notes
            reloadAllNotesFromDB();

            // reset UI
            resetNotesUI();
        })
        .fail(function(jqxhr, textStatus, errorThrown)
        {
            console.error("createNewNote ::: $.post failed");

            console.log(jqxhr);
            console.log(textStatus);
            console.log(errorThrown);

            createNoty("Note creation failed", "error");
        })
        .always(function()
        {
            // doing nothing so far
        });
    }
    else
    {
        createNoty("Missing <b>note title</b>.", "error");
    }
    console.debug("createNewNote ::: Stop");
}



/**
 * @name saveNote
 * @description updates an existing note via post to inc/noteUpdate.php
 * @memberof notes
 */
function saveNote()
{
    console.debug("saveNote ::: Start");

    console.log("saveNote ::: Saving an existing note");

    if ($("#bt_saveNote").is(":disabled"))
    {
        console.warn("saveNote ::: Save button is disabled (ignoring save cmd).");
    }
    else
    {
        // noteID
        var modifiedNoteID = document.myform.noteID.value;

        // noteVersion
        var modifiedNoteCounter = document.myform.noteVersion.value;

        // noteTitle
        var modifiedNoteTitle = document.myform.noteTitle.value;

        // noteContent (ckeditor)
        var modifiedNoteContent = CKEDITOR.instances.editor1.getData();
        //var modifiedNoteContent = CKEDITOR.instances.editor1.document.getBody().getHtml();

        // replace: '   with &#39; // cleanup note content
        modifiedNoteContent.replace(/'/g , "&#39;");

        // if we have a note-id - save the change to db
        if((modifiedNoteID.length > 0) && (modifiedNoteID !== "ID"))
        {
            var jqxhr = $.post( "inc/noteUpdate.php", { modifiedNoteID: modifiedNoteID, modifiedNoteTitle: modifiedNoteTitle, modifiedNoteContent: modifiedNoteContent, modifiedNoteCounter: modifiedNoteCounter}, function()
            {
                console.log("saveNote ::: success creasting new note");
            })
            .done(function()
            {
                console.log("saveNote ::: done");

                // reload all notes
                reloadAllNotesFromDB();

                // reset UI
                resetNotesUI();

                // reset search field (as all notes got reloaded in the step before)
                //
                //console.log("saveNote ::: Resetting search field.");
                //$("#searchField").val("");

                // disable save button
                //disableNoteSavingButton();


                //var overallRowCount = oTable.rows().count();
                //console.log("saveNote ::: Table has currently " + overallRowCount + " rows");

                //$(oTable.row(10).node()).click();
                //$(oTable.row(overallRowCount).node()).click();

                // reload all notes
                //reloadAllNotesFromDB();

                //selectSingleDataTableRow(overallRowCount);

                // simulate keypress - arrow down:
                //$("#searchField").focus(); // as arrow up/down needs focus to work
                //jQuery.event.trigger({ type : 'keypress', which : character.charCodeAt(40) });

                //console.log("saveNote ::: Simulating arrow down key press");
                //var event = jQuery.Event('keypress');
                //event.which = 40;
                //event.keyCode = 40; //keycode to trigger this for simulating enter
                //jQuery(this).trigger(event);

                createNoty("Saved note <b>" + modifiedNoteTitle + "</b>.", "success");

            })
            .fail(function(jqxhr, textStatus, errorThrown)
            {
                console.error("saveNote ::: $.post failed");
                console.log(jqxhr);
                console.log(textStatus);
                console.log(errorThrown);

                createNoty("Saving note failed", "error");
            })
            .always(function()
            {
                // doing nothing so far
            });


        }
        else // should never happen as the save button is not always enabled.
        {
            createNoty("Missing ID reference", "error");
        }
    }
    console.debug("saveNote ::: Stop");
}



/**
 * @name deleteNote
 * @description deletes an existing note via post to inc/noteDelete.php
 * @memberof notes
 */
function deleteNote()
{
    console.debug("deleteNote ::: Start");

    console.log("deleteNote ::: Delete note dialog");

    var deleteID = $("#noteID").val();
    var deleteTitle = $("#noteTitle").val();
    //var deleteContent = $("#editor1").val();

    if ((deleteID.length > 0) && (deleteID !== "" ))
    {
        // confirm dialog
        var x = noty({
            text: "Do you really want to delete the note <b>" + deleteTitle +"</b>?",
            type: "confirm",
            dismissQueue: false,
            layout: "topRight",
            theme: "defaultTheme",
            buttons: [
                {addClass: "btn btn-primary", text: "Ok", onClick: function($noty) {
                    $noty.close();

                    var jqxhr = $.post( "inc/noteDelete.php", { deleteID: deleteID, deleteTitle: deleteTitle }, function()
                    {
                        console.log("deleteNote ::: success deleting note");
                    })
                    .done(function()
                    {
                        console.log("deleteNote ::: done");

                        // reload notes
                        reloadAllNotesFromDB();

                        // reset UI
                        resetNotesUI();

                        // display noty
                        createNoty("Deleted note <b>" + deleteTitle + "</b>.", "success");

                    })
                    .fail(function(jqxhr, textStatus, errorThrown)
                    {
                        console.error("deleteNote ::: .$post failed");

                        console.log(jqxhr);
                        console.log(textStatus);
                        console.log(errorThrown);

                        createNoty("Deleting note failed", "error");
                    })
                    .always(function()
                    {
                        // doing nothing so far
                    });
                }
            },
            {
                addClass: "btn btn-danger", text: "Cancel", onClick: function($noty)
                {
                    $noty.close();
                    createNoty("Aborted", "error");
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
            console.error("deleteNote ::: Unable to delete a note as no note was selected.");
        }
        else
        {
            console.error("deleteNote ::: Unable to delete note.");
            createNoty("Unable to delete note", "error");
        }
    }
    console.debug("deleteNote ::: Stop");
}


/**
 * @name reloadCurrentPage
 * @description reloads the current page
 * @memberof notes
 */
function reloadCurrentPage()
{
    console.debug("reloadCurrentPage ::: Start");

    console.log("reloadCurrentPage ::: Reloading the current page");

    var loc = window.location;
    window.location = loc.protocol + "//" + loc.host + loc.pathname + loc.search;

    console.debug("reloadCurrentPage ::: Stop");
}


/**
 * @name initDataTable
 * @description initializes the notes DataTable
 * @memberof notes
 */
function initDataTable(sessionLanguage)
{
    console.debug("initDataTable ::: Start");
    console.log("initDataTable ::: Initializing the DataTable with language set to: " + sessionLanguage);

    if( sessionLanguage === "de_DE.UTF-8")
    {
        langUrl = "js/datatables/German.json";
    }
    else {
        langUrl = "js/datatables/English.json";
    }
    console.log("initDataTable ::: Language file is set to: " + langUrl);

    oTable = $("#myDataTable").DataTable( {
        "select": {
            "style": "single"
        },

        "language": {
            "url": langUrl,
            "loadingRecords": "Loading...",
            "processing": "Processing...",
            "search": "Search:",
            "emptyTable": "No matches found",
            "zeroRecords": "No notes found",
            "info": "Showing _TOTAL_ notes",
            "infoEmpty": "No notes available",
            "infoFiltered": " - after searching all _MAX_ notes.",
            "select": {
                "rows": "%d selected",
            }
        },
        // test
        "searching": true,
        "info": true,
        // #242 - Highlight search strings in datatable using mark.js & datatables.mark.js
        "mark": true,
        "processing": true,
        //"serverSide": true, // might conflict with .search in datatable
        "ajax": "inc/noteGetAllNotes.php",
        "dom": "irt<'clear'>",
        "paging": false,
        "aaSorting": [[ 3, "desc" ]], // default sorting
        "aoColumnDefs": [ // disable sorting for all visible columns - as it breaks keyboard navigation
            { "bSortable": false, "aTargets": [ 0 ] }, // id
            { "bSortable": false, "aTargets": [ 1 ] }, // title
            { "bSortable": false, "aTargets": [ 2 ] }, // content
            { "bSortable": true, "aTargets": [ 3 ] }, // date mod
            { "bSortable": false, "aTargets": [ 4 ] }, // date create
            { "bSortable": false, "aTargets": [ 5 ] }, // version
            { "bSortable": false, "aTargets": [ 6 ] }, // owner
        ],
        "aoColumns"   : [
            { "bSearchable": false, "bVisible": false, "sWidth": "5%" },
            { "bSearchable": true, "bVisible": true, "sWidth": "100%" },
            { "bSearchable": true, "bVisible": false},
            { "bSearchable": false, "bVisible": false},
            { "bSearchable": false, "bVisible": false},
            { "bSearchable": false, "bVisible": false },
            { "bSearchable": false, "bVisible": false}
        ],
    } );
    console.log("initDataTable ::: Finished initializing the DataTable");

    // amountOfRecordsAfterFilter should be set to count of all records, not 0
    amountOfRecordsAfterFilter = 0;

    console.debug("initDataTable ::: Stop");
}


/**
 * @name selectAndMarkTableRow
 * @description select and mark a single row in DataTable
 * @memberof notes
 * @param {string} currentRow - The row number
 */
function selectAndMarkTableRow(currentRow)
{
    console.debug("selectAndMarkTableRow ::: Start");

    console.log("selectAndMarkTableRow ::: Selecting and marking the row: "+currentRow);

    // click the record to load the data to UI
    //$("#myDataTable tbody tr:eq("+currentRow+")").click();
    $("#myDataTable tbody td:eq("+currentRow+")").click();

    // change background as well (mark as selected)
    $("#myDataTable tbody tr:eq("+currentRow+")").addClass("row_selected");

    console.debug("selectAndMarkTableRow ::: Stop");
}


/**
 * @name updateCurrentSelectedRowInDataTable
 * @description update the current selected row in DataTable
 * @memberof notes
 * @param {string} valueChange - the change
 */
function updateCurrentSelectedRowInDataTable(valueChange)
{
    console.debug("updateCurrentSelectedRowInDataTable ::: Start");

    // count visible rows
    amountOfRecordsAfterFilter = oTable.$("tr", {"filter":"applied"}).length;
    console.log("Visible records after search: "+amountOfRecordsAfterFilter);

    // if curSelectedTableRow is not yet defined - set a default value
    if ( typeof curSelectedTableRow === "undefined" )
    {
        console.warn(typeof curSelectedTableRow);
        curSelectedTableRow = -1;
        console.warn("...initializing curSelectedTableRow to: " + curSelectedTableRow);
    }
    else {
        console.log("updateCurrentSelectedRowInDataTable was: " + curSelectedTableRow);
        curSelectedTableRow = curSelectedTableRow + valueChange;
        console.log("updateCurrentSelectedRowInDataTable is now: " + curSelectedTableRow);

    }

    if ( curSelectedTableRow < 0 )  // doesnt make sense -> jump to last row
    {
        curSelectedTableRow = amountOfRecordsAfterFilter - 1;
        console.log("updateCurrentSelectedRowInDataTable ::: Set curSelectedTableRow to: " + curSelectedTableRow);
    }

    // doesnt make sense -> jump to last row
    if ( curSelectedTableRow > amountOfRecordsAfterFilter - 1 )
    {
        curSelectedTableRow = 0;
        console.log("updateCurrentSelectedRowInDataTable ::: Set curSelectedTableRow to: " + curSelectedTableRow);

    }

    // update UI
    unmarkAllDataTableRows();
    selectAndMarkTableRow(curSelectedTableRow);

    console.debug("updateCurrentSelectedRowInDataTable ::: Stop");
}


/**
 * @name selectNextDataTableRow
 * @description selects the next row in notes DataTable
 * @memberof notes
 */
function selectNextDataTableRow()
{
    console.debug("selectNextDataTableRow ::: Start");

    console.log("selectNextDataTableRow ::: Selecting next row in DataTable");
    updateCurrentSelectedRowInDataTable(1);

    console.debug("selectNextDataTableRow ::: Stop");
}


/**
 * @name selectPreviousDataTableRow
 * @description selects the previous row in notes DataTable
 * @memberof notes
 */
function selectPreviousDataTableRow()
{
    console.debug("selectPreviousDataTableRow ::: Start");

    console.log("selectPreviousDataTableRow ::: Selecting previous row in DataTable");
    updateCurrentSelectedRowInDataTable(-1);

    console.debug("selectPreviousDataTableRow ::: Stop");
}


/**
 * @name onClickDataTableCell
 * @description executed if a cell in the notes DataTable is clicked
 * @memberof notes
 * @param {array} data - Contains all fields of the selected note
 */
function onClickDataTableCell(data)
{
    console.debug("onClickDataTableCell ::: Start");

    console.log("onClickDataTableCell ::: A cell of the DataTable was clicked - try to load the note.");

    // get data from current record
    noteID = data[0];
    noteTitle = data[1];
    noteContent = data[2];
    noteDateMod = data[3];
    noteDateCreate = data[4];
    noteSaveCount = data[5];
    noteOwner = data[6];

    // output record datas
    console.log("Note ID: " + noteID);
    console.log("Note title:" + noteTitle);
    console.log("Note Content:" + noteContent);
    console.log("Note Date Mod: " + noteDateMod);
    console.log("Note Date Create:" + noteDateCreate);
    console.log("Note Save Count:" + noteSaveCount);
    console.log("Note Owner:" + noteOwner);

    // disable button (no changes yet)
    disableNoteSavingButton();

    // enable the delete button
    $("#bt_deleteNote").prop("disabled",false);

    // enable note title field
    $("#noteTitle").prop("disabled",false);

    console.log("onClickDataTableCell ::: Updating notes UI with note ID: "+noteID+ " and title: '"+noteTitle+"'.");

    // update UI
    $("#noteID").val(noteID);   // fill id field
    $("#noteTitle").val(noteTitle); // fill title field
    $("#noteVersion").val(noteSaveCount); // fill version -  is hidden

    // set focus to search
    $("#searchField").focus(); // as arrow up/down needs focus to work

    // load note to ckeditor
    console.log("onClickDataTableCell ::: Trying to load note content to editor");
    console.log("onClickDataTableCell ::: NoteContent: " + noteContent);

    CKEDITOR.instances.editor1.setData(noteContent,function()
    {
        // set data of editor to noteContent
        CKEDITOR.instances.editor1.setData(noteContent); // #201

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
            console.log("onClickDataTableCell ::: OnChangeListener for CKEditor");

            // check if button is disabled - if so - enable it
            if($("#bt_saveNote").is(":disabled"))
            {
                enableNoteSavingButton();
            }
        });

        // On Change Listener for noteTitle
        // to detect if note title has changed after loading a note
        $("#noteTitle").on("change keyup paste", function()
        {
            // Enable or disable the save button, based on the fact if the noteTitle is still > 0
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

    console.debug("onClickDataTableCell ::: Stop");
}


/**
 * @name onFilterDataTable
 * @description executed after the raw filtering of notes DataTable is done
 * @memberof notes
 * @param {number} amountOfRecordsAfterFilter - The amount of visible DataTable records
 */
function onFilterDataTable(amountOfRecordsAfterFilter)
{
    console.debug("onFilterDataTable ::: Start");

    switch(amountOfRecordsAfterFilter)
    {
        case 0: // there is 0 record in selection after processing search
            console.log("onFilterDataTable ::: Got 0 results");
            // reset noteID field
            $("#noteID").val("");
            // reset noteVersion field
            $("#noteVersion").val("");
            // reset noteTitle
            $("#noteTitle").val("");

            // hide all buttons
            hideAllButtons();

            // reset content of note-editor
            CKEDITOR.instances.editor1.setData("");
            break;

        case 1: // there is one record in selection after processing search
            console.log("onFilterDataTable ::: Got 1 result");
            //$("#myDataTable tbody tr:eq(0)").click(); // select the only record left after search
            $("#myDataTable tbody td:eq(0)").click(); // select the only record left after search

            console.log("Mark/Highlight the current record");
            $("#myDataTable tbody tr:eq(0)").addClass("row_selected"); // change background as well

            CKEDITOR.config.readOnly = false; // enable the editor
            break;


        default: // there is > 1 record in selection after processing search
            console.log("onFilterDataTable ::: Got > 1 result");

            // check if there is already one note selected or not
            var table = $("#myDataTable").DataTable();
            if (table.rows( ".row_selected" ).any() )
            {
                console.log("onFilterDataTable ::: > 1 result BUT 1 is selected");
                // one record is selected - editor should not be modified
            }
            else
            {
                console.log("onFilterDataTable ::: > 1 result and no record selected");

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
    console.debug("onFilterDataTable ::: End");
}



/**
 * @name onNotesPageReady
 * @description init the notes view
 * @memberof notes
 */
function onNotesPageReady()
{
    console.debug("onNotesPageReady ::: Start");

    initialLoad = true;
    curSelectedTableRow = -1;

    console.log("onNotesPageReady ::: Starting to initializing the notes view");

    // CKEditor
    //
    initCKEditor();
    saveCKEditorHeightOnChange();

    // DataTable
    //
    initDataTable(userLanguage); // initializes the DataTable

    // DataTable: add a click handler to the rows (<tr>)
    $("#myDataTable tbody").on("click", "tr", function ()
    {
        console.log("onNotesPageReady ::: clicked a record row <tr>");

        // count visible rows
        //amountOfRecordsAfterFilter = 0;
        amountOfRecordsAfterFilter = oTable.$("tr", {"filter":"applied"}).length;
        console.log("onNotesPageReady ::: Click Table row: Visible records after search: "+amountOfRecordsAfterFilter);

        // get ID of selected record
        var idOfSelectedRecord = oTable.row( this ).index();
        console.log("onNotesPageReady ::: Click Table row: ID of selected record____: " + idOfSelectedRecord);

        // get IDs of all visible records
        console.log("onNotesPageReady ::: Click Table row: Records IDs in selection:");
        var foo = oTable.rows({filter: "applied"});
        console.log(foo);
        for ( var i = 0; i < foo[0].length; i++ )
        {
            //console.log(foo[0][i]);
            if(idOfSelectedRecord === foo[0][i])
            {
                curSelectedTableRow = i;
                console.log("onNotesPageReady ::: Set curSelectedTableRow to: " + curSelectedTableRow);
            }
        }
    });
    // End of datatable <tr> click handler


    // Datatable: Add a click handler to the rows (<td>)
    //
    $("#myDataTable").on("click", "tbody td", function(event)
    {
        // unmark all records
        unmarkAllDataTableRows();

        // Add selected attribute to current row
        $(event.target.parentNode).addClass("row_selected");

        // get data from current record
        var data = oTable.row( $(this).parents("tr") ).data();

        onClickDataTableCell(data);
    });
    // End of datatable <td> click handler


    // Datatable: Add a doubleclick handler to the table <tr>
    $("table tr").dblclick(function ()
    {
        console.log("onNotesPageReady ::: DoubleClick on DataTable");
    });
    // End of datatable <tr> click handler


    // Search field: add key-up handling
    //
    // configure the search field & its event while typing
    $("#searchField").keyup(function(e) // keyup triggers on each key
    //$('#searchField').keypress(function(e) // keypress ignores all soft-keys
    {
        var code = (e.keyCode || e.which);

        console.log("onNotesPageReady ::: Keypress in search field: '" + code + "'.");

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
            console.log("onNotesPageReady ::: Keypress in search field: Ignoring some key inputs");
            return;
        }

        // assuming it is a key / input we want
        // start processing ....

        // search the table
        //
        console.log("onNotesPageReady ::: Keypress in search field - Searching table for: " + $(this).val());
        oTable.search( $(this).val() ).draw();

        // count visible rows
        amountOfRecordsAfterFilter = 0;
        amountOfRecordsAfterFilter = oTable.$("tr", {"filter":"applied"}).length;
        console.log("onNotesPageReady ::: Keypress in search field: Visible records after search: " + amountOfRecordsAfterFilter);

        // if there are multiple or no notes available after search
        // - reset ckeditor
        // - hide delete button
        onFilterDataTable(amountOfRecordsAfterFilter);

        // finished reacting on results after filtering
    });
    // #searchField: End of keyup handler

    // reset notes UI
    resetNotesUI();

    console.debug("onNotesPageReady ::: Stop");
}
