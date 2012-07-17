<script type='text/javascript' src='js/m_menu.js'></script>

<!-- MONOTO ICON -->
<div ID="navi">
<a href="notes.php"><img src="images/logo/icon_big.png" width="90" alt="monoto logo" align="right" margin-right="10"></a>
</div>

<!-- MONOTO NAVI -->
<?php 
	include 'conf/config.php';

	if($_SESSION['valid'] == 1) // is valid
	{
		session_start();
		$owner = $_SESSION['username'];
		
		echo '<ul id="nav">
				
		    	<li><a accesskey="y" href="notes.php">notes</a></li>
		    	<li><a accesskey="x" href="settings.php">settings</a></li>
			    <li><a accesskey="c" href="info.php">info</a></li>';
    			
    	// admin-section
		if($_SESSION['admin'] == 1)
		{	echo '<li><a href="admin.php">admin</a></li>';		}
		
		// logout
		echo '<li><a href="logout.php"> ...logout '.$owner.'</a></li></ul>';
	}
	else // no valid session: display login only
	{
		// snhow nothing - redirect andstuff will take care.
		//echo '<ul id="nav"><li><a href="index.php">login</a></li></ul>';
	}
?>