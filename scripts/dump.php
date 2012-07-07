<?php
/*
* Database MySQLDump Control File
* Copyright (c) 2009 by James Elliott
* James.d.Elliott@gmail.com
* GNU General Public License v3 http://www.gnu.org/licenses/gpl.html
*/

require_once('./mysqldump.class.php'); 		//Location Of Class File.

// START: monoto mod
//
// fit monoto vars to dump vars:
include '../conf/config.php';				// include the monoto conf file for credentials

$dbhost   = $mysql_server; 			//Server Hostname.
$dbuser   = $mysql_user; 			//Server User Name.
$dbpass   = $mysql_pw; 				//Server Password.
$dbname   = $mysql_db; 				//Database Name On MySQL Server.

$drop_table_if_exists = false; //Add MySQL 'DROP TABLE IF EXISTS' Statement To Output?
$version2 = '1.1.2'; //Script Version.
//
// END: monoto mod



//START xHTML 1.0 Strict Header Output. ----------------------------------------------
$title    = 'Database MySQLDump '.$version1.' for '.$dbname; //Write Title Info to Variable Using Chosen Database Name and Version.
echo "<pre>";
echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
echo "\n<html xmlns=\"http://www.w3.org/1999/xhtml\">";
echo "\n<head>";
echo "\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";
echo "\n<link href=\"mysqldump.css\" rel=\"stylesheet\" type=\"text/css\" />";
echo "\n<title>".$title."</title>"; 
echo "\n</head>";
echo "\n<body>\n";
echo "</pre>";
//END xHTML 1.0 Strict Header Output. ------------------------------------------------

$backup = new MySQLDump();
$backup->droptableifexists = $drop_table_if_exists;
$backup->connect($dbhost,$dbuser,$dbpass,$dbname); //Connect To Database
if (!$backup->connected) { die('_Error: '.$backup->mysql_error); } //On Failed Connection, Show Error.
$backup->list_tables(); //List Database Tables.
$broj = count($backup->tables); //Count Database Tables.

//START Page Header Output. ----------------------------------------------------------
echo "<div>";
echo "-------------------------------------------------<br />";
echo "--- Database MySQLDump Class Script ".$version1." ---<br />";
echo "-- Database MySQLDump Control Script ".$version2." --<br />";
echo "-------------------------------------------------<br />";
echo "</div>";
//END Page Header Output. ------------------------------------------------------------

echo "<pre>";
echo "\nDatabase Selected: $dbname on $dbhost ($dbuser:$dbpass)"; //Show Database, Server, User Name and Password.

//START Database MySQL Dump. ---------------------------------------------------------
echo "\n-- STARTING MYSQL DATABASE DUMP --";
for ($i=0;$i<$broj;$i++) {
	$table_name = $backup->tables[$i]; //Get Table Names.
	$backup->dump_table($table_name); //Dump Data to the Output Buffer.
	echo htmlspecialchars($backup->output); //Display Output.
}
echo "\n-- END OF MYSQL DATABSE DUMP --";
//END Database MySQL Dump. -----------------------------------------------------------

echo "\n</pre>";
echo "<div><br /><br />Database MySQLDump Class ".$version1." ".$copy." by James Elliott.<br />"; //Copyrights to Class File.
echo "Database MySQLDump Control Script ".$version2." ".$copy." by James Elliott.<br /></div>"; //Copyrights to Control File.
echo "<div>";
echo "<pre>";
echo "<a href=\"http://validator.w3.org/check?uri=referer\">";
echo "\n<img src=\"http://www.w3.org/Icons/valid-xhtml10\" alt=\"Valid XHTML 1.0 Strict\" height=\"31\" width=\"88\" />";
echo "\n</a>\n";
echo "</pre>";
echo "</div>";
echo "<pre>";
echo "</body>";
echo "</html>";
?>