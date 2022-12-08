<?php 
include './config/connection.php';
$print_id = $_GET['id'];
$query = "select * from patients where id =".$print_id;

$stmt = $con->prepare($query);
$stmt->execute();
$row = $stmt->fetch();

?>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
</style>

<div style="margin-top:100px;">
<h1 align="center">HOPE CLINIC</h1>
<p align="center"><?php echo date('d/m/Y h:i:A',strtotime($row['date_of_birth'])) ?></p>
<table>
  <tr>
    <th>Slip Number</th>
    <th>Patient Name</th>
    <th>Father Name</th>
    <th>Slip Price</th>
    <th>Slip Type</th>
  </tr>
  <tr>
    <td><?php echo $row['slipNumber']?></td>
    <td><?php echo $row['patient_name']?></td>
    <td><?php echo $row['father_name']?></td>
    <td><?php echo $row['gender']?><?php if ($row['is_zf']==1) {
                          echo 'ZF';
                  }
                      ?></td>
    <td><?php echo $row['cnic']?></td>
  </tr>
  
</table>
</div>

<script>

window.print();
window.onafterprint = window.close;
</script>
