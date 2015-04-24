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
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="monoto notes">
		<meta name="author" content="florian poeck">

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.min.css" />
		<link rel="stylesheet" type="text/css" href="css/dataTables.scroller.min.css" />
		
		<link rel="stylesheet" type="text/css" href="css/page01.css" title="default" /> 
		<link rel="stylesheet" href="images/font-awesome-4.3.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="css/bootstrap.min.css" >		<!-- Bootstrap core CSS -->
		<link rel="stylesheet" href="css/bootstrap-theme.min.css" >		<!-- Bootstrap theme -->

		<!-- JS-->
		<script type="text/javascript" src="js/jquery/jquery-2.1.3.min.js"></script>
		<script type="text/javascript" src="js/jquery.cookie.js"></script>
		<!-- ckeditor -->
		<script src="js/ckeditor/ckeditor.js"></script>
		<!-- datatables -->
		<script type="text/javascript" language="javascript" src="js/datatables/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" src="js/datatables/dataTables.scroller.min.js"></script>
		<!-- noty - notifications -->
		<script type="text/javascript" src="js/noty/jquery.noty.js"></script>
		<script type="text/javascript" src="js/noty/layouts/topRight.js"></script>
		<script type="text/javascript" src="js/noty/themes/default.js"></script>

		<script type="text/javascript" src="js/monoto/m_coreFunctions.js"></script>
		<script type="text/javascript" src="js/monoto/m_noteFunctions.js"></script>

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
		
		<script type="text/javascript">
			var currentRow = -1;			// fill var for ugly row-selection hack with a default value
			var oTable;
			var giRedraw = false;

			$(document).ready(function() 
			{
				$("#bt_delete").hide(); 				// hide the delete button
				$("#bt_save").hide(); 					// hide the save button
				$("#bt_createNewNoteButton").hide(); 	// hode the createNewNote button

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

				saveCKEditorHeightOnChange();
				// END CKEDITOR
				
				
				
				
				/* Add a click handler to the rows - this could be used as a callback */
				$("#example tbody").click(function(event) 
				{
					$(oTable.fnSettings().aoData).each(function ()
					{
						$(this.nTr).removeClass('row_selected');
					});
					$(event.target.parentNode).addClass('row_selected');

					document.myform.bt_save.disabled=false;				// enable the save button
					document.myform.bt_delete.disabled=false;			// enable the delete button
					document.myform.noteTitle.disabled=false;			// enable note title field
				});




				/* Add a click handler for the delete row - we dont use that so far */
				$('#bt_delete').click( function() 
				{
					//console.log("Delete handler");
					//var anSelected = fnGetSelected( oTable );
					//oTable.fnDeleteRow( anSelected[0] );
				} );






				/* Init the table */
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


						// baustelle
						curRow =sData[0];
						console.log(curRow);
						rowCount = oTable.fnSettings().fnRecordsTotal();
						console.log(rowCount);
						currentRow = rowCount - curRow -1;



						amountOfRecordsAfterFilter = oTable.fnSettings().fnRecordsDisplay();		// get amount of records after filter

						curRow =sData[1];

						
						// get all currently visible rows
						var filteredrows = $("#example").dataTable()._('tr', {"filter": "applied"});

						for ( var i = 0; i < filteredrows.length; i++ ) {
							
							if(filteredrows[i][1]== curRow)
							{
								curID=i;
							}
						};

						nextID=0;
						prevID=0;

						switch (filteredrows.length) 
						{
							 case 1:
									//console.log("sonderfall 1");
								  break;
							 default:
								  switch (curID) 
									{
										case 0:
											nextID=curID+1;
											prevID=amountOfRecordsAfterFilter-1;
										break;
				
										case amountOfRecordsAfterFilter-1:
											nextID=0;
											prevID=curID-1;
										break;
				
										default:
											nextID=curID+1;
											prevID=curID-1;
										break;
									}
								  break;
						} 

						document.myform.noteID.value = sData[1]											// fill id field
						document.myform.noteTitle.value = sData[2]										// fill title field
						document.myform.noteVersion.value = sData[5]									// fill version - not displayed as field is hidden		
						document.getElementById('myInputTextField').focus();							// set focus to search - as arrow up/down navi works right now only if focus is in search
						//document.getElementById("newNoteTitle").value = '';	// reset newNoteTitle (should prevent misinformations in UI if user was working on creating a new note and then selected an existing one
						
						// show some items
						$("#bt_delete").show();					// show delete button
						$("#bt_save").show();					// show save button

						// hide some items
						$("#newNoteTitle").hide();
						$("#bt_createNewNoteButton").hide();
						$("#bt_PrepareNoteCreation").hide();
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
		</script>
	</head>


	<body role="document">
		<?php require "inc/getText.php"; ?>
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
					<a class="navbar-brand" href="notes.php"><img src="images/icons/monoto_logo_white.png" height="25"></a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li class="active"><a href="notes.php" accesskey="n"><i class="fa fa-pencil-square-o fa-1x"></i> <?php echo translateString("Notes"); ?></a></li>
						<li><a href="mymonoto.php" accesskey="m"><i class="fa fa-user fa-1x"></i> <?php echo translateString("MyMonoto") ?></a></li>
						<li><a href="keyboard.php"><i class="fa fa-keyboard-o fa-1x"></i> <?php echo translateString("Keyboard"); ?></a></li>
						<?php
							if($_SESSION['admin'] == 1) // show admin-section
							{
								echo '<li><a href="admin.php"><i class="fa fa-cogs fa-1x"></i> ';
								echo translateString("Admin");
								echo '</a></li>';
							}
						?>
						<li><a href="#" onclick="reallyLogout();"><i class="fa fa-power-off fa-1x"></i> <?php echo translateString("Logout"); ?></a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
		<div class="container theme-showcase" role="main">
	<br>


	<div id="container">
		<div class="spacer">&nbsp;</div>
		<div class="spacer">&nbsp;</div>
			<!-- CONTENT -->
			<div id="noteContentCo">
				<form name="myform" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'];?>">
					<div class="input-group">
						<input placeholder="<?php echo translateString("search here");?>" id="myInputTextField" type="text" class="form-control">
						<span class="input-group-btn">
							<button  class="btn btn-default" type="button" disabled><i class="fa fa-search fa-1x"></i> <?php echo translateString("Search");?></button>
						</span>
					</div>
				
					<table style="width: 100%" border="0" cellspacing="0" cellpadding="5">
						<tr><td colspan="3">&nbsp;</td></tr>
						<!-- show id, title and version of current selected note -->
						<tr>
							<td colspan="2"><input type="text" id="noteTitle" name="noteTitle" placeholder="<?php echo translateString("Note title");?>" disabled style="width:100%; " class="form-control" onkeyUp="prepareNewNoteStepTwo();" /></td>
							<td>
								<button type="button" class="btn btn-sm btn-default" style="width:90px" title="Enable note creation" name ="bt_PrepareNoteCreation" id="bt_PrepareNoteCreation" onClick="prepareNewNoteStepOne();"><i class="fa fa-plus fa-1x"></i> <?php echo translateString("new");?></button>
								<button type="button" class="btn btn-sm btn-default" style="width:90px" title="Stores the current note to the db." name ="bt_save" id="bt_save" onClick="saveNote();" disabled="disabled"><input type="hidden" name="noteVersion" ><i class="fa fa-save fa-1x"></i> <?php echo translateString("save");?></button>
								<button type="submit" class="btn btn-sm btn-default" style="width:90px" title="Create a new note" id="bt_createNewNoteButton" name="bt_createNewNoteButton" onClick="createNewNote()" disabled="disabled"><i class="fa fa-pencil-square-o fa-1x"></i> <?php echo translateString("create");?></button>
							</td>
						</tr>
						<!-- NOTE CONTENT using CKeditor -->
						<tr>
							<td colspan="2" width="95%"><textarea cols="110" id="editor1" name="editor1"></textarea></td>
							<td>
							<input type="hidden" style="width: 20px; padding: 2px" name="noteID" disabled placeholder="ID" onkeyup="javascript:enableSaveButton()" />
							<button type="button" style="width:90px" class="btn btn-sm btn-danger" title="Deletes the current note from the db" name="bt_delete" id="bt_delete" onClick="deleteNote();" disabled="disabled"><i class="fa fa-trash-o fa-1x"></i> <?php echo translateString("delete");?></button>
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
						$result = mysql_query("SELECT id, title, content, date_mod, save_count FROM m_notes WHERE owner='".$owner."' ORDER by date_mod ASC ");
						while($row = mysql_fetch_array($result))
						{
							echo '<tr class="odd gradeU"><td>'.$rowID.'</td><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].'</td></tr>';
							$rowID = $rowID +1;
						}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div> <!-- /container -->

		<!-- loading the other scripts via LAB.js - without load-blocking so far -->
		<script type="text/javascript" src="js/LAB.js"></script>
		<script>
			$LAB
			.script("js/monoto/m_reallyLogout.js") 					// ask really-logout question if configured by admin
			.script("js/monoto/m_disableRightClick.js")				// disabled the right-click contextmenu
			.script("js/monoto/m_keyPressNotes.js")					// disabled the right-click contextmenu
			.script("js/bootstrap.min.js")						// disabled the right-click contextmenu
		</script>
	</body>
</html>
