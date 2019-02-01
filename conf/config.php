<?php
	// WARNING - this is a critical file.
	// Make sure it is not worldwide-readable ;)

	// #################################################################################
	// SECTION 1 - you should mess with these
	// #################################################################################
	//
	$mysql_server 			= "localhost"; // define your mysql server here
	$mysql_db 				= "monoto"; // define your mysql db here
	$mysql_user				= "monoto"; // define your mysql user here
	$mysql_pw				= "monoto"; // define the mysql user pw here


	// #################################################################################
	// SECTION 2- you might mess with these
	// #################################################################################
	//
	$s_enable_maintenance_mode		= false; // true = login is locked.


	// #################################################################################
	// SECTION 3 - Configure debug output for developers
	// #################################################################################
	//
	define("DEVELOPMENT", true);
	if ((defined('DEVELOPMENT')) && (DEVELOPMENT === true))
	{
		ini_set('display_errors', 1);
	}
?>
