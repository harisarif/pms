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
  header("Location:congratulation.php?goto_page=patients.php&message=$message&print=$last_id");
  exit;
}



try {

$query = "SELECT `id`, `patient_name`, `address`,
`cnic`, date_format(`date_of_birth`, '%d %b %Y') as `date_of_birth`, date_of_birth as app_time,
`phone_number`, `gender`,`father_name`,`slipNumber`
FROM `patients` where date(date_of_birth) = curdate() order by `patient_name` asc;";

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
            <h1>Patients</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
     <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
          <h3 class="card-title">Add Patients</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>

          </div>
        </div>
        <div class="card-body">
          <form method="post">
            <div class="row">
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                  <label>Slip Number</label>
                  <input type="number" id="slipno" name="slipno" required="required" value="<?= $next_slip_no?>"
                  class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
              <label>Patient Name</label>
              <input type="text" id="patient_name" name="patient_name" required="required"
                class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
              <label>Father Name</label>
              <input type="text" id="patient_name" name="father_name" required="required"
                class="form-control form-control-sm rounded-0"/>
              </div>
              <br>
              <br>
              <br>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Address</label>
                <input type="text" id="address" name="address" required="required"
                class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Slip Type</label>
                <input type="text" id="cnic" name="cnic" required="required"
                class="form-control form-control-sm rounded-0"/>
              </div>

              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <div class="form-group">
                  <label>Time</label>
                    <div class="input-group date"
                    id="date_of_birth"
                    >
                        <input type="datetime-local" value="<?php echo  $today; ?>" class="form-control form-control-sm rounded-0" name="date_of_birth"
                         />
                        <!-- <div class="input-group-append"
                        data-target="#date_of_birth"
                        data-toggle="datetimepicker">
                        </div> -->
                    </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
                <label>Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" required="required"
                class="form-control form-control-sm rounded-0"/>
              </div>
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-10">
              <label>Slip Price</label>
                <select class="form-control form-control-sm rounded-0" id="gender"
                name="gender">
                  <?php echo getGender();?>
                </select>

              </div>
              </div>

              <div class="clearfix">&nbsp;</div>

              <div class="row">
                <div class="col-lg-11 col-md-10 col-sm-10 xs-hidden">&nbsp;</div>

              <div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">
                <button type="submit" id="save_Patient"
                name="save_Patient" class="btn btn-primary btn-sm btn-flat btn-block">Save</button>
              </div>
            </div>
          </form>
        </div>

      </div>

    </section>

     <br/>
     <br/>
     <br/>

 <section class="content">
      <!-- Default box -->
      <div class="card card-outline card-primary rounded-0 shadow">
        <div class="card-header">
          <h3 class="card-title">Total Patients</h3>

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
                    <th>Action</th>
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
                    <td>
                      <a href="update_patient.php?id=<?php echo $row['id'];?>" class = "btn btn-primary btn-sm btn-flat">
                      <i class="fa fa-edit"></i>
                      </a>
                    </td>

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
  showMenuSelected("#mnu_patients", "#mi_patients");

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


</script>
</body>
</html>
