<?php
include './config/connection.php';
include './common_service/common_functions.php';

$message = '';
if(isset($_GET["del"])){
    $id = $_GET["del"];
    $query= "DELETE FROM patients WHERE id=$id";
        
         $stmtPatient = $con->prepare($query);
         $stmtPatient->execute();  
         

  $message = 'Patient deleted successfully.'; 
  header("Location:congratulation.php?goto_page=patients.php&message=$message");
  exit;
}  
?>