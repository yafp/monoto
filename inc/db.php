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
		die('Could not connect: ' . mysqli_connect_error()); // die is useless as not visible
	}
	$db = mysqli_select_db($con, $mysql_db);

	return $con;
}
?>
