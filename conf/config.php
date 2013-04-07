<?php
	// WARNING - this is a critical file. 
	// Make sure it is not worldwide-readable ;)
	
	// #################################################################################
	// SECTION 1 - you should mess with these
	// #################################################################################
	//
	$mysql_server 			= "localhost";			// define your mysql server here
	$mysql_db 				= "monoto";				// define your mysql db here	
	$mysql_user				= "monoto";				// define your mysql user here
	$mysql_pw				= "monoto";				// define the mysql user pw here


	// #################################################################################
	// SECTION 2- you might mess with these
	// #################################################################################
	//
	$s_enable_maintenance_mode	= false;			// true = login is locked.
	$s_enable_random_logout_gif = true;				// show a random logout image
	$s_enable_really_logout		= true;				// should there be a really logout question before doing it
	$s_enable_really_delete		= false;			// should there be a 'really delete' question before deleting notes?
	$s_enable_UnstableSources 	= true;				// if true - UpdateCheck searches for dev releases as well. 
	$s_enable_user_icon			= true;				// enable user-icon in nav
	$s_user_icon_path			= "images/icons/user-14.png";

	// #################################################################################
	// SECTION 3 - but you shouldnt touch those ... thanks. DEVELOPER-STUFF
	// #################################################################################
	//
	$m_name						= "monoto";						// name of your notes system
	$m_milestone				= "3";							// current milestone number
	$m_milestone_title			= "pimping the workflow";		// current milestone name
	$m_build					= "20130407.02";				// build-date and day's version
	$m_stable					= false;						// defined if that is a milestone release
?>
