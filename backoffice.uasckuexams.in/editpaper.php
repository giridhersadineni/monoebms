<?php include 'header.php'?>

<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

//check connection
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{

$sql = "select * from allpapers where ID=".$_GET['id'];
$result = $conn->query($sql);

if($result->num_rows>0){

while($row=$result->fetch_assoc()) {
break;
}
}
}
?>
<head>
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
<h3 class="text-primary">Edit Paper </h3> </div>
<div class="col-md-7 align-self-center">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
<li class="breadcrumb-item active">Edit paper</li>
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
<h4 class="m-b-0 text-white">EDIT PAPER </h4>
</div>
</center>
<div class="card-body">
<div class="basic-form">

<form action="updatepaper.php?id=<?php echo $_GET["id"] ?>" method="POST">

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Course</h4></p>
<input type="text" class="form-control input-focus " value="<?php echo $row['COURSE']?>" name="course"></div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Paper Code</h4></p>
<input type="text" class="form-control input-focus " value="<?php echo $row['PAPERCODE']?>" name="papercode">
</div></div></div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Specialization</h4></p>
<input type="text" class="form-control input-focus " value="<?php echo $row['SPECIALIZATION']?>" name="specialization"></div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Paper Name</h4></p>
<input type="text" class="form-control input-focus " value="<?php echo $row['PAPERNAME']?>" name="papername">
</div></div></div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Group Code</h4></p>
<input type="text" class="form-control input-focus " value="<?php echo $row['GROUPCODE']?>" name="code"></div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Semester</h4></p>
<input type="text" class="form-control input-focus" value="<?php echo $row['SEM']?>" name="semester">
</div> </div>
</div>
<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Year</h4></p>
<input type="text" class="form-control input-focus " value="<?php echo $row['YEAR']?>" name="year"></div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Paper Type</h4></p>
<input type="text" class="form-control input-focus" value="<?php echo $row['PAPERTYPE']?>" name="papertype">
</div> </div>
</div>
<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Medium</h4></p>
<input type="text" class="form-control input-focus " value="<?php echo $row['MEDIUM']?>" name="medium"></div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Description</h4></p>
<input type="text" class="form-control input-focus" value="<?php echo $row['DESCRIPTION']?>" name="description">
</div> </div>
</div>
<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Elective Group</h4></p>
<input type="text" class="form-control input-focus " value="<?php echo $row['EGROUP']?>" name="egroup">
</div></div></div>

<!-- Buttons-->
<p align="Center"> <input type="submit" class="btn btn-success" value="Update Information"></p>

<p align="Center"> <a href="papers.php" class="btn btn-primary" value="Back">Back</a></p>
</div>
</div>
</div>
</div>
<!-- /# card -->
</div>
<!-- /# column -->
</div>
<!--end of page content-->

<?php include "datatablefooter.php";?>
