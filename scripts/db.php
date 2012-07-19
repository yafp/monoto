<?php

	//
	// connect to mySQL
	//
	function connectToDB() 
	{
		include 'conf/config.php';												// include the relevant stuff (remember regarding path: who is calling this?)

		$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);			// connect to mysql	
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());							// die is useless as not visible
		}
		mysql_select_db($mysql_db, $con);										// select db
	}


	//
	// disconnect from mySQL
	//
	function disconnectFromDB() 
	{
		include 'conf/config.php';
	   	mysql_close($con);				// disconnect from mysql db 
	}


?>