<?php
include './config/connection.php';

  $date = date('Y-m-d');

  $year =  date('Y');
  $month =  date('m');

  $queryToday = "SELECT count(*) as today FROM `patients` where date(date_of_birth) = curdate()";

  $queryWeek = "SELECT count(*) as week FROM `patients` where date(date_of_birth) = curdate() AND gender = 500";

$queryYear = "SELECT count(*) as year FROM `patients` where date(date_of_birth) = curdate() AND gender = 1500";

$queryMonth = "SELECT count(*) as month FROM `patients` where date(date_of_birth) = curdate() AND gender = 1000";

  $todaysCount = 0;
  $currentWeekCount = 0;
  $currentMonthCount = 0;
  $currentYearCount = 0;


  try {

    $stmtToday = $con->prepare($queryToday);
    $stmtToday->execute();
    $r = $stmtToday->fetch(PDO::FETCH_ASSOC);
    $todaysCount = $r['today'];

    $stmtWeek = $con->prepare($queryWeek);
    $stmtWeek->execute();
    $r = $stmtWeek->fetch(PDO::FETCH_ASSOC);
    $currentWeekCount = $r['week'];

    $stmtYear = $con->prepare($queryYear);
    $stmtYear->execute();
    $r = $stmtYear->fetch(PDO::FETCH_ASSOC);
    $currentYearCount = $r['year'];

    $stmtMonth = $con->prepare($queryMonth);
    $stmtMonth->execute();
    $r = $stmtMonth->fetch(PDO::FETCH_ASSOC);
    $currentMonthCount = $r['month'];

  } catch(PDOException $ex) {
     echo $ex->getMessage();
   echo $ex->getTraceAsString();
   exit;
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php include './config/site_css_links.php';?>
 <title>Dashboard - Hope Clinic in PHP</title>
<style>
  .dark-mode .bg-fuchsia, .dark-mode .bg-maroon {
    color: #fff!important;
}
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->

  <?php include './config/data_tables_css.php';?>
<?php

include './config/header.php';
include './config/sidebar.php';
?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Dashboard</h1>
            <!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade DivIdToPrint" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content modal-lg">
      <div class="modal-header" style="display: flex; justify-content:space-between">
        <div>
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        </div>
        <div>
        <a class="btn btn-primary text-white" id="print" onclick="printData()"></a>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>  
      </div>
        
      </div>
      <div class="modal-body" style="max-height: 90vh; overflow-y: auto;">
      <div class="card-body" >
            <div class="row table-responsive">
              <table id="all_patients"
              class="table table-striped dataTable table-bordered dtr-inline" border="1" cellpadding="3"
               role="grid" aria-describedby="all_patients_info">

                <thead>
                  <tr>
                    <th>Slip Number</th>
                    <th>Patient Name</th>
                    <th>Father Name</th>
                    <th>Slip Price</th>
                    <th>Address</th>
                  </tr>
                </thead>

                <tbody id="slip-detail">
        
                </tbody>
              </table>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">

          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info" style="display: flex; justify-content:space-between">
              <div class="inner">
                <h3><?php echo $todaysCount;?></h3>

                <p style="font-size: 0.9rem;">Today's Slips</p>
              </div>
              <div class="inner">
                <h3><?php echo  (500 * $currentWeekCount) + (1000 * $currentMonthCount) + (1500 * $currentYearCount);?></h3>

                <p>Total Amount</p>
                <button type="button" class="btn btn-primary" onclick="slipDetail(0)">
                  Show Details
                </button>
              </div>

            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-purple" style="display: flex; justify-content:space-between">
              <div class="inner">
                <h3><?php echo $currentWeekCount;?></h3>

                <p>500 Slips</p>
              </div>
              <div class="inner">
                <h3><?php echo 500 * $currentWeekCount;?></h3>

                <p>Amount</p>
                <button type="button" class="btn btn-primary" onclick="slipDetail(500)">
                  Show Details
                </button>
              </div>

            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-fuchsia text-reset" style="display: flex; justify-content:space-between">
              <div class="inner">
                <h3 class="text-white"><?php echo $currentMonthCount;?></h3>

                <p class="text-white">1000 Slips</p>
              </div>
              <div class="inner">
                <h3 class="text-white"><?php echo 1000 * $currentMonthCount;?></h3>

                <p class="text-white">Amount</p>
                <button type="button" class="btn btn-primary" onclick="slipDetail(1000)">
                  Show Details
                </button>
              </div>

            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-maroon text-reset" style="display: flex; justify-content:space-between">
              <div class="inner">
                <h3 class="text-white"><?php echo $currentYearCount;?></h3>

                <p class="text-white">1500 Slips</p>
              </div>
              <div class="inner">
                <h3 class="text-white"><?php echo 1500 * $currentYearCount;?></h3>

                <p class="text-white">Amount</p>
                <button type="button" class="btn btn-primary" onclick="slipDetail(1500)">
                  Show Details
                </button>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- /.content -->
  </div>
  
  <!-- /.content-wrapper -->
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<?php include './config/footer.php';?>


  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<?php include './config/site_js_links.php';?>
<?php include './config/data_tables_js.php'; ?>
<script>
  $(function(){
    showMenuSelected("#mnu_dashboard", "");
  })
  // $(function () {
  //   $("#all_patients").DataTable({
  //     "responsive": true, "lengthChange": false, "autoWidth": false,
  //     "buttons": ["excel", "pdf", "print",]
  //   }).buttons().container().appendTo('#all_patients_wrapper .col-md-6:eq(0)');

  // });
  function slipDetail(type)
  {
    if(type == 0)
    {
      $("#exampleModalLabel").text('Total Slips');
      $("#print").text('Print');
      
      
      $('#print').css("display","")
    }
    if(type == 500)
    {
      $("#exampleModalLabel").text('500 Slips');
      $("#print").text('');
      $('#print').css("display","none")
    }
    if(type == 1000)
    {
      $("#exampleModalLabel").text('1000 Slips');
      $("#print").text('');
      $('#print').css("display","none")
    }
    if(type == 1500)
    {
      $("#exampleModalLabel").text('1500 Slips');
      $("#print").text('');
      
      $('#print').css("display","none")
    }

    $.ajax({
      url: 'slip-detail.php',
      method: 'post',
      data: {type:type},
      success: function(response)
      {
        $("#slip-detail").html(response);
        $("#exampleModal").modal('show');
      }
    })
  }
  function printData()
{
   var divToPrint=document.getElementById("all_patients");
   newWin= window.open("");
   newWin.document.write(divToPrint.outerHTML);
   newWin.print();
   newWin.close();
}

// $('button').on('click',function(){
// printData();
// })
</script>




</body>
</html>
