<?php include '../header.php'?>
<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
    $sql = "select * from examsmaster where EXID=".$_GET['EXID'];
    $result = $conn->query($sql);
    $row=mysqli_fetch_array($result);
}
?>



<style>
.button
{
    background-color:#008CBA;
    color:white
}
h3
{
    color:blue;
}
</style>
 <div class="page-wrapper">
<!-- Bread crumb -->
<div class="row page-titles">
<div class="col-md-5 align-self-center">
<h3 class="text-primary">Edit Exam </h3> </div>
<div class="col-md-7 align-self-center">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="javascript:void(0)">Edit master</a></li>
<li class="breadcrumb-item active">Edit Exam</li>
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
<h4 class="m-b-0 text-white">EDIT EXAM </h4>
</div>
</center>
<div class="card-body">
<div class="basic-form">

<form action="updateexams.php?id=<?php echo $_GET["EXID"] ?>" method="POST">

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Semester</h4></p>
<input type="text" class="form-control input-focus " value="<?php echo $row['SEMESTER']; ?>" name="semester" >
</div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Exam name</h4></p>
<input type="text" class="form-control input-focus" value="<?php echo $row['EXAMNAME']; ?>" name="examname" >
</div>
</div>
</div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Month</h4></p>
<input type="text" class="form-control input-focus " value="<?php echo $row['MONTH']; ?>" name="month" >
</div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Year</h4></p>
<input type="text" class="form-control input-focus" value="<?php echo $row['YEAR']; ?>" name="year" >
</div>
</div>
</div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>EXAMTYPE</h4></p>
<select name="examtype"  id="examtype"  class="form-control input-focus" required>
     <option name="examtype"  value="<?php echo $row['EXAMTYPE'];?>"><?php echo $row['EXAMTYPE'];?></option>
        <option name="examtype"  value="REGULAR">Regular </option>
        <option name="examtype" value="SUPPLY">Supply</option>
</select>
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>STATUS</h4></p>
<select name="status"  id="status"  class="form-control input-focus" required>
    <option name="status"  value="<?php echo $row['STATUS'];?>"><?php echo $row['STATUS'];?></option>
        <option name="status"  value="OPEN">OPEN </option>
        <option name="status" value="CLOSED">CLOSE</option>
         <option name="status"  value="RUNNING">RUNNING</option>
        <option name="status" value="NOTIFY">NOTIFY</option>
</select>
</div></div>
</div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>COURSE</h4></p>
<input type="text" class="form-control input-focus" value="<?php echo $row['COURSE']; ?>" name="course" >
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>REGULAR FEE</h4></p>
<input type="text" class="form-control input-focus" value="<?php echo $row['FEE']; ?>" name="fee" >
</div>
</div>
</div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>FINE AMOUNT</h4></p>
<input type="text" class="form-control input-focus" value="<?php echo $row['FINE']; ?>" name="fine" >
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>ABOVE TWO SUBJECT</h4></p>
<input type="text" class="form-control input-focus" value="<?php echo $row['ABOVE2SUBS']; ?>" name="secondfee" >
</div>
</div>
</div>
<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4> IMPROVEMENT</h4></p>
<input type="text" class="form-control input-focus" value="<?php echo $row['IMPROVEMENT']; ?>" name="improvement" >
</div>
</div>
</div>

   <br>                 
<p align="center"> <input type="submit" class="btn btn-success" value="UPDATE" name="formsubmit"></p>
</div>
</div>
</div>
</div>
<!-- /# card -->
</div>
<!-- /# column -->
</div>
<!--end of page content-->

<?php include "../datatablefooter.php";?>
