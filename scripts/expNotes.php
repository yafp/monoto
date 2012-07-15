
<?php
	
	header("Expires: 0");
	header("Cache-control: private");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-Description: File Transfer");
	header("Content-Type: application/vnd.ms-excel");
	header("Content-disposition: attachment; filename=export.csv");
	
	

	session_start();
	// check if the user-session is valid or not
	if($_SESSION['valid'] == 1)
	{
		$owner = $_SESSION['username'];
		//echo $owner;
		//echo "<br><br>";

		$mysql_server 			= "localhost";			// define your mysql server here
		$mysql_db 				= "monoto";				// define your mysql db here	
		$mysql_user				= "monoto";				// define your mysql user here
		$mysql_pw				= "monoto";				// define the mysql user pw here


		// connect to mysql
		$con = mysql_connect($mysql_server, $mysql_user, $mysql_pw);		
		if (!$con)
		{
			die('Could not connect: ' . mysql_error());
		}
		mysql_select_db($mysql_db, $con);				// select db

 

		//$select = "SELECT * FROM m_notes";
		$select = "SELECT id, title, content FROM m_notes WHERE owner='".$owner."'";
		$export = mysql_query ( $select ) or die ( "Sql error : " . mysql_error( ) );
		$fields = mysql_num_fields ( $export );

		// columns
		for ( $i = 0; $i < $fields; $i++ )
		{
		    $header .= mysql_field_name( $export , $i ) . "\t";
		}

		// rows
		while( $row = mysql_fetch_row( $export ) )
		{
			echo "";
		    $line = '';
		    foreach( $row as $value )
		    {                                            
		        if ( ( !isset( $value ) ) || ( $value == "" ) )
		        {
		            $value = "\t";
		        }
		        else
		        {
		            $value = str_replace( '"' , '""' , $value );
		            $value = '"' . $value . '"' . "\t";
		        }
		        $line .= $value;
		    }
		    $data .= trim( $line ) . "\n";
		}


		$data = str_replace( "\r" , "" , $data );

		if ( $data == "" )
		{
		    $data = "\n(0) Records Found!\n";                        
		}
		else
		{
			echo "$data is empty";
		}


	}
	else
	{
		echo "you are not allowed to do this";
	}
?>