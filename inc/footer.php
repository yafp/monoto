<!--  FOOTER -->
<div id="footer" class="clear" style="text-align:center;">
<link rel="stylesheet" href="images/font-awesome-4.3.0/css/font-awesome.min.css">
	<?php 
		include 'conf/build.php';

		echo "<small>";
		echo "<b>".$m_name."</b> - build: ".$m_build; 
		echo '- Forgotten your password? <a href="pwreset.php">click here</a>';
		echo '<br>';
		echo '<a href="https://github.com/yafp/monoto" title="visit monoto at github"><i class="fa fa-github fa-2x"></i></a>';
		echo "</small>";
	?>
</div>
