<?php 
	include './config/connection.php';

  	$gotoPage = $_GET['goto_page'];

    $message = $_GET['message'];
	// $print_id = $_GET['print'];

  	 header("Location:$gotoPage?message=$message");

?>
