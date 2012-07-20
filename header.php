<script type='text/javascript' src='js/m_menu.js'></script>

<!-- MONOTO ICON -->
<div ID="logo">
	<a href="notes.php"><img src="images/logo/monoto_logo.png" alt="monoto logo" align="right" margin-right="10"></a>
</div>


<!-- MONOTO NAVI -->
<?php 
	include 'conf/config.php';

	if($_SESSION['valid'] == 1) // is valid
	{
		session_start();
		$owner = $_SESSION['username'];
		
		echo '<ul id="nav">
		    	<li><a accesskey="n" href="notes.php"><b>n</b>otes</a></li>
				<li><a href="#">more... </a>
      				<ul>
            			<li><a accesskey="s" href="settings.php"><b>s</b>ettings</a></li>
            			<li><a accesskey="i" href="info.php"><b>i</b>nfo</a></li>';
            			// admin-section
						if($_SESSION['admin'] == 1)
						{	
							echo '<li><a accesskey="d" href="admin.php">a<b>d</b>min</a></li>';		
						}
      	echo '</ul></li>';

      	// logout
		echo '<li><a href="logout.php"> ...logout <b>'.$owner.'</b></a></li></ul>';

		// random welcome message to user
		echo '<div ID="welcome">';
			$input = array("Hello", "ahoy", "welcome", "Hallo", "bonjour", "welkom", "velkommen", "Willkommen", "aloha", "shalom", "benvenuto", "Bienvenido", "Merhaba", "Välkommen");
			echo $input[array_rand($input)]." ".$owner ;
		echo '</div>';

	}
	else // no valid session: display login only
	{
		// show nothing - redirect and stuff will take care.
	}
?>

