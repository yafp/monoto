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
		// F1 - Open online help/docs
		case 112:
			window.open('https://github.com/yafp/monoto/wiki');
		break;



		// F2 - trigger maximize editor to fullscreen
		case 113:
			CKEDITOR.instances.editor1.execCommand( 'maximize' );
		break;



		// ESC - reset the UI
		case 27:
			resetNotesUI();
		break;



		// Arrow Up
		case 38:
			// specialcase: if focus in search -> jump to first record in table
			if(document.activeElement.id == "myInputTextField")
			{
				console.log("Key: Arrow Up (Code 38)");
				selectUpperRow();
			}
		break;



		// Arrow Down
		case 40:
		   	// specialcase: if focus in search -> jump to first record in table
			if(document.activeElement.id == "myInputTextField")
			{
				console.log("Key: Arrow Down (Code 40)");
				selectNextRow();
			}
		break;



		// DEL - delete selected note & reloads page
		case 46:
			// missing: we should do that only if a row in datatables is selected 
			// if focus is not in new title & in search & in noteTitle
			if((document.activeElement.id != "newNoteTitle") && (document.activeElement.id != "noteTitle") && (document.activeElement.id != "myInputTextField"))	
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
