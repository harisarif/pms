<?php 
	include './config/connection.php';

  	$gotoPage = $_GET['goto_page'];

    $message = $_GET['message'];
	$print_id = $_GET['print'];

	if($_GET['already_exists'])
	{
		header("Location:$gotoPage?message=$message");
		exit;
	}
	if(isset($_GET['deleted']))
	{
		header("Location:$gotoPage?message=$message");
		exit;
	}
	else 
	{
		header("Location:$gotoPage?message=$message&print=$print_id");
		exit;
	}


?>
