<!-- MONOTO ICON -->
<div ID="logo">
	<a href="notes.php"><img src="images/icons/monoto_logo.png" alt="monoto logo" style="float:right"></a>
</div>


<!-- MONOTO NAVI -->
<?php 
	include 'conf/config.php';

	if($_SESSION['valid'] == 1) // is valid
	{
		//session_start();
		$owner = $_SESSION['username'];
	?>
		<!-- stylesheet-switcher -->
		&nbsp;
		<a href="#" title="default stylesheet" onclick="setActiveStyleSheet('default'); return false;"><small>/b</small></a>
		<a href="#" title="alt stylesheet" onclick="setActiveStyleSheet('alt'); return false;"><small>/o</small></a>

	<?php	
		echo "<br>";
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
		echo '<li><a accesskey="l" href="logout.php"> ...<b>l</b>ogout <b>'.$owner.'</b></a></li></ul>';

		// random welcome message to user
		echo '<div ID="welcome">';
			$welcomeArray = array("Hello", "ahoy", "welcome", "Hallo", "bonjour", "welkom", "velkommen", "Willkommen", "aloha", "shalom", "benvenuto", "Bienvenido", "Merhaba", "Välkommen", "Wellkumma", "Bonvenon", "Salve", "Bun venit" );
			$relation = array("english", "czech", "english", "german", "french", "dutch", "Norwegian", "german", "hawai", "hebrew", "italian", "spanish", "turkish", "swedish", "Wellkumma", "esperanto", "romanian", "romania" );
			$myRandomPick = array_rand($welcomeArray);				// pick a random text/number
			$myRandomPickLanguage = ($relation[$myRandomPick]);		// pick related language
			echo $welcomeArray[$myRandomPick]." ".$owner."<br>" ;	// show the random text and the related language
			echo "<small><i>... that's ".$myRandomPickLanguage."</i></small>";
		echo '</div>';
		
	}
	else // no valid session: display login only
	{
		// show nothing - redirect and stuff will take care.
	}
?>