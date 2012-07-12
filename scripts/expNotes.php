<?php
	header("Expires: 0");
	header("Cache-control: private");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-Description: File Transfer");
	header("Content-Type: application/vnd.ms-excel");
	header("Content-disposition: attachment; filename=export.csv");

	include ('conf/config.php');
	include ('db.php');
	connectToDB();
	$owner = $_SESSION['username'];
	echo $owner;

	$query = "SELECT * FROM m_notes WHERE owner='".$owner."' ";
	$export = mysql_query ($query ) or die ( "Sql error : " . mysql_error( ) );
	$fields = mysql_num_fields ( $export );
	for ( $i = 0; $i < $fields; $i++ )
	{
	    $header .= mysql_field_name( $export , $i ) . "\t";
	}

	while( $row = mysql_fetch_row( $export ) )
	{
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
		echo "damn";
	}
?>