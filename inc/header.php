<!-- MONOTO ICON -->
<div ID="logo"><a href="notes.php"><img src="images/icons/transparent.gif" alt="monoto logo" title="Reloads the main page" width="200px" height="98px"></a></div>

<!-- MONOTO NAVI -->
<?php 
	include 'conf/config.php';

	if($_SESSION['valid'] == 1) // is valid
	{
		$owner = $_SESSION['username'];
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<a class='modal' href='javascript:void(0);''><img src='images/icons/keyboard.png' alt='keyboard shortcuts popup' title='keyboard shortcuts' width='25px'></a><br>";
		echo '<ul id="nav"><li><a accesskey="n" href="notes.php"><b>n</b>otes</a></li><li><a accesskey="m" href="mymonoto.php"><b>m</b>y monoto</a></li>';
		if($_SESSION['admin'] == 1) // show admin-section
		{	
			echo '<li><a accesskey="d" href="admin.php">a<b>d</b>min</a></li>';		
		}

		if($s_enable_random_image == true) // really logout question or not
		{
			echo '<ul id="nav"><li><a accesskey="r" href="random.php"><b>r</b>andom image</a></li>';
		}


		if($s_enable_really_logout == false) // really logout question or not
		{
			echo '<li><a accesskey="l" href="logout.php"> ...<b>l</b>ogout <b>'.$owner.'</b></a></li></ul>';
		}
		else // we need to ask the reallyLogout question
		{
			echo '<li><a accesskey="l" href="#" onclick="reallyLogout();"> ...<b>l</b>ogout <b>'.$owner.'</b></a></li></ul>';
		}
	}
	else // no valid session: display login only
	{
		// show nothing - redirect and stuff will take care.
	}
?>