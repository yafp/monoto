// ---------------------------------
// initialize DataTable
// ---------------------------------
function initDataTable()
{
	oTable = $('#example').dataTable( 
	{ 
		"oLanguage": { 
			"sProcessing": "<img src='../images/loadi_ng.gif'>",
			//"sProcessing": "DataTables is currently busy",
			"sEmptyTable": "You have 0 notes so far - start writing some...", // displayed if table is initial empty
			"sZeroRecords": "No notes to display for your search" // displayed if table is filtered to 0 matching records
			},
			//"sDom": '<"wrapper"lit>, <l<t>',		// resorting the datatable sDom structure - to have search & recordcount - table - recordcount 
			//"oSearch": {"sSearch": ""}, 
			"sRowSelect": "single",
			"scrollY": "35%", // plugin: scroller
			"scrollCollapse": true,
			"oScroller": {"loadingIndicator": true},
			"dom": "rti",
			"deferRender": true,
			"bLengthChange": false,
			"bPaginate": false , 															// pagination  - BREAKS SELECTED ROW - copy content function right now*/
			"bScrollCollapse": true,
			"aaSorting": [[ 4, "desc" ]],													/* default sorting */
			"aoColumnDefs": [																// disable sorting for all visible columns - as it breaks keyboard navigation 
							{ "bSortable": false, "aTargets": [ 1 ] },
							{ "bSortable": false, "aTargets": [ 2 ] },
							{ "bSortable": false, "aTargets": [ 3 ] },
							{ "bSortable": false, "aTargets": [ 4 ] }
							], 
			"aoColumns"   : [																/* visible columns */
						{ "bSearchable": false, "bVisible": false },						/* manually defined row id */
						{ "bSearchable": false, "bVisible": false, "sWidth": "5%" }, 							/* note-id */
						{ "bSearchable": true, "bVisible": true, "sWidth": "100%" },							/* note-title */
						{ "bSearchable": true, "bVisible": false}, 							/* note-content */
						{ "bSearchable": false, "bVisible": false}, 							/* note-modification date */
						{ "bSearchable": false, "bVisible": false}, 							/* save-count */
						],
	} );
}




