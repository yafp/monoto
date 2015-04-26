<?php

function displayNoty($notyText, $notyType) 
{
	echo $notyText;
	echo $notyType;

	echo '<script type="text/javascript">var n = noty({text: "test ", type: "notification"});</script>';
}
?>
