
<?php include "header.php";?>
<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//check connection
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from enrolledview where id=" . $_GET['id'];
    $result = $conn->query($sql);
    echo "  " . $result->num_rows;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            break;
        }
    }
}
?>

<style>

.table,th,td{
 padding: 2px;   
  background-color: #f1f1c1;

  
}
.button
{
    background-color:#008CBA;
    color:white
}
h3
{
   
}
.img1{

    align:right;
    position:relative;
    top:100px;
     padding-right:4cm;
}

</style>

 <div class="page-wrapper">
            <!-- Bread crumb -->
<div class="row page-titles">
<div class="col-md-5 align-self-center">
<h3 class="text-primary">Student Details</h3>
</div>
<div class="col-md-7 align-self-center">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="javascript:void(0)">View Student</a></li>
<li class="breadcrumb-item active">Student Details</li>
</ol>
</div>
</div>
<!-- End Bread crumb -->
<!-- Container fluid  -->
<div class="container">
<div class="row">
<div class="col-md-12  toppad  pull-right col-md-offset-6 ">
<div class="panel panel-info">
<div class="panel-body">
<div class="row">



<!--button-->
<div class="container">
<!-- The Modal -->
<div class="card">
<div class="card-body">
<div class="row justify-content-center">
<div class=" col-md-9 col-lg-9 ">

<div class="img1" align="right" >
<img src="../students/upload/images/<?php echo $row['aadhar']; ?>.jpg" alt="12345" width="100px"/><br>
<img src="../students/upload/signatures/<?php echo $row['aadhar']; ?>.jpg" alt="12345" width="100px"/>

</div>

<table width="50%">

<tbody>
<h4>1.Candidate details</h4>
<tr>
<td>Full Name:</td>
<td><center><h3><?php echo $row["sname"] ?> </h3></center></td>
</tr>
<tr>
<td>Father Name:</td>
<td><center><h3><?php echo $row["fname"] ?> </h3></center></td>
</tr>
<tr>
<td>Mother Name:</td>
<td><center><h3><?php echo $row["mname"] ?> </h3></center></td>
</tr>

<tr>
<td>Email:</td>
<td><center><h3><?php echo $row["email"] ?>  </h3></center></td>
</tr>

<tr>
<td>Date of Birth:</td>
<td><center> <h3> <?php echo $row["dob"] ?></h3></center></td>
</tr>

<tr>
<td>Gender:</td>
<td><center><h3><?php echo $row["gender"] ?>  </h3></center></td>
</tr>

<tr>
<td>Phone Number:</td>
<td><center><h3><?php echo $row["phone"] ?>  </h3></center></td>
</tr>
</tbody>
</table>
<table width="50%">
<tbody>
<br>

<h4>2.Course details</h4>
<tr>
<td>Group:</td>
<td><center><h3> <?php echo $row["group"] ?> </h3></center></td>
</tr>

<tr>
<td>Halltickt:</td>
<td><center> <h3><?php echo $row["haltckt"] ?> </h3></center></td>
</tr>

<tr>
<td>Semester:</td>
<td><center> <h3> <?php echo $row["sem"] ?> </h3></center></td>
</tr>

<tr>
<td>Year:</td>
<td><center><h3><?php echo $row["curryear"] ?>  </h3></center></td>
</tr>

<tr>
<td>Aadhar:</td>
<td><center><h3><?php echo $row["aadhar"] ?>  </h3></center></td>
</tr>
</tbody>
</table>
<br>
<br>
<table width="50%">
<tbody>
<h4>4.Fee Details:</h4>
<tr>
<td>Fee Amount: <h3> <?php echo $row["FEEAMOUNT"] ?> </h3></td>
<td>Fee Paid: <h3> <?php echo $row["FEEPAID"] ?></h3></td>
</tr>

</tbody>
</table>
<br>
<br>
 <p align="center"> <a href="getresultnyeid.php?id=<?php echo $row["ID"]; ?>" class="btn btn-primary" target="_blank">Get Result</a>
<!--<p align="center"><a class="btn btn-success" href="enrolledview.php" >Back</a></p> -->
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<!--end of page content-->
<?php include "datatablefooter.php";?>