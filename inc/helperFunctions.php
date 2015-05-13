<?php

function displayNoty($notyText, $notyType) 
{
	// NOTY-TYPES:
	//
	// alert
	// information
	// error
	// warning
	// notification
	// success
	echo "<script>var n = noty({text: '".$notyText."', type: '".$notyType."'});</script>";	// display notification
}


?>
