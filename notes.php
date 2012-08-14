<?php
	session_start();
	include 'conf/config.php';
	if($_SESSION['valid'] == 1)			// check if the user-session is valid or not
	{
		include 'html_head.php';			// include the new header
?>
		<!-- continue the header -->
		<!-- ################### -->
		<!-- jquery -->
		<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
		<!-- datatables -->
		<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
		<!--  m_keyPress-->
		<script type="text/javascript" language="javascript" src="js/m_keyPress.js"></script>
		<!--  m_reallyLogout-->
		<script type="text/javascript" language="javascript" src="js/m_reallyLogout.js"></script>
		<!--  m_disableRightClick-->
		<script type="text/javascript" language="javascript" src="js/m_disableRightClick.js"></script>
		<!-- scroll up -->
		<script type="text/javascript" language="javascript" src="js/m_scrollUp.js"></script>
		<!-- styleswitcher -->
		<script type="text/javascript" language="javascript" src="js/styleswitcher.js"></script>
		<!--  CLEditor -->
		<link rel="stylesheet" type="text/css" href="jquery.cleditor.css" />
		<script type="text/javascript" src="js/jquery.cleditor.min.js"></script>
 		<script type="text/javascript">		
	      $(document).ready(function() 
	      {
	        $("#input2").cleditor({
	          width:        "100%", // width not including margins, borders or padding
	          height:       250, // height not including margins, borders or padding
	          controls:     // controls to add to the toolbar
	                        "bold italic underline strikethrough | font size " +
	                        "style | color highlight removeformat | bullets numbering | outdent " +
	                        "indent | alignleft center alignright justify | undo redo | " +
	                        "rule image link unlink | cut copy paste pastetext | print source",
	          colors:       // colors in the color popup
	                        "FFF FCC FC9 FF9 FFC 9F9 9FF CFF CCF FCF " +
	                        "CCC F66 F96 FF6 FF3 6F9 3FF 6FF 99F F9F " +
	                        "BBB F00 F90 FC6 FF0 3F3 6CC 3CF 66C C6C " +
	                        "999 C00 F60 FC3 FC0 3C0 0CC 36F 63F C3C " +
	                        "666 900 C60 C93 990 090 399 33F 60C 939 " +
	                        "333 600 930 963 660 060 366 009 339 636 " +
	                        "000 300 630 633 330 030 033 006 309 303",    
	          fonts:        // font names in the font popup
	                        "Arial,Arial Black,Comic Sans MS,Courier New,Narrow,Garamond," +
	                        "Georgia,Impact,Sans Serif,Serif,Tahoma,Trebuchet MS,Verdana",
	          sizes:        // sizes in the font size popup
	                        "1,2,3,4,5,6,7",
	          styles:       // styles in the style popup
	                        [["Paragraph", "<p>"], ["Header 1", "<h1>"], ["Header 2", "<h2>"],
	                        ["Header 3", "<h3>"],  ["Header 4","<h4>"],  ["Header 5","<h5>"],
	                        ["Header 6","<h6>"]],
	          useCSS:       false, // use CSS to style HTML when possible (not supported in ie)
	          docType:      // Document type contained within the editor
	                        '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
	          docCSSFile:   // CSS file used to style the document contained within the editor
	                        "", 
	          bodyStyle:    // style to assign to document body contained within the editor
	                        "margin:4px; font:10pt Arial,Verdana; cursor:text"
	        });
	      });
    	</script>

		<!-- main js for table etc -->
		<script type="text/javascript">
			var currentRow = -1;			// fill var for ugly row-selection hack with a default value

			var oTable;
			var giRedraw = false;

			$(document).ready(function() 
			{
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
					/* "sDom": '<"wrapper"flipt>, <l<t>ip>', */		/* resorting the datatable sDom structure - to have search & recordcount - table - recordcount */
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
					$('#input2').val(sData[3]).blur();												// fill html richtext cleditor with text of selected note
					document.myform.noteID.value = sData[1]											// fill id field
					document.myform.noteTitle.value = sData[2]										// fill title field
					document.myform.noteVersion.value = sData[7]									// fill version - not displayed as field is hidden		
					//currentRow = sData[0];															// correct current row - as its on the initial value but user select a note via mouse
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
			var modifiedNoteContent = document.myform.input2.value;						// get the NEW note content
			var modifiedNoteCounter = document.myform.noteVersion.value;				// get current save-counter/version
			// get text of cleditor
			var html = $("#input2").val();
			modifiedNoteContent = html;
			
			if((modifiedNoteID.length > 0) && (modifiedNoteID != 'ID'))					// if we have a note-id - save the change to db
			{
				$.post("scripts/updNote.php", { modifiedNoteID: modifiedNoteID, modifiedNoteTitle: modifiedNoteTitle, modifiedNoteContent: modifiedNoteContent, modifiedNoteCounter: modifiedNoteCounter  } );
				reloadNote();
				log.info('Note saved.');
			}
			else 																		// should never happen as the save button is not always enabled.
			{  
				log.debug('Error while trying to save a note. Please select a record first and try again.');
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
			var deleteContent = document.myform.input2.value;

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
							$.post("scripts/delNote.php", { deleteID: deleteID, deleteTitle: deleteTitle, deleteContent: deleteContent } );
							reloadNote();
							log.info('Note deleted.');							// blackbird js logging	
						}
				<?php
					}
					else
					{
				?>
						$.post("scripts/delNote.php", { deleteID: deleteID, deleteTitle: deleteTitle, deleteContent: deleteContent } );
						reloadNote();
						log.info('Note deleted.');								// blackbird js logging	
				<?php
					}
				?>
			}
			else // should never happen as the delete button is disabled if no note is selected
			{ 
				log.error('Error while trying to delete a note. Please select a record first and try again.');		// blackbird js logging	
			}	
		}

		//
		// CREATE NEW NOTE
		//
		function createNote() 
		{
			log.debug( 'blackbird test - createNote launched.' );							// testing blackbird js logging

			var newNoteTitle = document.myform.newNoteTitle.value;							// get new title
			//newNoteTitle = newNoteTitle.replace(/[^a-zA-Z0-9 _-]/g,'');					// replace all characters except numbers,letters, space, underscore and - .
			newNoteTitle = newNoteTitle.replace(/[^a-zA-Z0-9-._ ]/g, '');


			var newNoteContent = document.myform.input2.value;								// get note content if defined									
			newNoteContent = $("#input2").val();											// cleanup html stuff of note-content

			if (newNoteTitle.length > 0)													// if we have a note title - create the new note (content is not needed so far)
		  	{
		  		if(newNoteContent.length == 0)												// check if user defined note-content or not
		  		{
		  			newNoteContent = "Placeholder content - as no note-content was defined while creating this note.";			// define dummy content as user didnt
		  		}
		  		
		  		$.post("scripts/newNote.php", { newNoteTitle: newNoteTitle, newNoteContent: newNoteContent } );		// call create script
				//reloadNote();
				log.info('Note created.');													// blackbird js logging
		  	}
			else
			{ 
				log.error('Error while trying to create a new note. Please enter a note title and try again.');		// blackbird js logging
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
    		log.debug('reloadNote() executed.');													// blackbird js logging
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
			$('#input2').val('').blur();						// empty cleditor textarea
		}
		</script>
	</head>

	<body id="dt_example" class="ex_highlight_row">
		<div id="container">
			<!-- HEADER & NAV -->
			<?php include 'header.php'; ?>
			<!-- CONTENT -->
			<div id="noteContentCo">
				<h2>notes</h2>
				<form name="myform" method="post" action="<?php echo $PHP_SELF;?>">
					<table style="width: 100%" cellspacing="0" cellpadding="5">
						<!-- show id, title and version of current selected note -->
						<tr>
							<td width="40px"><input type="text"  style="width: 20px; padding: 2px" name="noteID" disabled placeholder="ID"  onkeyup="javascript:enableSaveButton()" /></td>
							<td colspan="1"><input type="text" id="noteTitle" name="noteTitle" placeholder="Please select a note to see its title here" disabled style="width:100%; " /></td>
							<td><input type="button"  style="width:90px" title="Stores the current note to the db." name ="save" id="save" value="save" onClick="saveNote();" disabled="disabled"><input type="hidden" name="noteVersion" /></td>
						<!-- NEW NOTE CONTENT using clEditor -->
						<tr>
							<td colspan="2" width="95%"><textarea id="input2" name="input2" cols="110" ></textarea></td>
							<td>
								<input type="button" style="width:90px;" title="Reloads all notes from database" value="reload" onClick="reloadNote();">
								<input type="button" style="width:90px" title="Deletes the current note from the db" name="delete" id="delete" value="delete" onClick="deleteNote();" disabled="disabled">
							</td>
						</tr>
						<!-- newTitle AND create buttons -->
						<tr>
							<td colspan="2"><input type="text" style="width:100%"  placeholder="Enter title for your new note and press the 'create' button."  id="newNoteTitle" name="newNoteTitle" onkeyup="javascript:enableCreateButton()" /></td>
							<td><input type="submit"  style="width:90px" title="Create a new note" id="createNoteButton" name="createNoteButton" value="create" onClick="createNote()" disabled="disabled">							</td>
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
						include 'scripts/db.php';  							// connect to db
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
		<?php include 'footer.php'; ?>
	</body>
</html>

<?php
	}
	else  	//session is NOT valid
	{
		header('Location: redirect.php');
	}
?>