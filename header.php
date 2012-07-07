<!-- HEADER -->
<div id="header">
<?php 
	include 'conf/config.php';

	echo '<a href="home.php"><img src="images/logo.png" width="140px" align="left"></a>';

	if($s_enable_header_tagline == true)
	{
		echo "<small>".$m_tagline."</small>"; 
	}	
?>
</div>

<div id="navi">

<?php

	session_start();

	// check if the user-session is valid or not
	if($_SESSION['valid'] == 1)
	{
		echo $_SESSION['userid'];

		echo "Welcome to monoto... ";

		$var=explode('?',$_SERVER['REQUEST_URI']);
		$page=preg_replace('/.*\/([^\/])/','$1',$var[0]);
		unset($var);

		// we should fix that with proper css usage
		// home
		if ($page == 'home.php')
		{ echo '| <a accesskey="y" href="home.php" title="jump to the main monoto page" style="text-decoration: underline;">&nbsp;notes&nbsp;</a>'; }
		else { echo '| <a accesskey="y" href="home.php" title="jump to the main monoto page">&nbsp;notes&nbsp;</a>'; }

		// settings
		if ($page == 'settings.php')
		{ echo '| <a accesskey="x" href="settings.php" title="jumps to the monoto settings page" style="text-decoration: underline;">&nbsp;settings&nbsp;</a>'; }
		else { echo '| <a accesskey="x" href="settings.php" title="jumps to the monoto settings page">&nbsp;settings&nbsp;</a>'; }

		// info
		if ($page == 'info.php')
		{ echo '| <a accesskey="c" href="info.php" title="jumps to the monoto info page" style="text-decoration: underline;">&nbsp;info&nbsp;</a>'; }
		else { echo '| <a accesskey="c" href="info.php" title="jumps to the monoto info page">&nbsp;info&nbsp;</a>'; }

		echo '| <a accesskey="x" href="logout.php" title="jumps to the monoto login page">&nbsp;logout&nbsp;</a> |<br>';

		// manual user-icon-hack
		if($s_enable_user_icon == true)
		{
			echo '<img src='.$s_user_icon_path.' align="right" width="50px" style="filter:alpha(opacity=60); opacity: 0.6;">';
		}
	}
	else // not logged in - so other menu
	{
		echo '| <a accesskey="x" href="index.php" title="jumps to the monoto login page">&nbsp;login&nbsp;</a> |' ;
	}
?>

</div>
<br>
<br>


