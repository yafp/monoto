<?php
// -----------------------------------------------------------------------------
// Name:		checkSession.php
// Function:	Checks for valid sesstion and redirects back to login page if needed
// -----------------------------------------------------------------------------
session_start();
if($_SESSION['valid'] != 1) // check if the user-session is valid or not
{
    header('Location: index.php'); // back to login page
    die();
}
?>
