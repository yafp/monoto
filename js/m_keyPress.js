/* 

KEY PRESS SCRIPT - via: http://www.geekpedia.com/tutorial138_Get-key-press-event-using-JavaScript.html 
KeyPress Codes: http://www.cambiaresearch.com/articles/15/javascript-char-codes-key-codes

*/


// ALT + 1-3 for page navigation
			//
			/*
			function detectspecialkeys(e)
			{
				var evtobj=window.event? event : e
				//if (evtobj.altKey || evtobj.ctrlKey || evtobj.shiftKey)
				if (evtobj.altKey)
				{
					alert("you pressed the 'Alt' key")
				}
				else
				{
					//alert("kommt auch bei einzel-presses")
				}
			}
			document.onkeypress=detectspecialkeys
			*/






			// SINGLE KEY PRESSES
			//
			document.onkeyup = KeyCheck;       
			function KeyCheck(e)
			{
				var KeyID = (window.event) ? event.keyCode : e.keyCode;
				switch(KeyID)
   				{
   					
					case 16:
						//document.Form1.KeyName.value = "Shift";
					break; 

      				case 17:
						//document.Form1.KeyName.value = "Ctrl";
      				break;

      				case 27:
						// quick test
						//alert(document.activeElement.name);

						// focus in newNoteTitle
						if(document.activeElement.name == "newNoteTitle")
						{
							document.activeElement.value = "";		// reset field
							document.activeElement.blur(); 			// lose focus
						}

						// focus in content textarea
						if(document.activeElement.name == "outputtext")
						{
							document.activeElement.value = ""; 
							document.activeElement.blur();
						}

						// focus in search
						if(document.activeElement.name == "")
						{
							//alert("search? - lets reset the search right?");
							
						}


						// reset search - showing all records
						$('.dataTables_filter input').val('').keyup();

						

						// reload page - aktualisiert die seite inkl daten - eigen taste?
						//window.location = 'http://localhost/monoto/index.php';

						// set focus on search-field
						$('div.dataTables_filter input').focus();
      				break;


      				// Arrow Left
				   	case 37:
				    	//document.Form1.KeyName.value = "Arrow Left";
				   	break;


				   	// Arrow Up
				    case 38:
				    	// jump tp previous row
				  		alert("Dummy Arrow Up - Jump to previous note - current row?");
				    break;


				    // Arrow Right
				    case 39:
				      	//document.Form1.KeyName.value = "Arrow Right";
				    break;


				    // Arrow Down
				    case 40:
				      	// jump to next note
				      	alert("Dummy Arrow Down - Jump to next note - current row?");
				   	break;


				   	// DEL - delete selected note
				   	case 46:
				      	// jump to next note
				      	// alert("Dummy Del - Delete selected note - current row?");
				      	deleteNote();
				   	break;


				   	// F1 - Open online Help
				   	case 112:
						window.location = 'http://google.com','_blank';

					break;


					// F2 - Rename selected note
				   	case 113:
						//alert("F2 - rename? we dont have that so far");
						renameNote();
					break;


					// F5 - Reload main page
					case 116:
						reloadNote();
					break;

					// F9 - save
					case 120:
						//alert("save via f10");
						saveNote();
					break;
   				}
			}