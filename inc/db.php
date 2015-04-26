<?php
	//
	// connect to mySQL
	//
	function connectToDB() 
	{
		require 'conf/config.php';
		
		$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);			// connect to mysql	
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());							// die is useless as not visible
		}
		//mysql_select_db($mysql_db, $con);										// select db
		$db = mysql_select_db($mysql_db, $con) or die("<div class='alert alert-danger' role='alert'>Couldn't select database. Please check conf/config.php</div>");
	}
?>
