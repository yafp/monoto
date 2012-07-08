<?php
	// Function: 
	// - create a user in m_users^with hardcoded username & password
	// - please edit username & password and run the file once
	// - then delete it right away ;)

	include '../conf/config.php';

	// define user data
	$username	= "test";
	$password 	= "test";

	// playing with hash
	$hash = hash('sha256', $password);
	// playing with salt - creates a 3 character sequence
	function createSalt()
	{
    	$string = md5(uniqid(rand(), true));
    	return substr($string, 0, 3);
	}
	$salt = createSalt();
	$hash = hash('sha256', $salt . $hash);

	//echo "DEBUG OUTPUT<br>";
	//echo "username: ".$username."<br>";
	//echo "password: ".$password."<br>";
	//echo "hash: ".$hash."<br>";
	//echo "salt: ".$salt."<br>";

    // connect to mysql
	$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);		
	if (!$con)
	{
		die('Could not connect: ' . mysql_error());
	}

	mysql_select_db($mysql_db, $con);				// select db
	$query = "INSERT INTO m_users ( username, password, salt ) VALUES ( '$username' , '$hash' , '$salt' );";
	mysql_query($query);

	mysql_close($con); 								// close sql connection

?> 
