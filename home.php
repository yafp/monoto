<?php
	session_start();

	// check if the user-session is valid or not
	if($_SESSION['valid'] == 1)
	{
?>


<!--
first step via http://datatables.net/examples/api/select_single_row.html
-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="http://www.yafp.de/favicon.ico" />
		
		<title>monoto - your webbased notes-keeper</title>
		<style type="text/css" title="currentStyle">
			@import "css/page.css";
			@import "css/table.css";
		</style>

		<!-- jquery -->
		<script type="text/javascript" language="javascript" src="js/jquery.js"></script>

		<!-- datatables -->
		<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>

		<!--  m_keyPress-->
		<script type="text/javascript" language="javascript" src="js/m_keyPress.js"></script>

		<!--  m_doubleClick-->
		<script type="text/javascript" language="javascript" src="js/m_doubleClick.js"></script>

		<!-- main js for table etc -->
		<script type="text/javascript" charset="utf-8">
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
				});
	

				/* Add a click handler for the delete row */
				$('#delete').click( function() 
				{
					var anSelected = fnGetSelected( oTable );
					oTable.fnDeleteRow( anSelected[0] );
				} );


	

				/* Init the table */
				oTable = $('#example').dataTable( 
				{ 
				"oSearch": {"sSearch": ""}, 
				"sPaginationType": "full_numbers",
				"iDisplayLength": 10000,					/* default rows */
				"bLengthChange": false,
				"bPaginate": true , 					/* pagination  - BREAKS SELECTED ROW - copy content function right now*/
				"aaSorting": [[ 4, "desc" ]],				/* sorting */
				"aoColumns"   : [					/* visible columns */
							{ "bSearchable": true, "bVisible": true }, 	/* note-id */
							{ "bSearchable": true, "bVisible": true },	/* note-title */
							{ "bSearchable": true, "bVisible": false }, 	/* note-content */
							{ "bSearchable": true, "bVisible": true },	/* tags */
							{ "bSearchable": true, "bVisible": true }, 	/* last edit */
							{ "bSearchable": true, "bVisible": true },	/* created */
							{ "bSearchable": true, "bVisible": true }	/* save_counter */
						],
				}
				);

				$('.dataTables_filter input').attr("placeholder", "enter seach term here");			// define placeholder for search field

				$('table tr').click(function () 
				{
				//alert("clicked ...");
				/* Get the position of the current data from the node */
				var sData = oTable.fnGetData( this );

				// show selected note-data as alert
				//alert("========= "+sData[1]);

				var aPos = oTable.fnGetPosition(this);
				//alert("aPos ..."+aPos);
				/* Get the data array for this row */
				var aData = oTable.fnGetData( aPos[0] );

				/* Update the data array and return the value */
				aData[ aPos[1] ] = 'clicked';

				// change content of current note ..dont know right now what for ;)
				//this.innerHTML = 'cli_cked';

				//var newtext = document.myform.inputtext.value;
				// reset note content
				document.myform.outputtext.value = "";

				// add data of current selected row to textarea
				document.myform.outputtext.value += sData[2]

				// we need the note id
				document.myform.noteID.value = "";
				document.myform.noteID.value += sData[0]

				// we need the note Title
				document.myform.noteTitle.value = "";
				document.myform.noteTitle.value += sData[1]

				// 
				document.myform.noteVersion.value = "";
				document.myform.noteVersion.value += sData[6]
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
			var modifiedNoteContent = document.myform.outputtext.value;			// get the NEW note content
			var modifiedNoteCounter = document.myform.noteVersion.value;		// get current save-counter/version
			
			// if we have a note-id - save the change to db
			if((modifiedNoteID.length > 0) && (modifiedNoteID != 'ID'))
			{
				$.post("scripts/updNote.php", { modifiedNoteID: modifiedNoteID, modifiedNoteTitle: modifiedNoteTitle, modifiedNoteContent: modifiedNoteContent, modifiedNoteCounter: modifiedNoteCounter  } );
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
			var deleteContent = document.myform.outputtext.value;

			// if we have a note id to delete - do it
			if ((deleteID.length > 0) && (deleteID != 'ID' ))
		  	{	

		  		// add really delete question here

		  		// delete
				$.post("scripts/delNote.php", { deleteID: deleteID, deleteTitle: deleteTitle, deleteContent: deleteContent } );
				
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
			var newNoteContent = document.myform.outputtext.value;

			// if we have a note title - create the new note (content is not needed so far)
			if ((newNoteTitle.length > 0) && (newNoteContent.length != 0 ))
		  	{
				$.post("scripts/newNote.php", { newNoteTitle: newNoteTitle, newNoteContent: newNoteContent } );
		  	}
			else
			{
		  		alert("Error while trying to create a new note. Please enter a note title plus content and try again.");
		  	}
		}


		//
		// RENAME NOTE
		//
		function renameNote() 
		{
			// is a note selected?
			var renameNoteID = document.myform.noteID.value;					// get the note id
			var renameNoteTitle = document.myform.newNoteTitle.value;			// get the NEW note title 
			var renameNoteContent = document.myform.outputtext.value;			// get the NEW note content
			var renameNoteCounter = document.myform.noteVersion.value;		// get current save-counter/version
			
			// if we have a note-id - save the change to db
			if( (renameNoteID.length > 0) && (renameNoteID != 'ID') && (renameNoteTitle.length >0) )
			{
				$.post("scripts/renNote.php", { renameNoteID: renameNoteID, renameNoteTitle: renameNoteTitle, renameNoteContent: renameNoteContent, renameNoteCounter: renameNoteCounter  } );
			}
			else
			{
				alert("Error while trying to rename a note. Please select a record first and try again.");
			}
		}


		//
		// RELOAD ALL NOTES
		//
		function reloadNote() 
		{
			javascript:history.go(0)	// reload page
		}
		</script>
	</head>




	<body id="dt_example">
		<div id="container">
			
			<!-- HEADER & NAV -->
			<?php include 'header.php'; ?>

			<div id="demo">

				<!-- SHOW SELECTED NOTE -->
				<h2>create/view/edit/rename/delete notes</h2>	

				<form name="myform">
					<!-- show id, title and version of current selected note -->
					<table border="0" width="100%" cellspacing="0" cellpadding="5">
						<tr>
							<td width="5%"><input type="input" name="noteID" disabled placeholder="ID" style="width:50%; height:15px;" /></td>
							<td colspan="1"><input type="input" name="noteTitle" placeholder="Please select a note to see its title here" disabled style="width:100%; height:15px;" /></td>
							<td><input type="button"  style="width:90px" title="Stores the current note to the db." value="save" onClick="saveNote();"></td>
							<input type="hidden" style="width:50%; height:15px;"   name="noteVersion" />
						</tr>
						<!-- note content -->
						<tr>
							<td colspan="2" width="95%"><textarea  id="txtarea" onDblClick="SelectAll('txtarea');" style="width:100%" name="outputtext" cols="110" rows="20" placeholder="Either select a note from table or create a new one using the create button. Doubleclicking this field will select the entire note text."></textarea></td>
							<td>
								<input type="button"  style="width:90px;" title="Reloads all notes from database" value="reload" onClick="reloadNote();">
								<input type="button"  style="width:90px" title="Deletes the current note from the db" value="rename" onClick="renameNote();" >
								<br><br>
								<input type="button"  style="color:#c00; width:90px" title="Deletes the current note from the db" value="delete" onClick="deleteNote();">
								<br><br><br><br><br><br><br><br><br><br><br><br><br>
							</td>
						</tr>
						<!-- newTitle & create buttons -->
						<tr>
							<td colspan="2"><input type="text" 	 style="width:100%" placeholder="Enter title for your new note and press the 'create' button."  name="newNoteTitle" align="right" /></td>
							<td><input type="submit"  style="width:90px" title="Create a new note" name="createNoteButton" value="create" onClick="createNote()"></td>
						</tr>
					</table>
				</form>


				<!-- SHOW TAGS -->
				Selected note is tagged as follows:
				<a href="">#dummyA</a> 
				<a href="">#dummyB</a>
				<a href="">#dummyC</a>
		

				<!-- SPACER -->
				<div id="spacer">&nbsp;</div>


				<h2>search notes</h2>
				<table cellpadding="0" cellspacing="0" border="0" class="display" id="example" width="100%">
					<thead>
						<tr>
							<th>id</th>
							<th>title</th>
							<th>content</th>
							<th>tags</th>
							<th>modified</th>
							<th>created</th>
							<th>version</th>
						</tr>
					</thead>
					<tbody>


					<?php
						// connect to mysql db and fetch all notes  
						// we should move the db-connection data to an external config file later
						include 'conf/config.php';

    					// connect to mysql
						$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);
						if (!$con)
					  	{
					  		die('Could not connect: ' . mysql_error());
							echo "Unable to connect to defined database - please check your credentials.";	
					  	}
						else
						{
							// do the mysql connect
							mysql_select_db("monoto", $con);

							// run the mysql query
							$result = mysql_query("SELECT * FROM m_notes");

							// fetch data and file table as a second step later on
							while($row = mysql_fetch_array($result))
							{
								echo '<tr class="odd gradeU">';
									echo '<td>'.$row[0].'</td>';
									echo '<td>'.$row[1].'</td>';
									echo '<td>'.$row[2].'</td>';
									echo '<td>'.$row[3].'</td>';
									echo '<td>'.$row[4].'</td>';
									echo '<td>'.$row[5].'</td>';
									echo '<td>'.$row[6].'</td>';	
								echo '</tr>';
							}
						}
						mysql_close($con);													// close sql connection
					?>

					</tbody>
					<tfoot>
						<tr>
							<th>id</th>
							<th>title</th>
							<th>content</th>
							<th>tags</th>
							<th>modified</th>
							<th>created</th>
							<th>version</th>
						</tr>
					</tfoot>
				</table>
			</div>
		

		<!--  FOOTER -->
		<?php include 'footer.php'; ?>

		</span>
		</div>
		</div>
	</body>
</html>



<?php
	}
	else
	{
		//session is NOT valid
		header('Location: redirect.php');
	}
   
?>