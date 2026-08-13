<?php 
/*session_start();
if(!(isset($_SESSION['login'])))
{
    header("location:index.php?sessionexpired");
}*/
?>

<?php include "header.php";?>

<?php
/*
include "config.php";
//check connection
$conn=mysqli_connect($servername,$dbuser,$dbpwd,$dbname);
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
    $sql = "select * from students";
    $result = $conn->query($sql);
}*/
?>

<!-- End Left Sidebar  -->

<!-- Page wrapper  -->
<div class="page-wrapper">
<!-- Bread crumb -->
<div class="row page-titles">
<div class="col-md-5 align-self-center">
<!--<h3 class="text-primary">Dashboar</h3>--> </div>
<div class="col-md-7 align-self-center">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
<li class="breadcrumb-item active">Payment Details</li>
</ol>
</div>
</div>
<!-- End Bread crumb -->

<!-- Container fluid  -->
<div class="container-fluid">
<!-- Start Page Content -->
<div class="row">
<div class="col-12">
<div class="card">
<div class="card-body">
<h4 class="card-title">Transcation Table</h4>
<div class="table-responsive m-t-40">
<table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
<thead>
<tr>
<th>Student ID</th>
<th>Student name</th>
<th>Hallticket</th>
<th>Semester</th>
<th>Payment Details</th>
<th>Date of Payment</th>
<th>Time</th>
<th>Actions</th>
</tr>
</thead>

<?php

if ($result->num_rows > 0) {
    // output data of each row
while($row = mysqli_fetch_assoc($result)) {

    echo "<tr>";

echo "<td><a href='#.php?stid=". $row["stid"]."'>".$row["stid"]."</a></td><td>" . $row["sname"]. "</td><td>" . $row["fname"]. "</td><td>" .$row["phone"]. "</td><td>" . $row["group"]. "</td><td>" . $row["haltckt"]. "</td><td>"  . $row["email"]."</td>";

echo '<td><a href="students.php?stid='.$row["stid"].'" class="btn btn-success">View</a></td></tr>';

}
} else {
echo '<tr><td colspan="8">No Branches - Empty Table</td></tr>';
}
?>

<?php 
mysqli_close($conn);
?> 

</table>
<!-- End PAge Content -->
</div>
<!-- End Container fluid  -->
<!-- footer -->
<?php include "footer.php";?>