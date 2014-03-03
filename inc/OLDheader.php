<!-- MONOTO ICON -->
<div ID="logo">
	<a href="notes.php"><img src="images/icons/transparent.gif" alt="monoto logo" title="Reloads the main page" height="50px"></a>

	<?php
		function curPageName() 
		{
 			return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
		}
	?>

</div>


<!-- MONOTO NAVI -->
<?php 
	include 'conf/config.php';

	if($_SESSION['valid'] == 1) // is valid
	{
		$owner = $_SESSION['username'];
		echo '<div id="neuNav">';

			echo '<ul id="nav">';
			
			// only on notes.php -  show search-field
			if((curPageName() == "notes.php") ) 
			{
				echo '<li><input type="search" id="myInputTextField" placeholder="enter search term" style="width:100%;"><li>';
			}
			
			echo "<li>&nbsp&nbsp&nbsp</li>";
			
			// notes
			echo '<li class="active"><a href="notes.php" accesskey="n" title="notes"><i class="fa fa-pencil-square-o fa-2x"></i></a></li>';
			
			// mobile interface
			echo '<li class="active"><a href="mobile/index.php" title="mobile interface"><i class="fa fa-mobile fa-2x"></i></a></li>';
			
			// my monoto
			echo '<li class="active"><a href="mymonoto.php" accesskey="m" title="my monoto"><i class="fa fa-user fa-2x"></i></a></li>';
			
			if($_SESSION['admin'] == 1) // show admin-section
			{	
				echo '<li class="active"><a href="admin.php" accesskey="d" title="admin"><i class="fa fa-cogs fa-2x"></i></a></li>';
			}

			if($s_enable_random_image == true) // random image function
			{
				echo '<li class="active"><a href="random.php" accesskey="r" title="random image"><i class="fa fa-camera-retro fa-2x"></i></a></li>';
			}
			
			// keyboard shortcuts
			echo '<li class="active"><a href="keyboard.php" title="keyboard shortcuts"><i class="fa fa-keyboard-o fa-2x"></i></a></li>';
			
			
			// really logout
			if($s_enable_really_logout == false) // really logout question or not
			{
				echo '<li class="active"><a href="logout.php" title="logout" accesskey="l"><i class="fa fa-power-off fa-2x"></i></a></li>';
			}
			else // we need to ask the reallyLogout question
			{
				echo '<li class="active"><a href="#" title="logout" accesskey="l" onclick="reallyLogout();"><i class="fa fa-power-off fa-2x"></i></a></li>';
			}
			
		echo '</div>';
	}
	else // no valid session: display login only
	{
		// show nothing - redirect and stuff will take care.
	}
?>
