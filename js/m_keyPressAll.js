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
			log.debug( 'F1 key was pressed.' );				// log to blackbird logging	
			window.open('https://github.com/macfidelity/monoto/wiki');
			log.debug( 'Finished starting the monoto online help.' );
		break;
   	}
}