// ---------------------------------
// initialize CKEditor 
// ---------------------------------
function initCKEditor()
{
	// START CKEDITOR
	CKEDITOR.replace( 'editor1', {
		enterMode: CKEDITOR.ENTER_BR, /* prevent <p>aragraphs over and over in note-content */
		height: monotoEditorHeight,
		extraPlugins : 'wordcount',
		wordcount : {
			showCharCount : true,
			showWordCount : true,
			countHTML: false
		},
		removePlugins: 'elementspath', /*  hide html tags in ckeditors foot*/
		toolbar:
		[
			{ name: 'document',    items : [ 'Source' ] },
			{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','RemoveFormat' ] },
			{ name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv' ] },
			{ name: 'insert',      items : [ 'Link','Image','Flash','Table','HorizontalRule','SpecialChar' ] },
			{ name: 'styles',      items : [ 'Styles','Format' ] },
			{ name: 'tools',       items : [ 'Maximize' ] }
		]
	});
}



// ---------------------------------
// handle timeout & warning
// ---------------------------------
function timeOutHandler()
{
	var lefttime = "<?php echo get_cfg_var('max_execution_time');  ?>"; /* get server-sided php timeout value in minutes */
	var interval;
	interval = setInterval('change()',60000);

	function change()
	{
		lefttime--;
		if(lefttime <= 0) // session should be dead
		{		
			window.location = "logout.php"
		}
		else
		{
			if(lefttime == 5) 
			{
				var n = noty({text: 'timeout-reminder.', type: 'warning'});
				alert("Are you still there? Timeout might happen in "+lefttime+" minute(s). Do something.");
			}
		}
	}
}




// ---------------------------------
// handle ckeditor height
// ---------------------------------
function saveCKEditorHeightOnChange()
{
	CKEDITOR.on('instanceReady',function(ev) 
	{
		ev.editor.on('resize',function(reEvent)
		{
			editorHeight = this.ui.space( 'contents' ).getStyle( 'height' ); // get current height
			window.localStorage.setItem("monotoEditorHeight", editorHeight); //save to localstorage
		});
	});
	console.log("CKEditor height")
}





// ---------------------------------
// unselect/unmark all rows in table
// ---------------------------------
function unmarkAllTableRows()
{
	$(oTable.fnSettings().aoData).each(function ()					// unselect all records
	{
		$(this.nTr).removeClass('row_selected');
	});
}




// -------------------------------------
// select and mark a single row in table
// -------------------------------------
function selectAndMarkTableRow(currentRow)
{
	$('#example tbody tr:eq('+currentRow+')').click(); 						// select the top record
	$('#example tbody tr:eq('+currentRow+')').addClass('row_selected');		// change background as well
}




// ----------------------------------------------
// Update the scrollbar of the datatable-scroller
// ----------------------------------------------
function updateTableScrollbar()
{
	scrollPos = (curID / amountOfRecordsAfterFilter) * 300 ;
	//console.log(curID);
	//console.log(amountOfRecordsAfterFilter);
	//console.log("Scroll-Position: "+scrollPos);
	$(".dataTables_scrollBody").scrollTop(scrollPos);
}






// ---------------
// select next row
// ---------------
function selectNextRow()
{
	if(typeof nextID === 'undefined') // to handle first jump from searchfield to table
	{
		nextID=0;
	};
	
	unmarkAllTableRows();
	selectAndMarkTableRow(nextID);
	updateTableScrollbar();
}


// --------------------
// select the upper row
// --------------------
//
function selectUpperRow()
{
	unmarkAllTableRows();
	selectAndMarkTableRow(prevID);
	updateTableScrollbar();
}


// ------------------------------
// reset the notes user-interface
// ------------------------------
//
function resetNotesUI() 
{
	console.log("resetting notes ui now");
	currentRow = -1;
	
	// show some elements
	$("#newNoteTitle").show();
	$("#bt_PrepareNoteCreation").show();
	
	// hide some elements
	$("#bt_delete").hide();
	$("#bt_save").hide();
	$("#bt_createNewNoteButton").hide();

	// disable some items
	$("#noteTitle").prop("disabled",true);

	// refresh the gui
	unmarkAllTableRows();
	$("#myInputTextField").prop("disabled",false);
	$('#noteTitle').val("");
	document.activeElement.value = "";							// reset newNoteTitle
	document.activeElement.blur(); 								// lose focus from newNotetitle
	$("#myInputTextField").focus();
	document.activeElement.value = "";
	CKEDITOR.instances['editor1'].setData("");					// empty the editor
}



// --------------------------------
// Prepare New Note creation Step 1
// --------------------------------
//
function prepareNewNoteStepOne() 
{
	resetNotesUI();
	$("#myInputTextField").prop("disabled",true);	// disable search-field
	$("#noteTitle").prop("disabled",false);			// enable note-title field
	$("#noteTitle").focus(); 						// set focus to note title
	$("#bt_createNewNoteButton").prop("disabled",true);		// disable create-note button
	$("#bt_createNewNoteButton").show();					// show create-note button
	$("#bt_PrepareNoteCreation").hide();
}



// --------------------------------
// Prepare New Note creation Step 2
// --------------------------------
function prepareNewNoteStepTwo() 
{
	var noteTitle = document.myform.noteTitle.value;

	if( $('#bt_save').is(':visible')) // if Save-Button is visible we can not be in note-creation mode
	{
		//console.log("visible");
		if(noteTitle.length > 0) // 
		{
			$("#bt_save").prop("disabled",false);
		}
		else
		{
			$("#bt_save").prop("disabled",true);
		}
	}
	else // we are in Note-Creation mode
	{
		if(noteTitle.length > 0) // & save button nicht sichtbar
		{
			$("#bt_createNewNoteButton").prop("disabled",false);
			$("#bt_createNewNoteButton").show();
		}
		else
		{
			$("#bt_createNewNoteButton").prop("disabled",true);
		}
	}
}


// --------------------------------
// Prepare New Note creation Step 2
// --------------------------------
//
function createNewNote() 
{
	var newNoteTitle = document.myform.noteTitle.value;
	newNoteTitle = newNoteTitle.replace(/[^a-zA-Z0-9-._äüößÄÜÖ/ ]/g, '');	// replace all characters except numbers,letters, space, underscore and - .
	
	var newNoteContent = CKEDITOR.instances.editor1.getData();				// get note content if defined
	newNoteContent=newNoteContent.replace(/\'/g,'&#39;');					// cleanup note content replace...

	if (newNoteTitle.length > 0)											// if we have a note title - create the new note (content is not needed so far)
	{
		if(newNoteContent.length == 0)										// check if user defined note-content or not
		{
			newNoteContent = "Placeholder content<br><br>If you see this text - you missed defining a note content while note-creation.";			// define dummy content as user didnt
		}
				
		$.post("inc/newNote.php", { newNoteTitle: newNoteTitle, newNoteContent: newNoteContent } );		// call create script				
		alert("Note with title: "+newNoteTitle+" created");			// FUCK IT - whyever this helps creating the note - might be a timing issue?????
		$.cookie("lastAction", "Note "+newNoteTitle+" created.");	// store last Action in cookie
		displayDesktopNotification("Note created");
		//reloadNote();
	}
	else
	{ 
		var n = noty({text: 'Error: No note title', type: 'error'});
	}
}







// -----------
// SAVE A NOTE
// -----------
//
function saveNote() 
{
	var modifiedNoteID = document.myform.noteID.value;							// get the note id
	var modifiedNoteTitle = document.myform.noteTitle.value;					// get the note title 
	var modifiedNoteContent = CKEDITOR.instances.editor1.getData();
	var modifiedNoteCounter = document.myform.noteVersion.value;				// get current save-counter/version
	modifiedNoteContent.replace(/'/g , "&#39;");				// replace: ' 	with &#39; // cleanup note content
	if((modifiedNoteID.length > 0) && (modifiedNoteID != 'ID'))					// if we have a note-id - save the change to db
	{
		$.post("inc/updNote.php", { modifiedNoteID: modifiedNoteID, modifiedNoteTitle: modifiedNoteTitle, modifiedNoteContent: modifiedNoteContent, modifiedNoteCounter: modifiedNoteCounter  } );
		var n = noty({text: 'Note saved', type: 'success'});
		$.cookie("lastAction", "Note "+modifiedNoteTitle+" saved.");			// store last Action in cookie
		displayDesktopNotification("Note saved");
	}
	else 																		// should never happen as the save button is not always enabled.
	{
		var n = noty({text: 'Error: Missing ID reference', type: 'error'});
	}
}


// -----------------
// DELETE A NEW NOTE
// -----------------
//
function deleteNote() 
{
	var deleteID = document.myform.noteID.value;
	var deleteTitle = document.myform.noteTitle.value;
	var deleteContent = document.myform.editor1.value;

	if ((deleteID.length > 0) && (deleteID != 'ID' ))
	{
		// confirm dialog
		var x = noty({
			text: 'Really delete this note?',
			type: 'confirm',
			dismissQueue: false,
			layout: 'topRight',
			theme: 'defaultTheme',
			buttons: [
				{addClass: 'btn btn-primary', text: 'Ok', onClick: function($noty) {
					$noty.close();
					$.post("inc/delNote.php", { deleteID: deleteID, deleteTitle: deleteTitle, deleteContent: deleteContent } );
					$.cookie("lastAction", "Note "+deleteID+" deleted.");	// store last Action in cookie
						displayDesktopNotification("Note deleted");
						
						// delete it in ui
						var anSelected = fnGetSelected( oTable );
						oTable.fnDeleteRow( anSelected[0] );
						
						resetNotesUI();
				}
			},
			{addClass: 'btn btn-danger', text: 'Cancel', onClick: function($noty) {
					$noty.close();
					noty({text: 'Aborted', type: 'error'});
					reloadNote();
				}
			}
			]
		})	
	}
	else // Data to identify note-to-delete are missing - should never happen as the delete button is disabled if no note is selected
	{
		var n = noty({text: 'Error: While trying to delete a note', type: 'error'});
	}
}



// ----------------
// RELOAD ALL NOTES
// ---------------
function reloadNote() 
{
	var loc = window.location;
	window.location = loc.protocol + '//' + loc.host + loc.pathname + loc.search;
}
