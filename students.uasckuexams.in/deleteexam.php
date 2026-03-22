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

$sql = "select * from examsmaster where semester=".$_GET['semester'];
$result = $conn->query($sql);

if($result->num_rows>0){

while($row=$result->fetch_assoc()) {
break;
}
}
}
?>
        <!-- Page wrapper  -->

<div class="page-wrapper">
            <!-- Bread crumb -->
<div class="row page-titles">
<div class="col-md-5 align-self-center">
<h3 class="text-primary">Paper Details</h3> 
</div>
<div class="col-md-7 align-self-center">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="javascript:void(0)">View</a></li>
<li class="breadcrumb-item active"> Details</li>
</ol>
</div>
</div>
<!-- End Bread crumb -->
<!-- Container fluid  -->
<div class="container">
<div class="row justify-content-center">
<div class=" col-md-9 col-lg-9 "> 
<table class="table table-user-information">
<tbody>
<tr>
<td>Exam Id   :</td>
<td><center><?php echo $row["EXAMID"]?></center></td>
</tr>
<tr>
<td>Semester   :</td>
<td><center><?php echo $row["SEMESTER"]?></center></td>
</tr>
<tr>
<td>Course   :</td>
<td><center><?php echo $row["COURSE"]?></center></td>
</tr>
<tr>
<td>Exam Name :</td>
<td><center><?php echo $row["EXAMNAME"]?></center></td>
</tr>
<tr>
<td>Month  :</td>
<td><center><?php echo $row["MONTH"]?></center></td>
</tr>
<tr>
<td>Year  :</td>
<td><center><?php echo $row["YEAR"]?></center></td>
</tr>
 
</tbody>
</table>
<br>
<p align="right">
 
<a onClick="return confirm('Are you sure ?');" href="deleteexam.php" class="btn btn-danger" value="Delete "><font color="white">Delete</a>

</div>
</div>
</div>
</div>

<?php

$id=$_GET['semester'];

$result =$conn->query("delete from students where semester='".$_GET["semester"]."'");

if($result) {
    ?>
    <script>
    alert ("Data deleted ");
   // window.location.href='examtable.php';
    </script>
}else{
    <script>
    alert ("fail to delete data");
   // window.location.href='deleteexam.php';
    </script>
}
 <?php
}
 ?>
<?php include "datatablefooter.php"; ?>