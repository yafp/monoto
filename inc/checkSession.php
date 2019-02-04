<?php
session_start();
if($_SESSION['valid'] != 1) // check if the user-session is valid or not
{
    header('Location: redirect.php');
    die();
}
?>
