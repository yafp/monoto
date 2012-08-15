/* 
KEY PRESS SCRIPT - via: http://www.geekpedia.com/tutorial138_Get-key-press-event-using-JavaScript.html 
KeyPress Codes: http://www.cambiaresearch.com/articles/15/javascript-char-codes-key-codes
*/

// SINGLE KEY PRESSES
//
document.onkeyup = KeyCheck;       
function KeyCheck(e)
{
	var KeyID = (window.event) ? event.keyCode : e.keyCode;
	switch(KeyID)
	{
		// F1 - Open online help/docs - SPECIAL ONE - is in KeyPressAll aswell ... another ugly hack as m_keyPressAll is not working inside notes.php sofar.
		case 112:
			log.debug( 'F1 key was pressed ' );				// log to blackbird logging	
			window.open('https://github.com/macfidelity/monoto/wiki');
			log.debug( 'Finished starting the monoto online help.' );
		break;



		// ESC
		case 27:
			log.debug( 'ESC key was pressed' );				// log to blackbird logging
			currentRow = -1;

			// unselect a maybe selected row in datatable - maybe via redraw.
			$(oTable.fnSettings().aoData).each(function ()
			{
				$(this.nTr).removeClass('row_selected');
			});

			// refresh the gui
			document.activeElement.value = "";								// reset newNoteTitle
			document.activeElement.blur(); 									// lose focus from newNotetitle
			document.getElementById('myInputTextField').focus();			// set focus to search
			enableCreateButton();											// run enableCreateButton from notes.php to reload buttons status etc. 
			document.myform.createNoteButton.disabled=true;					// disable create new note button afterwards to end up with a clean interface
		break;


		// Arrow Up
		case 38:
			log.debug( 'Arrow-Up key was pressed' );				// log to blackbird logging		
			// specialcase: if focus in search -> jump to first record in table
			if(document.activeElement.id == "myInputTextField")
			{
				if(currentRow == -1)			// if no row is selected - moving up doesnt makes sense - offer jump to newNotetitle
				{
					// safety first - unselect all rows
					$(oTable.fnSettings().aoData).each(function ()
					{
						$(this.nTr).removeClass('row_selected');
					});

					// jump to new note title
					document.getElementById('newNoteTitle').focus();	
				}
				else 	// select row/record above
				{
					selectUpperRow();
				}
			}
		break;


		// Arrow Down
		case 40:
			//alert("down");
			log.debug( 'Arrow-Down key was pressed' );				// log to blackbird logging

		   	// specialcase: if focus in search -> jump to first record in table
			if(document.activeElement.id == "myInputTextField")
			{
				selectNextRow();	
			}
		break;


		// DEL - delete selected note & reloads page
		case 46:
			log.debug( 'Del key was pressed' );				// log to blackbird logging
			// missing: we should do that only if a row in datatables is selected 
			// if focus is not in new title & in search
			if((document.activeElement.id != "newNoteTitle") && (document.activeElement.id != "myInputTextField"))	
			{
				deleteNote();								// execute delete function
			}
		break;


		// F5 - Reload main page
		case 116:
			log.debug( 'F5 key was pressed' );				// log to blackbird logging
			reloadNote();									// execute reload function
		break;


		// F9 - save
		case 120:
			log.debug( 'F9 key was pressed' );				// log to blackbird logging
			saveNote();										// execute save function
		break;
   	}
}