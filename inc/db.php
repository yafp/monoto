<?php
// Name:		db.php
// Function:	connect to mySQL
//
function connectToDB()
{
	require 'conf/config.php';

	$con = mysqli_connect($mysql_server, $mysql_user, $mysql_pw, $mysql_db);
	if (!$con)
	{
		echo "Fehler: konnte nicht mit MySQL verbinden." . PHP_EOL;
    	echo "Debug-Fehlernummer: " . mysqli_connect_errno() . PHP_EOL;
    	echo "Debug-Fehlermeldung: " . mysqli_connect_error() . PHP_EOL;
    	exit;
	}
	$db = mysqli_select_db($con, $mysql_db);

	return $con;
}
?>
