<?php
include './config/connection.php';
include './common_service/common_functions.php';

$message = '';
if(isset($_GET["del"])){
    $id = $_GET["del"];
    $reason = $_GET['reason'];
    $query1 = "
      insert into deleted_slips 
      select * from patients where id = $id
    ";
    $con->query($query1);
    $deleted_id = $con->lastInsertId();
    $con->query("update deleted_slips set reason = '$reason' WHERE id = $deleted_id");
    $query= "DELETE FROM patients WHERE id=$id";
        
         $stmtPatient = $con->prepare($query);
         $stmtPatient->execute();  
         

  $message = 'Patient deleted successfully.'; 
  header("Location:congratulation.php?goto_page=patients.php&message=$message&deleted=1");
  exit;
}  
?>