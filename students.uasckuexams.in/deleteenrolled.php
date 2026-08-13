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

$sql = "select * from examenrollments where ID=".$_GET['id'];
$result = $conn->query($sql);

if($result->num_rows>0){

while($row=$result->fetch_assoc()) {
break;
}
}
}
?>

 <div class="page-wrapper">
            <!-- Bread crumb -->
<div class="row page-titles">
<div class="col-md-5 align-self-center">
<h3 class="text-primary">Enrolled Details</h3> 
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
<td>Student Id   :</td>
<td><center><?php echo $row["STUDENTID"]?></center></td>
</tr>
<tr>
<td>Subject:</td>
<td><center><?php echo $row["S1"]?></center></td>
</tr>
<tr>
<td>Subject 2  :</td>
<td><center><?php echo $row["S2"]?></center></td>
</tr>
<tr>
<td>Subject 3  :</td>
<td><center><?php echo $row["S3"]?></center></td>
</tr>
<tr>
<td>Subject 4 :</td>
<td><center><?php echo $row["S4"]?></center></td>
</tr>
<tr>
<td>Subject 5   :</td>
<td><center> <?php echo $row["S5"]?></center></td>
</tr>
<tr>
<td>Subject 6   :</td>
<td><center> <?php echo $row["S6"]?></center></td>
</tr>
<tr>
<td>Subject 7   :</td>
<td><center> <?php echo $row["S7"]?></center></td>
</tr>
<tr>
<td>Subject 8   :</td>
<td><center> <?php echo $row["S8"]?></center></td>
</tr>
<tr>
<td>Subject 9   :</td>
<td><center> <?php echo $row["S9"]?></center></td>
</tr>
<tr>
<td>Subject 10   :</td>
<td><center> <?php echo $row["S10"]?></center></td>
</tr> 
<tr>
<td>Elective 1   :</td>
<td><center> <?php echo $row["E1"]?></center></td>
</tr> 
<tr>
<td>Elective 2   :</td>
<td><center> <?php echo $row["E2"]?></center></td>
</tr> 
<tr>
<td>Elective 3   :</td>
<td><center> <?php echo $row["E3"]?></center></td>
</tr> 
<tr>
<td>Elective 4   :</td>
<td><center> <?php echo $row["E4"]?></center></td>
</tr> 
<tr>
<td></td>
<td></td>
</tr> 
</tbody>

</table>
<br>
</div>
</div>
</div>
</div>


<?php

$id=$_GET['id'];

$result =$conn->query("delete from examenrollments where ID='".$_GET["id"]."'");

if($result) {
    ?>
    <script>
    alert ("Data deleted ");
    window.location.href='enrolledview.php';
    </script>
}else{
    <script>
    alert ("fail to delete data");
    window.location.href='deleteenrolled.php';
    </script>
}
 <?php
}
 ?>



<?php include 'datatablefooter.php'?>