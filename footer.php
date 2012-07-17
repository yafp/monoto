<!--  FOOTER -->
<div id="footer" class="clear" style="text-align:center;">
	<?php 
		include 'conf/config.php';
		echo "<b>".$m_name."</b> - milestone: ".$m_milestone."  '<i>".$m_milestone_title."</i>' - build: ".$m_build." - developed by <a href='https://github.com/macfidelity'>Florian Poeck</a> since 2012.<br>"; 
		echo "Please visit the ".$m_name." <a href='http://macfidelity.github.com/monoto/'>project page</a> and the <a href='https://github.com/macfidelity/monoto/wiki'>Wiki</a> for more details. If you want to participate in development consider visiting the <a href='https://github.com/macfidelity/monoto/issues'>Issue tracker</a>.<br>";
		echo "Page was generated at: ".date('Ymd H:i:s').".<br>"; 
	?>
</div>