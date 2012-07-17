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
		// ESC
		case 27:
			// focus in newNoteTitle
			if(document.activeElement.name == "newNoteTitle")
			{
				document.activeElement.value = "";		// reset field
				document.activeElement.blur(); 			// lose focus
			}
			$('.dataTables_filter input').val('').keyup();						// reset search - showing all records
			$('div.dataTables_filter input').focus();							// set focus on search-field
		break;


		// Arrow Left
		case 37:
		 	//
		break;


		// Arrow Up
		case 38:
		   	// jump tp previous row
			//alert("Dummy Arrow Up - Jump to previous note - current row?");
		break;


		// Arrow Right
		case 39:
		  	//
		break;


		// Arrow Down
		case 40:
		   	// jump to next note
		break;


		// DEL - delete selected note & reloads page
		case 46:
		   	deleteNote();
		break;


		// F1 - Open online help/docs
		case 112:
			window.open('https://github.com/macfidelity/monoto/wiki');
		break;


		// F2 - Rename selected note
		case 113:
			renameNote();
		break;


		// F5 - Reload main page
		case 116:
			reloadNote();
		break;


		// F9 - save
		case 120:
			saveNote();
		break;
   	}
}