<?php
// -----------------------------------------------------------------------------
// Name:		db.php
// Function:	connect to a mySQL database
// -----------------------------------------------------------------------------

function connectToDB()
{
	require 'config/config.php';

    $con = new mysqli($database_server, $database_user, $database_pw, $database_db);

	if (!$con)
	{
		echo "Error: Unable to connect to MySQL." . PHP_EOL;
    	echo "Debug-Msg: " . mysqli_connect_errno() . PHP_EOL;
    	echo "Debug-Msg: " . mysqli_connect_error() . PHP_EOL;
    	exit;
	}

	return $con;
}
?>
