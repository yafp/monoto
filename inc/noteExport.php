<?php


session_start();

// check if the user-session is valid or not
if($_SESSION['valid'] == 1)
{
	require '../conf/config.php';
	$owner = $_SESSION['username'];

	// database table
	$db_record = 'm_notes';

	// optional where query
	$where = "WHERE owner='".$owner."'";

	// filename for export
	$exportDate = date("Ymd-his");
	$csv_filename = $exportDate."-monoto-export.csv";

	// connect to db
	$conn = mysqli_connect($mysql_server, $mysql_user, $mysql_pw, $mysql_db); // connect to mysql
	if (mysqli_connect_errno()) {
	    die("Failed to connect to MySQL: " . mysqli_connect_error());
	}

	// create empty variable to be filled with export data
	$csv_export = '';

	// query to get data from database
	$query = mysqli_query($conn, "SELECT * FROM ".$db_record." ".$where);
	$field = mysqli_field_count($conn);

	// create line with field names
	for($i = 0; $i < $field; $i++) {
	    //$csv_export.= mysqli_fetch_field_direct($query, $i)->name.';';
	}

	// newline (seems to work both on Linux & Windows servers)
	//$csv_export.= '
	//';

	// loop through database query and fill export variable
	while($row = mysqli_fetch_array($query)) {
	    // create line with field values
	    for($i = 0; $i < $field; $i++) {
	        $csv_export.= '"'.$row[mysqli_fetch_field_direct($query, $i)->name].'";';
	    }
	    $csv_export.= '
	';
	}

	// Export the data and prompt a csv file for download
	header("Content-type: text/x-csv");
	header("Content-Disposition: attachment; filename=".$csv_filename."");
	echo($csv_export);
}
