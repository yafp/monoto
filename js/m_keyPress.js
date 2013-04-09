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
			window.open('https://github.com/macfidelity/monoto/wiki');
		break;


		// ESC
		case 27:
			currentRow = -1;

			// unselect a maybe selected row in datatable - maybe via redraw.
			$(oTable.fnSettings().aoData).each(function ()
			{
				$(this.nTr).removeClass('row_selected');
			});

			var n = noty({text: 'Unselected current note', type: 'notification'});

			// refresh the gui
			document.activeElement.value = "";								// reset newNoteTitle
			document.activeElement.blur(); 									// lose focus from newNotetitle
			document.getElementById('myInputTextField').focus();			// set focus to search
			enableCreateButton();											// run enableCreateButton from notes.php to reload buttons status etc. 
			document.myform.createNoteButton.disabled=true;					// disable create new note button afterwards to end up with a clean interface
			// empty the editor
			CKEDITOR.instances['editor1'].setData("");
		break;


		// Arrow Up
		case 38:
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
		   	// specialcase: if focus in search -> jump to first record in table
			if(document.activeElement.id == "myInputTextField")
			{
				selectNextRow();	
			}
		break;


		// DEL - delete selected note & reloads page
		case 46:
			// missing: we should do that only if a row in datatables is selected 
			// if focus is not in new title & in search & in noteTitle
			//if((document.activeElement.id != "newNoteTitle") && (document.activeElement.id != "myInputTextField") && (document.activeElement.id != "noteTitle"))	
			if((document.activeElement.id != "newNoteTitle") && (document.activeElement.id != "noteTitle"))	
			{
				deleteNote();								// execute delete function
			}
		break;


		// F5 - Reload main page
		case 116:
			reloadNote();									// execute reload function
		break;


		// F9 - save
		case 120:
			saveNote();										// execute save function
		break;
   	}
}