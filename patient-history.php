<?php
include './config/connection.php';
include './common_service/common_functions.php';
date_default_timezone_set("Asia/Karachi");
$today = date("Y-m-d H:i:s");
// if(isset($_GET['print']))
// {
//   $print_id = $_GET['print'];
// 	echo '<script>window.open("slip-print.php?id='.$print_id.'")</script>';
// }


function patient_already_added($con, $patient_name, $slip_no)
{
  $query = $con->query('select patient_name, slipNumber from patients order by id desc limit 1');
  $r = $query->fetch();
  if($r['patient_name'] == $patient_name && $r['slipNumber'] == $slip_no)
  {
    return false;
  }
  return true;
}


$message = '';
if (isset($_POST['save_Patient'])) {

    $patientName = trim($_POST['patient_name']);
    
    $fName = trim($_POST['father_name']);
    $slipNumber = trim($_POST['slipno']);
    $address = trim($_POST['address']);
    $cnic = trim($_POST['cnic']);

    $dateBirth = date('Y-m-d H:i:s', strtotime($_POST['date_of_birth']));
    $phoneNumber = trim($_POST['phone_number']);

    $patientName = ucwords(strtolower($patientName));
    $address = ucwords(strtolower($address));

    $gender = $_POST['gender'];
if ($patientName != '' && $address != '' &&
  $cnic != '' && $dateBirth != '' && $phoneNumber != '' && $gender != '') {


    if(patient_already_added($con, $patientName, $slipNumber))
    {
      $query = "INSERT INTO `patients`(`patient_name`,
          `address`, `cnic`, `date_of_birth`, `phone_number`, `gender`,`father_name`,`slipNumber`)
      VALUES('$patientName', '$address', '$cnic', '$dateBirth',
      '$phoneNumber', '$gender','$fName','$slipNumber');";
    try {
    
      $con->beginTransaction();
    
      $stmtPatient = $con->prepare($query);
      $stmtPatient->execute();
      $last_id = $con->lastInsertId();
      $con->commit();
    
      $message = 'patient added successfully.';
    
    } catch(PDOException $ex) {
      $con->rollback();
    
      echo $ex->getMessage();
      echo $ex->getTraceAsString();
      exit;
    }
    }
}
  header("Location:congratulation.php?goto_page=patients.php&message=$message&print=$last_id");
  exit;
}



try {

$query = "SELECT `id`, `patient_name`, `address`,
`cnic`, date_format(`date_of_birth`, '%d %b %Y') as `date_of_birth`, date_of_birth as app_time,
`phone_number`, `gender`,`father_name`,`slipNumber`
FROM `patients`  order by `patient_name` asc;";

  $stmtPatient1 = $con->prepare($query);
  $stmtPatient1->execute();

} catch(PDOException $ex) {
  echo $ex->getMessage();
  echo $ex->getTraceAsString();
  exit;
}

 
  $query2 = "SELECT ifnull(MAX(slipNumber), 0)+1 as slipNumber FROM patients where date(date_of_birth)=curdate()";
  $stmt = $con->prepare($query2);
  $stmt->execute();
  $rown =$stmt->fetch();
   $next_slip_no =  $rown['slipNumber'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php';?>

 <?php include './config/data_tables_css.php';?>

  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <title>Patients - Hope Clinic in PHP</title>

</head>
<body class="hold-transition sidebar-minilayout-fixed layout-navbar-fixed">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
<?php include './config/header.php';
include './config/sidebar.php';?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Patient History</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      </section>

     <br/>
     <br/>
     <br/>

 <section class="content">
      <!-- Default box -->
      <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
          <h3 class="card-title">Patient History</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>

          </div>
        </div>
        <div class="card-body">
            <div class="row table-responsive">
              <table id="all_patients"
              class="table table-striped dataTable table-bordered dtr-inline"
               role="grid" aria-describedby="all_patients_info">

                <thead>
                  <tr>
                    <th>Slip Number</th>
                    <th>Patient Name</th>
                    <th>Father Name</th>
                    <th>Address</th>
                    <th>Slip Type</th>
                    <th>Time</th>
                    <th>Phone Number</th>
                    <th>Slip Price</th>
                    <!-- <th>Action</th> -->
                  </tr>
                </thead>

                <tbody>
                  <?php
                  $count = 0;
                  while($row =$stmtPatient1->fetch(PDO::FETCH_ASSOC)){
                    $count++;
                  ?>
                  <tr>
                    <td><?php echo $row['slipNumber']; ?></td>
                    <td><?php echo $row['patient_name'];?></td>
                    <td><?php echo $row['father_name'];?></td>
                    <td><?php echo $row['address'];?></td>
                    <td><?php echo $row['cnic'];?></td>
                    <td><?php echo $row['date_of_birth']; echo ' '; echo date('h:i:A', strtotime($row['app_time']))?></td>
                    <td><?php echo $row['phone_number'];?></td>
                    <td><?php echo $row['gender'];?></td>
                    

                  </tr>
                <?php
                }
                ?>
                </tbody>
              </table>
            </div>
        </div>

        <!-- /.card-footer-->
      </div>
      <!-- /.card -->


    </section>
  </div>
    <!-- /.content -->

  <!-- /.content-wrapper -->
<?php
 include './config/footer.php';

  $message = '';
  if(isset($_GET['message'])) {
    $message = $_GET['message'];
  }
?>
  <!-- /.control-sidebar -->


<?php include './config/site_js_links.php'; ?>
<?php include './config/data_tables_js.php'; ?>


<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<script>
  showMenuSelected("#patient-hisotry", "#patient-hisotry");

  var message = '<?php echo $message;?>';

  if(message !== '') {
    showCustomMessage(message);
  }
  $('#date_of_birth').datetimepicker({
        format: 'L'
    });


   $(function () {
    $("#all_patients").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#all_patients_wrapper .col-md-6:eq(0)');

  });

  function addHyphen (element) {
    	let ele = document.getElementById(element.id);
      var text = ele.value;
      if(text.search("-") == '-1')
      {
        ele = ele.value.split('-').join('');    // Remove dash (-) if mistakenly entered.

        let finalVal = ele.match(/.{1,4}/g).join('-');
        document.getElementById(element.id).value = finalVal;
      }
    }
</script>


</body>
</html>
