<?php 
include './config/connection.php';
$print_id = $_GET['id'];
$query = "select * from patients where id =".$print_id;

$stmt = $con->prepare($query);
$stmt->execute();
$row = $stmt->fetch();
?>

<div style="margin-top:300px;">
<h1 align="center">Slip Print Here</h1>

</div>


