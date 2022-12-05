<?php
include './config/connection.php';

if(isset($_POST['type']))
{

extract($_POST);
if($type == 0)
{ 
    $query = "SELECT `id`, `patient_name`, `address`,
    `cnic`, date_format(`date_of_birth`, '%d %b %Y') as `date_of_birth`, date_of_birth as app_time,
    `phone_number`, `gender`,`father_name`,`slipNumber`
    FROM `patients` where date(date_of_birth) = curdate()  order by `slipNumber` asc;";
}
else 
{
    $query = "SELECT `id`, `patient_name`, `address`,
    `cnic`, date_format(`date_of_birth`, '%d %b %Y') as `date_of_birth`, date_of_birth as app_time,
    `phone_number`, `gender`,`father_name`,`slipNumber`
    FROM `patients` where gender = $type and date(date_of_birth) = curdate() order by `slipNumber` asc;";
}
  $stmtPatient1 = $con->prepare($query);
  $stmtPatient1->execute();
  $count = 0;
        while($row =$stmtPatient1->fetch(PDO::FETCH_ASSOC)){
        $count++;
        ?>
        <tr>
        <td><?php echo $row['slipNumber']; ?></td>
        <td><?php echo $row['patient_name'];?></td>
        <td><?php echo $row['father_name'];?></td>
        <td><?php echo $row['gender'];?></td> 
        <td><?php echo $row['address'];?></td>  
        </tr>
    <?php
    }  
exit;
}