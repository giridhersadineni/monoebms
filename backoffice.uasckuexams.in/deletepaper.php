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

$sql = "select * from allpapers where papercode=".$_GET['papercode'];
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
<td>Course Name   :</td>
<td><center><?php echo $row["COURSE"]?></center></td>
</tr>
<tr>
<td>Paper Code   :</td>
<td><center><?php echo $row["PAPERCODE"]?></center></td>
</tr>
<tr>
<td>Subject   :</td>
<td><center><?php echo $row["SUBJECT"]?></center></td>
</tr>
<tr>
<td>papertitle  :</td>
<td><center><?php echo $row["PAPERTITLE"]?></center></td>
</tr>
<tr>
<td>Subject Code  :</td>
<td><center><?php echo $row["SUBJECTCODE"]?></center></td>
</tr>
<tr>
<td>Semester  :</td>
<td><center><?php echo $row["SEMESTER"]?></center></td>
</tr>
<tr>
<td>Intl marks   :</td>
<td><center> <?php echo $row["INTLMARKS"]?></center></td>
</tr>
<tr>
<td>Ext marks   :</td>
<td><center> EXTMARKS</center></td>
</tr>                    
<tr>
<td></td>
<td></td>
</tr> 
</tbody>
</table>
<br>
<p align="right">
 
 <a onClick="return confirm('Are you sure ?');" href="delete.php" class="btn btn-danger" value="Delete "><font color="white">Delete</a>
 

 

</div>
</div>
</div>
</div>
<?php

$id=$_GET['papercode'];

$result =$conn->query("delete from allpapers where papercode='".$_GET["papercode"]."'");

if($result) {
    ?>
    <script>
    alert ("Data deleted ");
    window.location.href='papers.php';
    </script>
}else{
    <script>
    alert ("fail to delete data");
    window.location.href='delete.php';
    </script>
}
 <?php
}
 ?>



<?php include 'datatablefooter.php'?>