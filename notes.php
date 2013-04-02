<?php
	session_start();
	include 'conf/config.php';
	if($_SESSION['valid'] == 1)			// check if the user-session is valid or not
	{
		include 'inc/html_head.php';			// include the new header
?>
		<!-- continue the header -->
		<!-- ################### -->
		<!-- datatables -->
		<script type="text/javascript" language="javascript" src="js/jquery.dataTables.min.js"></script>
		<!--  m_keyPress-->
		<script type="text/javascript" language="javascript" src="js/m_keyPress.js"></script>
		<!-- ckeditor - new -->
		<script src="js/ckeditor-4.0.2_standard/ckeditor.js"></script>
		<!-- main js for table etc -->
		<script type="text/javascript">
			var currentRow = -1;			// fill var for ugly row-selection hack with a default value
			var oTable;
			var giRedraw = false;

			$(document).ready(function() 
			{
				// START CKEDITOR
				CKEDITOR.replace( 'editor1');
				// END CKEDITOR

				/* Add a click handler to the rows - this could be used as a callback */
				$("#example tbody").click(function(event) 
				{
					$(oTable.fnSettings().aoData).each(function ()
					{
						$(this.nTr).removeClass('row_selected');
					});
					$(event.target.parentNode).addClass('row_selected');

					// enable the sidebar buttons - as we have selected a note
					document.myform.save.disabled=false;
					document.myform.delete.disabled=false;

					// enable note title field
					document.myform.noteTitle.disabled=false;
				});

				/* Add a click handler for the delete row - we dont use that so far */
				$('#delete').click( function() 
				{
					var anSelected = fnGetSelected( oTable );
					oTable.fnDeleteRow( anSelected[0] );
				} );

				/* Init the table */
				oTable = $('#example').dataTable( 
				{ 
					"sDom": '<"wrapper"lipt>, <l<t>ip>',		/* resorting the datatable sDom structure - to have search & recordcount - table - recordcount */
					"oSearch": {"sSearch": ""}, 
					"sRowSelect": "single",
					"bLengthChange": false,
					"bPaginate": false , 															/* pagination  - BREAKS SELECTED ROW - copy content function right now*/
					"bScrollCollapse": true,
					"aaSorting": [[ 5, "desc" ]],													/* default sorting */
					"aoColumnDefs": [																// disable sorting for all visible columns - as it breaks keyboard navigation 
      							{ "bSortable": false, "aTargets": [ 1 ] },
      							{ "bSortable": false, "aTargets": [ 2 ] },
      							{ "bSortable": false, "aTargets": [ 3 ] },
      							{ "bSortable": false, "aTargets": [ 4 ] },
      							{ "bSortable": false, "aTargets": [ 5 ] },
      							{ "bSortable": false, "aTargets": [ 6 ] },
      							{ "bSortable": false, "aTargets": [ 7 ] },
    								], 
					"aoColumns"   : [																/* visible columns */
								{ "bSearchable": false, "bVisible": false },						/* manually defined row id */
								{ "bSearchable": true, "bVisible": true, "sWidth": "5%" }, 							/* note-id */
								{ "bSearchable": true, "bVisible": true, "sWidth": "50%" },							/* note-title */
								{ "bSearchable": true, "bVisible": false}, 							/* note-content */
								{ "bSearchable": false, "bVisible": false },						/* tags */
								{ "bSearchable": true, "bVisible": true }, 							/* last edit */
								{ "bSearchable": true, "bVisible": true },							/* created */
								{ "bSearchable": true, "bVisible": true, "sWidth": "5%" }							/* save_counter */
							],
				} );


				/* configure a new search field & its event while typing */
				$('#myInputTextField').keypress(function()
				{
					oTable.fnFilter( $(this).val() );												// search the table
	      			var amountOfRecordsAfterFilter = oTable.fnSettings().fnRecordsDisplay();		// get amount of records after filter

	      			// unselect all records
	      			/*
				    $(oTable.fnSettings().aoData).each(function ()
					{
						$(this.nTr).removeClass('row_selected');
					});
					*/

				    // specialcase - only 1 record
	      			if(amountOfRecordsAfterFilter == 1)												// if there is only 1 record left - select/click it
	      			{
						$('#example tbody tr:eq(0)').click()										// select the only record left after search	
						$('#example tbody tr:eq(0)').addClass('row_selected');						// change background as well					
	      			}
				})

				document.getElementById('myInputTextField').focus();								// set focus on search field

				// select a row, highlight it and get the data
				$('table tr').click(function () 
				{				
					var sData = oTable.fnGetData( this );											// Get the position of the current data from the node 				
					var aPos = oTable.fnGetPosition(this);											// show selected note-data as alert				
					var aData = oTable.fnGetData( aPos[1] );										// Get the data array for this row			
					CKEDITOR.instances['editor1'].setData(sData[3]);								// fill html richtext cleditor with text of selected note

					document.myform.noteID.value = sData[1]											// fill id field
					document.myform.noteTitle.value = sData[2]										// fill title field
					document.myform.noteVersion.value = sData[7]									// fill version - not displayed as field is hidden		
					//currentRow = sData[0];														// correct current row - as its on the initial value but user select a note via mouse
					document.getElementById('myInputTextField').focus();							// set focus to search - as arrow up/down navi works right now only if focus is in search
				});
			} );

		/* Get the rows which are currently selected */
		function fnGetSelected( oTableLocal )
		{
			var aReturn = new Array();
			var aTrs = oTableLocal.fnGetNodes();
			
			for ( var i=0 ; i<aTrs.length ; i++ )
			{
				if ( $(aTrs[i]).hasClass('row_selected') )
				{
					aReturn.push( aTrs[i] );
				}
			}
			return aReturn;
		}


		//
		// select next row
		//
		function selectNextRow( )
		{
			var amountOfRecordsAfterFilter = oTable.fnSettings().fnRecordsDisplay();	// get amount of records after filter

			if( parseInt(currentRow) < (parseInt(amountOfRecordsAfterFilter) -1))		// check if moving down makes sense at all
			{
			    currentRow = parseInt(currentRow) + 1;									// update row-position
			
			    $(oTable.fnSettings().aoData).each(function ()							// unselect all records
				{
					$(this.nTr).removeClass('row_selected');
				});

			    $('#example tbody tr:eq('+currentRow+')').click(); 						// select the top record
			    $('#example tbody tr:eq('+currentRow+')').addClass('row_selected');		// change background as well
			}			
		}


		//
		// select other row
		//
		function selectUpperRow( )
		{
			if(currentRow > 0)															// change currentRow
			{
				currentRow = currentRow - 1;
			}

			$(oTable.fnSettings().aoData).each(function ()								// unselect all records
			{
				$(this.nTr).removeClass('row_selected');
			});

			$('#example tbody tr:eq('+currentRow+')').click(); 							// select the top record
		    $('#example tbody tr:eq('+currentRow+')').addClass('row_selected');			// change background as well
		}



		//
		// SAVE A NOTE
		//
		function saveNote() 
		{
			var modifiedNoteID = document.myform.noteID.value;							// get the note id
			var modifiedNoteTitle = document.myform.noteTitle.value;					// get the note title 
			var modifiedNoteContent = CKEDITOR.instances.editor1.getData();
			var modifiedNoteCounter = document.myform.noteVersion.value;				// get current save-counter/version

			// cleanup note content
			modifiedNoteContent=modifiedNoteContent.replace(/\'/g,'&#39;')				// replace: ' 	with &#39;

			if((modifiedNoteID.length > 0) && (modifiedNoteID != 'ID'))					// if we have a note-id - save the change to db
			{
				$.post("inc/updNote.php", { modifiedNoteID: modifiedNoteID, modifiedNoteTitle: modifiedNoteTitle, modifiedNoteContent: modifiedNoteContent, modifiedNoteCounter: modifiedNoteCounter  } );
				alert("Note saves with title: "+modifiedNoteTitle+".");
				alertify.success("Note saved");
				reloadNote();
				updateLastActionInformation("note saved");								// show last action
			}
			else 																		// should never happen as the save button is not always enabled.
			{  
				
			}
		}


		//
		// DELETE A NEW NOTE
		//
		function deleteNote() 
		{
			// get the note id etc
			var deleteID = document.myform.noteID.value;
			var deleteTitle = document.myform.noteTitle.value;
			var deleteContent = document.myform.editor1.value;

			// if we have a note id to delete - try to do it
			if ((deleteID.length > 0) && (deleteID != 'ID' ))
			{	
				<?php
					include 'conf/config.php';
					if($s_enable_really_delete	== true)
					{
					?>
						var answer = confirm("Do you really want to delete this note?")
						if (answer)
						{
							$.post("inc/delNote.php", { deleteID: deleteID, deleteTitle: deleteTitle, deleteContent: deleteContent } );
							alert("Note with ID: "+deleteID+" deleted");
							alertify.success("Note deleted");
							reloadNote();
							updateLastActionInformation("note deleted");								// show last action
						}
				<?php
					}
					else
					{
				?>
						$.post("inc/delNote.php", { deleteID: deleteID, deleteTitle: deleteTitle, deleteContent: deleteContent } );
						alert("Note with ID: "+deleteID+" deleted");
						alertify.success("Note deleted");
						reloadNote();
						updateLastActionInformation("note deleted");								// show last action
				<?php
					}
				?>
			}
			else // should never happen as the delete button is disabled if no note is selected
			{ 
			}	
		}

		//
		// CREATE NEW NOTE
		//
		function createNote() 
		{
			var newNoteTitle = document.myform.newNoteTitle.value;					// get new title
			newNoteTitle = newNoteTitle.replace(/[^a-zA-Z0-9-._äüößÄÜÖ/ ]/g, '');		// replace all characters except numbers,letters, space, underscore and - .
									
			var newNoteContent = CKEDITOR.instances.editor1.getData();				// get note content if defined

			// cleanup note content
			newNoteContent=newNoteContent.replace(/\'/g,'&#39;')					// replace: ' 	with &#39;

			if (newNoteTitle.length > 0)											// if we have a note title - create the new note (content is not needed so far)
		  	{
		  		if(newNoteContent.length == 0)										// check if user defined note-content or not
		  		{
		  			newNoteContent = "Placeholder content - as no note-content was defined while creating this note.";			// define dummy content as user didnt
		  		}
		  		
		  		$.post("inc/newNote.php", { newNoteTitle: newNoteTitle, newNoteContent: newNoteContent } );		// call create script
				alert("Note with title: "+newNoteTitle+" created");			// FUCK IT - whyever this helps creating the note - might be a timing issue?????
				alertify.success("Note created");
				updateLastActionInformation("note created");								// show last action
		  	}
			else
			{ 
				alertify.error("Error: No note title defined");
			}
		}


		//
		// RELOAD ALL NOTES
		//
		function reloadNote() 
		{
			// reload page - trying to ignore post data
			var loc = window.location;
    		window.location = loc.protocol + '//' + loc.host + loc.pathname + loc.search;
    		updateLastActionInformation("notes reloaded");								// show last action
    		alertify.success("Reloaded all notes");
		}


		//
		// ENABLE CREATE NOTE BUTTON
		//
		function enableCreateButton()
		{
			document.myform.createNoteButton.disabled=false;	// enable Create new note button

			// lets clean up the main interface
			document.myform.noteID.value = "";					// empty ID of previously selected note
			document.myform.noteTitle.value = "";				// empty title of previously selected note
			document.myform.noteVersion.value = "";				// empty hiddeen version of previously selected note
			document.myform.save.disabled=true;					// disable the save button
			document.myform.delete.disabled=true;				// disable the delete button
			document.myform.noteTitle.disabled=true;			// disable note title field
			$('#editor1').val('').blur();						// empty cleditor textarea
		}



		//
		// UPDATE LAST ACTION INFO
		//
		function updateLastActionInformation(lastActionText)
		{
			//document.getElementById("lastAction").innerHTML = lastActionText;
			document.getElementById("lastAction").innerHTML = '<span>'+lastActionText+'</span>';
		}
		</script>
	</head>

	<body id="dt_example" class="ex_highlight_row">
		<div id="container">
			<!-- HEADER & NAV -->
			<?php include 'inc/header.php'; ?>
			<!-- CONTENT -->
			<div id="noteContentCo">
				<div id="lastAction"></div>		<!-- div to show last action - strings are loaded via js function -->
				<h2 title="the monoto-notes page">notes</h2>
				<form name="myform" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'];?>">
					<table style="width: 100%" cellspacing="0" cellpadding="5">
						<!-- show id, title and version of current selected note -->
						<tr>
							<td width="5%"><input type="text"  style="width: 20px; padding: 2px" name="noteID" disabled placeholder="ID"  onkeyup="javascript:enableSaveButton()" /></td>
							<td><input type="text" id="noteTitle" name="noteTitle" placeholder="Please select a note to see its title here" disabled style="width:100%; " /></td>
							<td><input type="button"  style="width:90px" title="Stores the current note to the db." name ="save" id="save" value="save" onClick="saveNote();" disabled="disabled"><input type="hidden" name="noteVersion" /></td>
						</tr>	
						<!-- NEW NOTE CONTENt using CKeditor -->
						<tr>
							<td colspan="2" width="95%"><textarea cols="110" id="editor1" name="editor1"></textarea></td>
							<td>
								<input type="button" style="width:90px;" title="Reloads all notes from database" value="reload" onClick="reloadNote();">
								<input type="button" style="width:90px" title="Deletes the current note from the db" name="delete" id="delete" value="delete" onClick="deleteNote();" disabled="disabled">
							</td>
						</tr>
						<!-- newTitle AND create buttons -->
						<tr>
							<td colspan="2"><input type="text" style="width:100%"  placeholder="Enter title for your new note and press the 'create' button."  id="newNoteTitle" name="newNoteTitle" onkeyup="javascript:enableCreateButton()" /></td>
							<td><input type="submit"  style="width:90px" title="Create a new note" id="createNoteButton" name="createNoteButton" value="create" onClick="createNote()" disabled="disabled"></td>
						</tr>
					</table>
				</form>

				<!--  NEW CUSTOM SEARCH FIELD -->
				<input style="float:right" type="search" id="myInputTextField" placeholder="enter search term here">					

				<!-- DATA-TABLE -->
				<table cellpadding="0" cellspacing="0" class="display" id="example" width="100%">
					<thead align="left"><tr><th>m_id</th><th>id</th><th>title</th><th>content</th><th>tags</th><th>modified</th><th>created</th><th>version</th></tr></thead>
					<tbody>

					<?php
						include 'conf/config.php';							// connect to mysql db and fetch all notes  - we should move the db-connection data to an external config file later
						include 'inc/db.php';  							// connect to db
						connectToDB();
						$rowID = 0;
						$owner = $_SESSION['username'];						// only select notes of this user
						$result = mysql_query("SELECT id, title, content, tags, date_mod, date_create, save_count FROM m_notes WHERE owner='".$owner."' ORDER by date_mod DESC ");
						while($row = mysql_fetch_array($result))
						{
							echo '<tr class="odd gradeU"><td>'.$rowID.'</td><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].'</td><td>'.$row[5].'</td><td>'.$row[6].'</td></tr>';
							$rowID = $rowID +1;
						}
					?>
					</tbody>
					<tfoot align="left"><tr><th>m_id</th><th>id</th><th>title</th><th>content</th><th>tags</th><th>modified</th><th>created</th><th>version</th></tr></tfoot>
				</table>
			</div>
			<!-- SPACER -->
			<div class="spacer">&nbsp;</div>
		</div>

		<!--  FOOTER -->
		<?php include 'inc/footer.php'; ?>
	</body>
</html>

<?php
	}
	else  	//session is NOT valid
	{
		header('Location: redirect.php');
	}
?>