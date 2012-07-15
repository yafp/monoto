<?php 
	include 'conf/config.php';

	// HEADER
	echo '<div id="header">';
		echo '<a href="notes.php"><img src="images/logo/icon_big_mod2.png" width="80px" align="left"></a>';			// display monoto logo

		if($s_enable_header_tagline == true)				// display tagline if enabled
		{ echo "<small>".$m_tagline."</small>";  }	
	echo '</div>';

	// NAVI
	// 
	echo '<div id="navi">';
		echo "<table border='0' align='right'>";
			echo "<tr>";
				// check if the user-session is valid or not
				if($_SESSION['valid'] == 1) // is valid
				{
					// USER ICON manual user-icon-hack
					if($s_enable_user_icon == true)
					{
					echo "<td>";
						echo  '<img src='.$s_user_icon_path.' align="right" border="1" border-color:#000000 width="25px" style="filter:alpha(opacity=60); opacity: 0.6;">';
					echo "</td>";
					}
					// WELCOME TEXT
					echo "<td>Welcome ".$_SESSION['username']." </td>";
					echo "<td>";
					
					session_start();

					$var=explode('?',$_SERVER['REQUEST_URI']);
					$page=preg_replace('/.*\/([^\/])/','$1',$var[0]);
					unset($var);

					// we should fix that with proper css usage
					//
					// home
					if ($page == 'notes.php')
					{ echo '| <a accesskey="y" href="notes.php" title="jump to the main monoto page" style="text-decoration: underline;">&nbsp;notes&nbsp;</a>'; }
					else { echo '| <a accesskey="y" href="notes.php" title="jump to the main monoto page">&nbsp;notes&nbsp;</a>'; }
					// settings
					if ($page == 'settings.php')
					{ echo '| <a accesskey="x" href="settings.php" title="jumps to the monoto settings page" style="text-decoration: underline;">&nbsp;settings&nbsp;</a>'; }
					else { echo '| <a accesskey="x" href="settings.php" title="jumps to the monoto settings page">&nbsp;settings&nbsp;</a>'; }
					// info
					if ($page == 'info.php')
					{ echo '| <a accesskey="c" href="info.php" title="jumps to the monoto info page" style="text-decoration: underline;">&nbsp;info&nbsp;</a>'; }
					else { echo '| <a accesskey="c" href="info.php" title="jumps to the monoto info page">&nbsp;info&nbsp;</a>'; }
					// admin
					if($_SESSION['admin'] == 1)
					{
						if ($page == 'admin.php')
						{ echo '| <a accesskey="v" href="admin.php" title="jumps to the monoto admin page" style="text-decoration: underline;">&nbsp;admin&nbsp;</a>'; }
						else { echo '| <a accesskey="v" href="admin.php" title="jumps to the monoto admin page">&nbsp;admin&nbsp;</a>'; }
					}
					// logout
					if ($s_enable_really_logout == true)
					{ echo '| <a href="javascript:void(0)" onclick="reallyLogout();" title="jumps to the monoto login page">&nbsp;logout&nbsp;</a> |<br>'; }
					else
					{ echo '| <a href="logout.php" title="jumps to the monoto login page">&nbsp;logout&nbsp;</a> |<br>'; }
				}
				else // not logged in / non valid session - so just show him the login menu
				{ echo '| <a accesskey="x" href="index.php" title="jumps to the monoto login page">&nbsp;login&nbsp;</a> |' ; }
				echo "</td>";
			echo "</tr>";
		echo "</table>";
		echo "</div>";
		echo "<br>";
?>