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

	$s_enable_toc 				= true;				// should there be a toc on info.php & settings.php?
	$s_enable_really_logout		= true;				// should there be a really logout question before doing it
	$s_enable_really_delete		= true;				// should there be a 'really delete' question before deleting notes?
	$s_enable_UnstableSources 	= true;				// if true - UpdateCheck searches for dev releases as well. 
	$s_enable_welcome_message 	= true;				// should all users see a welcome message defined by you? 
	$s_welcome_message_to_all_users = "This is an example welcome message displayed for all users. It is adjustable by the admin.";
	$s_enable_user_icon			= true;				// enable user-icon in nav
	$s_user_icon_path			= "images/user_icons/user-14.png";

	// info.php:
	$s_enable_info_about_section	= true;				// show about oninfo page
	
	// #################################################################################
	// SECTION 2- you might mess with these
	// #################################################################################
	//
	// 
	$m_name						= "monoto";				// name of your notes system
	$m_tagline					= "...yet another attempt to find the ueber notes solution";	// tagline


	// #################################################################################
	// SECTION 3 - but you shouldnt touch those ... thanks. DEVELOPER-STUFF
	// #################################################################################
	//
	//
	$m_milestone				= "1";
	$m_milestone_title			= "the hello world thing";
	$m_build					= "20120717.03";
	$m_stable					= false;
?>
