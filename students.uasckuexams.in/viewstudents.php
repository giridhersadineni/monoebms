<?php include "header.php";?>
<?php
// if(isset($_GET['record']))
// {
//     echo '<script>alert("Updated Successfull");</script>';
// }
?>
<?php
//include "config.php";
//check connection
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

// if ($conn->connect_error) {
//     die("connection failed:" . mysqli_connect_error());
// } else {
//     $sql = "select * from students";
//     $result = $conn->query($sql);
// }
?>


<!-- End Left Sidebar  -->

<!-- Page wrapper  -->
<div class="page-wrapper">
<!-- Bread crumb -->

<div class="row page-titles">
 <div class="col-md-5 align-self-center">
 <!--<h3 class="text-primary">Dashboard</h3> -->
 </div>

 <div class="col-md-7 align-self-center">
 <ol class="breadcrumb">
 <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
 <li class="breadcrumb-item active">Student Details</li>
 </ol>
 </div>
 </div>
 <!-- End Bread crumb -->

<!-- Container fluid  -->
<div class="container-fluid">
<!-- Start Page Content -->
<div class="row">
<div class="col-6 mx-auto">
<div class="card  bg-danger">
<div class="card-body">
<form action="students.php" method="POST">    
 <div class="form-row align-items-center">
    <div class="col-sm-9 my-1">
    <input type="text" class="form-control" id="hallticket"  name="hallticket" placeholder="Enter Hallticket Number">
    </div>
  <div class="col-auto my-1">
      <button type="submit" class="btn btn-primary">Search </button>
    </div>    
</div>
<form>
</div>
</div>

</div>
</div>
<!-- footer -->
<?php include "datatablefooter.php";?>