<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" type="image/ico" href="favicon.ico" />
		<title>monoto-notes</title>

		<!-- CSS -->
		<!-- general -->
		<link rel="stylesheet" type="text/css" href="css/table.css" />
		<link rel="stylesheet" type="text/css" href="css/page01.css" title="default" />
		<link rel="alternate stylesheet" type="text/css" href="css/page02.css" title="alt" />
		<!-- blackbird css-->
		<link type="text/css" rel="Stylesheet" href="css/blackbird.css" />
		
		<!-- JS which apply to all pages -->
		<!-- ########################### -->
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type='text/javascript' src='js/LAB.js'></script>
		
		<!-- loading the other scripts via LAB.js  ... without loading-blocking so far -->
		<script>
		   $LAB
		   .script("js/m_reallyLogout.js")
		   .script("js/m_disableRightClick.js")
		   .script("js/m_keyPressAll.js")
		   .script("js/blackbird.js")
		   .script("js/styleswitcher.js")
		   .script("js/m_menu.js")
		   .script("js/modal.popup.js")
		   .script("js/m_keyboardPopup.js")
		</script>
	
		<!-- closing </head> inside each single php file to be able to load other js files inside the head -->