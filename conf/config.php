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

	// should there be a toc on info.php & settings.php?
	$s_enable_toc 				= true;				// true or false
	$s_enable_header_tagline 	= false;			// should the tagline be displayed in the header?
	$s_enable_really_logout		= true;				// should there be a really logout question before doing it
	$s_enable_really_delete		= true;				// should there be a 'really delete' question before deleting notes?

	$s_enable_UnstableSources 	= true;				// if true - UpdateCheck searches for dev releases as well. 

	$enable_welcome_message = true;
	$welcome_message_to_all_users = "Hi - feel free to use monoto .... pseudo welcome message adjustable by monoto-admin.";

	$s_enable_user_icon			= true;				// enable user-icon in nav
	$s_user_icon_path			= "images/myUser.jpg";

	// info.php:
	$enable_info_about_section	= false;				// show about oninfo page
	$enable_info_version_changelog_section	= false;	// should the changelog be displayed 
	$enable_info_stats_section	= true;				// show stats on info page
	$enable_info_keyboard_section	= true;			// show keyboard on info page
	


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
	$m_build					= "20120711.02";
	$m_stable					= false;

?>
