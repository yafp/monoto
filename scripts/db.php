<?php




	// connect to mySQL
	function connectToDB() 
	{
		// include the relevant stuff - as it is executed from 1 folder above we need to define the path from there ;)
		include 'conf/config.php';

	  
		// connect to mysql
		$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);		
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}


		mysql_select_db($mysql_db, $con);				// select db
	}







	// disconnect from mySQL
	function disconnectFromDB() 
	{
		// include the relevant stuff - as it is executed from 1 folder above we need to define the path from there ;)
		include 'conf/config.php';

		// disconnect from mysql db  
	   	mysql_close($con);
	}



?>