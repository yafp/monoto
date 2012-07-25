<?php
	session_start();
	include 'conf/config.php';
	if($_SESSION['valid'] == 1)			// check if the user-session is valid or not
	{
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/page.css" title="default" />
		<link rel="alternate stylesheet" type="text/css" href="css/page02.css" title="alt" />
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
	          height:       400, // height not including margins, borders or padding
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
					"sPaginationType": "full_numbers",
					"iDisplayLength": 100000,					/* default rows */
					"bLengthChange": false,
					"bPaginate": false , 					/* pagination  - BREAKS SELECTED ROW - copy content function right now*/
					"aaSorting": [[ 4, "desc" ]],				/* sorting */
					"aoColumns"   : [					/* visible columns */
								{ "bSearchable": true, "bVisible": true }, 	/* note-id */
								{ "bSearchable": true, "bVisible": true },	/* note-title */
								{ "bSearchable": true, "bVisible": false}, /* note-content */
								{ "bSearchable": false, "bVisible": false },	/* tags */
								{ "bSearchable": true, "bVisible": true }, 	/* last edit */
								{ "bSearchable": true, "bVisible": true },	/* created */
								{ "bSearchable": true, "bVisible": true }	/* save_counter */
							],
				} );


				/* configure a new search field */
				$('#myInputTextField').keypress(function()
				{
      				oTable.fnFilter( $(this).val() );	// search the table

      				// get amount of records after filter
      				var amountOfRecordsAfterFilter = oTable.fnSettings().fnRecordsDisplay();

      				// if there is only 1 record left - select/click it
      				if(amountOfRecordsAfterFilter == 1)
      				{
      					alert("only 1 record left after filtering - should autoselect that record.");
      				}

				})


				//$('.dataTables_filter input').attr("placeholder", "enter seach term here");			// define placeholder for search field
				//$("#example_filter input").focus();													// set focus on search field
				document.getElementById('myInputTextField').focus();

				$('table tr').click(function () 
				{				
					var sData = oTable.fnGetData( this );			// Get the position of the current data from the node 				
					var aPos = oTable.fnGetPosition(this);			// show selected note-data as alert				
					var aData = oTable.fnGetData( aPos[0] );		// Get the data array for this row			
					//aData[ aPos[1] ] = 'clicked';  					// Update the data array and return the value

					$('#input2').val(sData[2]).blur();				// fill html ricktext cleditor with text of selected note

					document.myform.noteID.value = sData[0]			// fill id field
					document.myform.noteTitle.value = sData[1]		// fill title field
					document.myform.noteVersion.value = sData[6]	// fill version - not displayed as field is hidden
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
		// SAVE A NOTE
		//
		function saveNote() 
		{
			var modifiedNoteID = document.myform.noteID.value;					// get the note id
			var modifiedNoteTitle = document.myform.noteTitle.value;			// get the note title 
			var modifiedNoteContent = document.myform.input2.value;			// get the NEW note content
			var modifiedNoteCounter = document.myform.noteVersion.value;		// get current save-counter/version

			// get text of cleditor
			var html = $("#input2").val();
			modifiedNoteContent = html;

			// if we have a note-id - save the change to db
			if((modifiedNoteID.length > 0) && (modifiedNoteID != 'ID'))
			{
				$.post("scripts/updNote.php", { modifiedNoteID: modifiedNoteID, modifiedNoteTitle: modifiedNoteTitle, modifiedNoteContent: modifiedNoteContent, modifiedNoteCounter: modifiedNoteCounter  } );
				reloadNote();
			}
			else
			{ 
				alert("Error while trying to save a note. Please select a record first and try again."); 
			}
		}


		//
		// DELETE A NEW NOTE
		//
		function deleteNote() 
		{
			// get the note id
			var deleteID = document.myform.noteID.value;
			var deleteTitle = document.myform.noteTitle.value;
			var deleteContent = document.myform.input2.value;

			// if we have a note id to delete - do it
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
						}
				<?php
					}
					else
					{
				?>
						$.post("scripts/delNote.php", { deleteID: deleteID, deleteTitle: deleteTitle, deleteContent: deleteContent } );
						reloadNote();	
				<?php
					}
				?>
			}
			else
			{ 
				alert("Error while trying to delete a note. Please select a record first and try again."); 
			}	
		}

		//
		// CREATE NEW NOTE
		//
		function createNote() 
		{
			var newNoteTitle = document.myform.newNoteTitle.value;
			var newNoteContent = document.myform.input2.value;

			// get text of cleditor
			var html = $("#input2").val();
			newNoteContent = html;
			// if we have a note title - create the new note (content is not needed so far)
			if (newNoteTitle.length > 0)
		  	{
		  		if(newNoteContent.length == 0)
		  		{
		  			newNoteContent = "Placeholder content - as no note-content was defined while creating this note.";			// define dummy content as user didnt
		  		}
		  		
		  		$.post("scripts/newNote.php", { newNoteTitle: newNoteTitle, newNoteContent: newNoteContent } );		// call create script
				//reloadNote();	
		  	}
			else
			{ 
				alert("Error while trying to create a new note. Please enter a note title and try again."); 
				// we should stop here and NOT reload page as we are doing right now
			}
		}


		//
		// RELOAD ALL NOTES
		//
		function reloadNote() 
		{
			javascript:history.go(0)
		}


		//
		// ENABLE CREATE NOTE BUTTON
		//
		function enableCreateButton()
		{
			document.myform.createNoteButton.disabled=false;	// enable Create new note button

			// lets clean up the main interface - as the user has choosen to create a new note by entering
			document.myform.noteID.value = "";				// empty ID of previously selected note
			document.myform.noteTitle.value = "";			// empty title of previously selected note
			document.myform.noteVersion.value = "";			// empty hiddeen version of previously selected note
			// disable sidebar buttons
			document.myform.save.disabled=true;
			document.myform.delete.disabled=true;
			document.myform.noteTitle.disabled=true;		// disable note title field
			$('#input2').val('').blur();					// empty cleditor textarea
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
					<table width="100%" cellspacing="0" cellpadding="5">
						<!-- show id, title and version of current selected note -->
						<tr>
							<td width="40px"><input type="text"  style="width: 20px; padding: 2px" name="noteID" disabled placeholder="ID"  onkeyup="javascript:enableSaveButton()" /></td>
							<td colspan="1"><input type="text" id="noteTitle" name="noteTitle" placeholder="Please select a note to see its title here" disabled style="width:100%; " /></td>
							<td><input type="button"  style="width:90px" title="Stores the current note to the db." name ="save" id="save" value="save" onClick="saveNote();" disabled="true"></td>
							<input type="hidden" style="width:50%; height:15px;" name="noteVersion" />
						<!-- NEW NOTE CONTENT using clEditor -->
						<tr>
							<td colspan="2" width="95%" height="400px"><textarea id="input2" name="input2" cols="110" ></textarea></td>
							<td>
								<input type="button"  style="width:90px;" title="Reloads all notes from database" value="reload" onClick="reloadNote();">
								<input type="button"  style="width:90px" title="Deletes the current note from the db" name="delete" id="delete" value="delete" onClick="deleteNote();" disabled="true">
							</td>
						</tr>
						<!-- newTitle AND create buttons -->
						<tr>
							<td colspan="2"><input type="text" style="width:100%"  placeholder="Enter title for your new note and press the 'create' button."  id="newNoteTitle" name="newNoteTitle" align="right" onkeyup="javascript:enableCreateButton()" /></td>
							<td><input type="submit"  style="width:90px" title="Create a new note" id="createNoteButton" name="createNoteButton" value="create" onClick="createNote()" disabled="true">							</td>
						</tr>
					</table>
				</form>

				<!-- SPACER -->
				<div class="spacer">&nbsp;</div>

				<!--  NEW CUSTOM SEARCH FIELD -->
				<input style="float:right" type="text" id="myInputTextField" placeholder="enter search term here">					

				<!-- DATA-TABLE -->
				<table cellpadding="0" cellspacing="0" class="display" id="example" width="100%">
					<thead><tr><th>id</th><th>title</th><th>content</th><th>tags</th><th>modified</th><th>created</th><th>version</th></tr></thead>
					<tbody>

					<?php
						include 'conf/config.php';							// connect to mysql db and fetch all notes  - we should move the db-connection data to an external config file later
						include 'scripts/db.php';  							// connect to db
						connectToDB();
						$owner = $_SESSION['username'];						// only select notes of this user
						$result = mysql_query("SELECT id, title, content, tags, date_mod, date_create, save_count FROM m_notes WHERE owner='".$owner."' ");
						while($row = mysql_fetch_array($result))
						{
							echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].'</td><td>'.$row[5].'</td><td>'.$row[6].'</td></tr>';
						}
						//disconnectFromDB();
					?>
					</tbody>
					<tfoot><tr><th>id</th><th>title</th><th>content</th><th>tags</th><th>modified</th><th>created</th><th>version</th></tr></tfoot>
				</table>
			</div>
			<!-- SPACER -->
			<div class="spacer">&nbsp;</div>
		</div>

		<!-- back to top -->
		<div id="message"><a href="#container">scroll to top</a></div>

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