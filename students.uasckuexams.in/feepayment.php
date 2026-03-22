<?php include 'header.php';?>

<?php

if (isset($_POST['Submit'])) {
  if (empty($_POST['studentid']))
 {
$error = "please fill the blank";
}
else {
  
  $studentid=$_POST['studentid'];
  $error=''; 

include 'config.php';
//check connection
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

$result = $conn->query("select * from enrolledview where  id='$studentid'");
$rows = $result->num_rows;

if ($rows == 1) 
{
   echo "student is valid";
} 
else 
{
$error = "Student Id is invalid";
}
mysqli_close($conn); 
}
}

?>

<div class="page-wrapper">
<!-- Bread crumb -->
<div class="row page-titles">
<div class="col-md-5 align-self-center">
<h3 class="text-primary">Mark Fee Payment </h3> </div>
<div class="col-md-7 align-self-center">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
<li class="breadcrumb-item active">mark fee Payment</li>
</ol>
</div>
</div>
<!-- End Bread crumb -->

<!-- Container fluid  -->
<div class="container-fluid">
<!-- Start Page Content -->
<div class="row">
<div class="col-lg-12">
<div class="card card-outline-primary">        
<center>
<div class="card-header">
<h4 class="m-b-0 text-white">MARK FEE PAYMENT </h4>
</div>
</center>
<br><br>
<div class="card-body">
<div class="basic-form">

<form action="feepayment.php" method="POST">

<div class="col-md-6">
<div class="input-group mb-3">
<input type="text" class="form-control" name="studentid" value="" placeholder="Enter Registration Number">
  <div class="input-group-append">
    <input class="btn btn-warning" type="submit" value="Get Details" name="Submit" id="Submit">
  </div>
  </div>
</div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Student Name</h4></p>
<input type="text" class="form-control" placeholder="" disabled></div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Course</h4></p>
<input type="text" class="form-control  " placeholder=""  name="course" disabled>
</div></div></div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Fee Amount</h4></p>
<input type="text" class="form-control" placeholder=""  name="amount" disabled></div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Enter Challan Number</h4></p>
<input type="text" class="form-control  " placeholder="" name="challannumber">
</div></div></div>
<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Enter Challan Date</h4></p>
<input type="text" class="form-control" placeholder="" name="challandate">
</div></div>
</div>
<br>
<p align="center"><input type="submit" class="btn btn-primary" value="Mark Payment"></p>

  </form>
  </div>
</div>
</div>
</div>
</div>
</div>
<?php include 'datatablefooter.php'?>







































