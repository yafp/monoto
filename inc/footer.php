<!--  FOOTER -->
<div id="footer" class="clear" style="text-align:center;">
<link rel="stylesheet" href="images/font-awesome-4.0.3/css/font-awesome.min.css">
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
		echo '<br>';
		echo '<a href="https://github.com/macfidelity/monoto" title="visit monoto at github"><i class="fa fa-github fa-2x"></i></a>';
		echo '&nbsp;<i class="fa fa-html5 fa-2x"></i>';
		echo '&nbsp;<i class="fa fa-css3 fa-2x"></i>';
		echo "</small>";
	?>
</div>
