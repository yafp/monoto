<!--  FOOTER -->
<div id="footer" class="clear" style="text-align:center;">
	<?php 
		include 'conf/build.php';

		echo "<small>";
		echo "<b>".$m_name."</b> - milestone: ".$m_milestone."  '<i>".$m_milestone_title."</i>' - build: ".$m_build." - status: "; 
		if($m_stable == false)
		{
			echo "unstable";
		}
		else
		{
			echo "stable";
		}
		echo " - developed by <a href='https://github.com/macfidelity' title='main developer'>Florian Poeck</a> since 2012.<br>"; 
		echo "| <a href='http://macfidelity.github.com/monoto/'>Homepage</a> | <a href='https://github.com/macfidelity/monoto/wiki'>Wiki</a> | <a href='https://github.com/macfidelity/monoto/issues'>Issue tracker</a> |<br>";
		echo "<a href='http://opensource.org/'><img src='images/icons/OSI-logo-100x117-bkgd-dark.png' alt='open-source initiative logo' width='50'></a>";
		echo "</small>";
	?>
</div>
