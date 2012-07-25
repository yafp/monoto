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
			// specialcase: if focus in newNoteTitle -> reset the field
			if(document.activeElement.name == "newNoteTitle")
			{
				document.activeElement.value = "";		// reset field
				document.activeElement.blur(); 			// lose focus
			}

			document.getElementById('myInputTextField').value = '';

			
			// unselect a maybe selected row in datatable - maybe via redraw.
			$(oTable.fnSettings().aoData).each(function ()
			{
				$(this.nTr).removeClass('row_selected');
			});

			// jump to search
			//$('.dataTables_filter input').val('').keyup();						// reset search - showing all records
			//$('div.dataTables_filter input').focus();							// set focus on search-field
			document.getElementById('myInputTextField').focus();				// set focus to new search

			enableCreateButton();												// run enableCreateButton from notes.php to reload buttons status etc. 

			// disable create new note button afterwards to end up with a clean interface
			document.myform.createNoteButton.disabled=true;
		break;


		// Arrow Left
		case 37:
		 	//
		break;


		// Arrow Up
		case 38:
			// specialcase: if focus in search -> jump to new note title
			if(document.activeElement.id == "myInputTextField")
			{
				//$('#input2').cleditor()[0].focus(); 	// jump to cleditor - makes no sense
				document.getElementById('newNoteTitle').focus();
			}
		break;


		// Arrow Right
		case 39:
		  	//
		break;


		// Arrow Down
		case 40:
		   	// specialcase: if focus in search -> jump to first record in table
			if(document.activeElement.id == "myInputTextField")
			{
				alert("Arrow Down in search - jump to first record");
			}
		break;


		// DEL - delete selected note & reloads page
		case 46:
			// missing: we should do that only if a row in datatables is selected 
		   	deleteNote();
		break;


		// F1 - Open online help/docs
		case 112:
			window.open('https://github.com/macfidelity/monoto/wiki');
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