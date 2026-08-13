<?php include 'header.php'?>
<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
    $sql = "select * from examsmaster";
    $result = $conn->query($sql);

    $course="select DISTINCT COURSE from courses ";
    $getcourse=$conn->query($course);
//   $gcourse= mysqli_fetch_assoc($getcourse);
//   echo $gcourse;
}
?>
<?php 
if(isset($_POST['formsubmit'])){
    
    $SEMESTER=$_POST["semester"];
    $EXAMNAME=$_POST["examname"];
    $MONTH=$_POST["month"];
    $YEAR=$_POST["year"];
    $EXAMTYPE=$_POST["examtype"];
    $STATUS=$_POST["status"];
    $COURSE=$_POST["course"];
    $FEE=$_POST["fee"];
    $ABOVE2SUBS=$_POST["secondfee"];
    $IMPROVEMENT=$_POST["improvement"];
    
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    
    if (!$conn){
        die("connection failed:".mysqli_connect_error());
    }
    $sql = "INSERT INTO `examsmaster` ( `SEMESTER`, `EXAMNAME`, `MONTH`, `YEAR`,`EXAMTYPE`,`STATUS`,`COURSE`,`FEE`,`ABOVE2SUBS`,`IMPROVEMENT`) 
    VALUES ('".$SEMESTER."','".$EXAMNAME."','".$MONTH."','".$YEAR."','".$EXAMTYPE."','".$STATUS."','".$COURSE."','".$FEE."','".$ABOVE2SUBS."',
    '".$IMPROVEMENT."')";
    
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        echo "<script>alert('Added Exam Successfully');</script>";
    } 
    else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
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
<h3 class="text-primary">Add Exam </h3> </div>
<div class="col-md-7 align-self-center">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="javascript:void(0)">Edit master</a></li>
<li class="breadcrumb-item active">Add Exam</li>
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
<h4 class="m-b-0 text-white">ADD EXAM </h4>
</div>
</center>
<div class="card-body">
<div class="basic-form">

<form action="addexams.php" method="POST">

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Semester</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter  Semester" name="semester" required>
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Exam Name</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter Exam Name" name="examname" required>
</div></div></div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Month</h4></p>
<select name="month" class="form-control input-focus" required>
<option name="month" value="choose">--select--</option>
<option name="month" value="JANUARY">JANUARY</option>
<option name="month" value="FEBRUARY">FEBRUARY</option>
<option name="month" value="MARCH">MARCH</option>
<option name="month" value="APRIL">APRIL</option>
<option name="month" value="MAY">MAY</option>
<option name="month" value="JUNE">JUNE</option>
<option name="month" value="JULY">JULY</option>
<option name="month" value="AUGUST">AUGUST</option>
<option name="month" value="SEPTMBER">SEPTEMBER</option>
<option name="month" value="OCTOBER">OCTOBER</option>
<option name="month" value="NOVEMBER">NOVEMBER</option>
<option name="month" value="DECEMBER">DECEMBER</option>
</select>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Year</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter year" name="year" required></div></div>
</div>
</div>
</div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>EXAMTYPE</h4></p>
<select name="examtype"  id="status"  class="form-control input-focus" required>

        <option name="examtype"  value="NULL">--Select--</option>
        <option name="examtype"  value="REGULAR">REGULAR</option>
        <option name="examtype" value="SUPPLY">SUPPLEMENTERY</option>
</select>
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>SET STATUS</h4></p>
<select name="status"  id="examtype"  class="form-control input-focus" required>

        <option name="status"  value="NULL">--Select--</option>
        <option name="status"  value="OPEN">OPEN</option>
        <option name="status"  value="OPEN">NOTIFY</option>
        <option name="status"  value="RUNNING">RUNNING</option>
        <option name="status" value="CLOSED">CLOSE</option>
</select>
</div></div>
</div>      

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>COURSE</h4></p>
<select name="course"  id="course"  class="form-control input-focus" required>
     <option name="course"  value="0">--Select--</option>
<?php 
if ($getcourse->num_rows > 0) {
    // output data of each row
while($row = mysqli_fetch_assoc($getcourse)) 
    {
    echo '  <option name="course"  value="'.$row['COURSE'].'">'.$row['COURSE'].'</option>';
}
}
?>
</select>
</div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>FEE AMOUNT</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter  Amount" name="fee" required>
</div></div>
</div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>ABOVE TWO SUBJECT FEE</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter Amount" name="secondfee" required>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>IMPROVEMENT FEE</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter  Amount" name="improvement" required>
</div></div>
</div>
<p align="right"> <input type="submit" class="btn btn-success" value="Add Exam" name="formsubmit"></p>
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
