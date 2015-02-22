<?php
	session_start();
	include 'conf/config.php';
	if($_SESSION['valid'] != 1)			// check if the user-session is valid or not
	{
		header('Location: redirect.php');
	}
	else
	{
?>
		<!-- SESSION TIMEOUT WARNING -->
		<script type="text/javascript">
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
			</script>
<?php
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />
		<title>monoto notes</title>
		
		<!-- META STUFF -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="monoto notes">
		<meta name="author" content="florian poeck">

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/dataTables.scroller.min.css" />
		
		<link rel="stylesheet" type="text/css" href="css/page01.css" title="default" /> 
		<link rel="stylesheet" href="images/font-awesome-4.0.3/css/font-awesome.min.css">
		<link rel="stylesheet" href="css/bootstrap.min.css" >		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="css/bootstrap-theme.min.css" >		<!-- Bootstrap theme -->

		<!-- JS-->
		<script type="text/javascript" src="js/jquery/jquery-2.1.3.min.js"></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
		<!-- datatables -->
		
		<script type="text/javascript" language="javascript" src="js/datatables/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" src="js/datatables/dataTables.scroller.min.js"></script>
		
		<!-- noty - notifications -->
		<script type="text/javascript" src="js/noty/jquery.noty.js"></script>
		<script type="text/javascript" src="js/noty/layouts/topRight.js"></script>
		<script type="text/javascript" src="js/noty/themes/default.js"></script>
		<!-- init noty -->
		<script>
		$.noty.defaults = {
		  layout: 'topRight',
		  theme: 'defaultTheme',
		  type: 'alert',
		  text: '',
		  dismissQueue: true, // If you want to use queue feature set this true
		  template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
		  animation: {
		    open: {height: 'toggle'},
		    close: {height: 'toggle'},
		    easing: 'swing',
		    speed: 500 // opening & closing animation speed
		  },
		  timeout: 2000, // delay for closing event. Set false for sticky notifications
		  force: false, // adds notification to the beginning of queue when set to true
		  modal: false,
		  maxVisible: 3, // you can set max visible notification for dismissQueue true option,
		  closeWith: ['click'], // ['click', 'button', 'hover']
		  callback: {
		    onShow: function() {},
		    afterShow: function() {},
		    onClose: function() {},
		    afterClose: function() {}
		  },
		  buttons: false // an array of buttons
		};
		</script>

		<!-- ckeditor -->
		<script src="js/ckeditor/ckeditor.js"></script>


		<script type="text/javascript">
			var currentRow = -1;			// fill var for ugly row-selection hack with a default value
			var oTable;
			var giRedraw = false;

			$(document).ready(function() 
			{
				$("#delete").hide(); // hide the delete button
				$("#save").hide(); // show save button
				

				// is something written in the cookie as lastAction? if yes - show it as a noty notification & reset the value 
				if($.cookie("lastAction") != "")
				{
					var n = noty({text: $.cookie("lastAction"), type: 'notification'});
					$.cookie("lastAction", "");	// unset the cookie - as we want to display the lastAction only once.
				}
				



				// Defining the editor height
				monotoEditorHeight = 300; // setting a default value - in case there is non stored in localStorage
				if(typeof(Storage)!=="undefined") // if localStorage is supported
				{
					monotoEditorHeight = window.localStorage.getItem("monotoEditorHeight");
				}
				

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
				// END CKEDITOR
				
				
				
				/*
					SAVE EDITORS HEIGHT ON CHANGE
				*/
				CKEDITOR.on('instanceReady',function(ev) 
				{
					ev.editor.on('resize',function(reEvent)
					{
						editorHeight = this.ui.space( 'contents' ).getStyle( 'height' ); // get current height
						window.localStorage.setItem("monotoEditorHeight", editorHeight); //save to localstorage
					});
				});
				





				/* Add a click handler to the rows - this could be used as a callback */
				$("#example tbody").click(function(event) 
				{
					$(oTable.fnSettings().aoData).each(function ()
					{
						$(this.nTr).removeClass('row_selected');
					});
					$(event.target.parentNode).addClass('row_selected');

					document.myform.save.disabled=false;			// enable the save button
					document.myform.delete.disabled=false;			// enable the delete button
					document.myform.noteTitle.disabled=false;		// enable note title field
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
					"oLanguage": { 
						"sProcessing": "<img src='../images/loading.gif'>",
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
								{ "bSearchable": true, "bVisible": true, "sWidth": "50%" },							/* note-title */
								{ "bSearchable": true, "bVisible": false}, 							/* note-content */
								{ "bSearchable": false, "bVisible": false}, 							/* note-modification date */
							],
				} );


				/* configure a new search field & its event while typing */
				$('#myInputTextField').keyup(function()
				{
					oTable.fnFilter( $(this).val() );												// search the table
					var amountOfRecordsAfterFilter = oTable.fnSettings().fnRecordsDisplay();		// get amount of records after filter
					
					// not 1 records as result
					if(amountOfRecordsAfterFilter != 1)												// if there is only 0 record left - reset ckeditor
					{
						CKEDITOR.instances['editor1'].setData(""); // reset content of note-editor
						document.getElementById("noteTitle").value = ''; // reset  note title
							
						$(oTable.fnSettings().aoData).each(function () // unselect all records
						{
							$(this.nTr).removeClass('row_selected');
						});
					}
					
					// specialcase - only 1 record in table - load it to editor
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
					clickedTableID = $(this).closest('table').attr('id') // check the click-source
					
					
					
										
					if(clickedTableID == "example") 				// should be triggerd only for datatable
					{	
					

						var sData = oTable.fnGetData( this );											// Get the position of the current data from the node 				
						var aPos = oTable.fnGetPosition(this);											// show selected note-data as alert				
						var aData = oTable.fnGetData( aPos[1] );										// Get the data array for this row			
						CKEDITOR.instances['editor1'].setData(sData[3]);								// fill html richtext cleditor with text of selected note



						curRow =sData[0];
						//console.log(curRow);
						rowCount = oTable.fnSettings().fnRecordsTotal();
						//console.log(rowCount);
						currentRow = rowCount - curRow -1;
						//console.log("CurrentRow: "+currentRow);

						document.myform.noteID.value = sData[1]											// fill id field
						document.myform.noteTitle.value = sData[2]										// fill title field
						document.myform.noteVersion.value = sData[7]									// fill version - not displayed as field is hidden		
						document.getElementById('myInputTextField').focus();							// set focus to search - as arrow up/down navi works right now only if focus is in search
						document.getElementById("newNoteTitle").value = '';	// reset newNoteTitle (should prevent misinformations in UI if user was working on creating a new note and then selected an existing one
						
						$("#newNoteTitle").hide();
						$("#createNoteButton").hide();
						$("#delete").show(); // show delete button
						$("#save").show(); // show save button
					}
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
		// unselect/unmark all rows in table
		// 
		function unmarkAllTableRows()
		{
			console.log("Unmark all Table rows");
		
			$(oTable.fnSettings().aoData).each(function ()								// unselect all records
			{
				$(this.nTr).removeClass('row_selected');
			});
		}
		
		
		
		//
		// select and mark a single row in table
		//
		function selectAndMarkTableRow(currentRow)
		{
			console.log("Select and mark a specific table row");
			
			$('#example tbody tr:eq('+currentRow+')').click(); 						// select the top record
			$('#example tbody tr:eq('+currentRow+')').addClass('row_selected');		// change background as well
		}


		//
		//
		//
		function updateTableScrollbar()
		{
			console.log("updating table scrollbar");
			$(".dataTables_scrollBody").scrollTop(currentRow*10);
		}



		//
		// select next row
		//
		function selectNextRow()
		{
			var amountOfRecordsAfterFilter = oTable.fnSettings().fnRecordsDisplay();	// get amount of records after filter

			if( parseInt(currentRow) < (parseInt(amountOfRecordsAfterFilter) -1))		// check if moving down makes sense at all
			{
				currentRow = parseInt(currentRow) + 1;									// update row-position
				unmarkAllTableRows();
			}

			selectAndMarkTableRow(currentRow);
			updateTableScrollbar();
		}



		//
		// select other row
		//
		function selectUpperRow()
		{
			if(currentRow > 0)															// update currentRow variable
			{
				currentRow = currentRow - 1;
			}

			unmarkAllTableRows();
			selectAndMarkTableRow(currentRow);
			updateTableScrollbar();
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

			modifiedNoteContent=modifiedNoteContent.replace(/\'/g,'&#39;')				// replace: ' 	with &#39; // cleanup note content

			if((modifiedNoteID.length > 0) && (modifiedNoteID != 'ID'))					// if we have a note-id - save the change to db
			{
				$.post("inc/updNote.php", { modifiedNoteID: modifiedNoteID, modifiedNoteTitle: modifiedNoteTitle, modifiedNoteContent: modifiedNoteContent, modifiedNoteCounter: modifiedNoteCounter  } );
				var n = noty({text: 'Note saved', type: 'success'});
				$.cookie("lastAction", "Note "+modifiedNoteTitle+" saved.");	// store last Action in cookie
			}
			else 																		// should never happen as the save button is not always enabled.
			{  
				var n = noty({text: 'Error: Missing ID reference', type: 'error'});
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







		//
		// CREATE NEW NOTE
		//
		function createNote() 
		{
			var newNoteTitle = document.myform.newNoteTitle.value;					// get new title
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
				//var n = noty({text: 'Note created', type: 'success'});
				$.cookie("lastAction", "Note "+newNoteTitle+" created.");	// store last Action in cookie
				//reloadNote();
				
		  	}
			else
			{ 
				var n = noty({text: 'Error: No note title', type: 'error'});
			}
		}




		//
		// RELOAD ALL NOTES
		//
		function reloadNote() 
		{
			console.log("reloadnote executed");
			
			var loc = window.location;
    		window.location = loc.protocol + '//' + loc.host + loc.pathname + loc.search;
		}





		//
		// ENABLE CREATE NOTE BUTTON
		//
		function enableCreateButton()
		{
			// if we are starting to write a new note - erase the content of noteContent first
			if(document.myform.newNoteTitle.value.length>=1)
			{
				CKEDITOR.instances.editor1.editable().setHtml( '' );
				$('.input-group').hide(); // hides the search field
				document.myform.createNoteButton.disabled=false;	// enable Create new note button
				
				// lets clean up the main interface
				document.myform.noteID.value = "";					// empty ID of previously selected note
				document.myform.noteTitle.value = "";				// empty title of previously selected note
				document.myform.noteVersion.value = "";			// empty hiddeen version of previously selected note
				document.myform.save.disabled=true;					// disable the save button
				document.myform.delete.disabled=true;				// disable the delete button
				document.myform.noteTitle.disabled=true;			// disable note title field
				$("#save").hide();
			}
			else // got no new note -title ...so cant create new note
			{
				$('.input-group').show();
				document.myform.createNoteButton.disabled=true;	// disable Create new note button
			}
		}
		</script>
	</head>  


	<body role="document">
		<!-- Fixed navbar -->
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="notes.php"><img src="images/icons/monoto_logo01.png" height="25"></a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li class="active"><a href="notes.php" accesskey="n"><i class="fa fa-pencil-square-o fa-1x"></i> Notes</a></li>
						<li><a href="mymonoto.php" accesskey="m"><i class="fa fa-user fa-1x"></i> MyMonoto</a></li>
						<li><a href="keyboard.php"><i class="fa fa-keyboard-o fa-1x"></i> Keyboard</a></li>
						<?php
							if($_SESSION['admin'] == 1) // show admin-section
							{
								echo '<li><a href="admin.php"><i class="fa fa-cogs fa-1x"></i> Admin</a></li>';
							}
						?>
						<li><a href="#" onclick="reallyLogout();"><i class="fa fa-power-off fa-1x"></i> Logout</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
		<div class="container theme-showcase" role="main">
	<br>


	<div id="container">
		<!-- SPACER -->
		<div class="spacer">&nbsp;</div>
		<div class="spacer">&nbsp;</div>
			<!-- CONTENT -->
			<div id="noteContentCo">
				<form name="myform" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'];?>">
					<div class="input-group">
						<input placeholder="search here" id="myInputTextField" type="text" class="form-control">
						<span class="input-group-btn">
							<button  class="btn btn-default" type="button" disabled><i class="fa fa-search fa-1x"></i> Search</button>
						</span>
					</div>
				
				
					<table style="width: 100%" border="0" cellspacing="0" cellpadding="5">
						<tr><td colspan="3">&nbsp;</td></tr>
						<!-- show id, title and version of current selected note -->
						<tr>
							<td colspan="2"><input type="text" id="noteTitle" name="noteTitle" placeholder="title of selected note" disabled style="width:100%; " class="form-control" /></td>
							<td>
							 <button type="button" class="btn btn-sm btn-default" style="width:90px" title="Stores the current note to the db." name ="save" id="save" value="save" onClick="saveNote();" disabled="disabled"><input type="hidden" name="noteVersion" ><i class="fa fa-save fa-1x"></i> save</button>
							</td>
						</tr>
						<!-- NOTE CONTENT using CKeditor -->
						<tr>
							<td colspan="2" width="95%"><textarea cols="110" id="editor1" name="editor1"></textarea></td>
							<td>
							<input type="hidden" style="width: 20px; padding: 2px" name="noteID" disabled placeholder="ID" onkeyup="javascript:enableSaveButton()" />
							<button type="button" style="width:90px" class="btn btn-sm btn-danger" title="Deletes the current note from the db" name="delete" id="delete" value="delete" onClick="deleteNote();" disabled="disabled"><i class="fa fa-trash-o fa-1x"></i> delete</button>
							</td>
						</tr>
						<!-- newTitle AND create buttons -->
						<tr>
							<td colspan="2"><input type="text" style="width:100%" placeholder="enter title for your new note" id="newNoteTitle" name="newNoteTitle" onkeyup="javascript:enableCreateButton()" class="form-control" /></td>
							<td>
							<button type="submit" class="btn btn-sm btn-default" style="width:90px" title="Create a new note" id="createNoteButton" name="createNoteButton" value="create" onClick="createNote()" disabled="disabled"><i class="fa fa-pencil-square-o fa-1x"></i> create</button>
							</td>
						</tr>
					</table>
				</form>
				
				<!-- DATA-TABLE -->
				<table cellpadding="0" cellspacing="0" class="display" id="example" width="100%">
					<tbody>
					<?php
						include 'conf/config.php';							// connect to mysql db and fetch all notes  - we should move the db-connection data to an external config file later
						include 'inc/db.php';  							// connect to db
						connectToDB();
						$rowID = 0;
						$owner = $_SESSION['username'];						// only select notes of this user
						$result = mysql_query("SELECT id, title, content, date_mod FROM m_notes WHERE owner='".$owner."' ORDER by date_mod ASC ");
						while($row = mysql_fetch_array($result))
						{
							echo '<tr class="odd gradeU"><td>'.$rowID.'</td><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td></tr>';
							$rowID = $rowID +1;
						}
					?>
					</tbody>
				</table>
			</div>
			<div class="spacer">&nbsp;</div>
		</div>

    </div> <!-- /container -->
    
		<!-- loading the other scripts via LAB.js  ... without load-blocking so far -->
		<script type="text/javascript" src="js/LAB.js"></script>
		<script>
		   $LAB
		   .script("js/m_reallyLogout.js") 						// ask really-logout question if configured by admin
		   .script("js/m_disableRightClick.js")				// disabled the right-click contextmenu
		   .script("js/m_keyPressNotes.js")							// disabled the right-click contextmenu
		   .script("js/bootstrap.min.js")							// disabled the right-click contextmenu
		</script>
	</body>
</html>
