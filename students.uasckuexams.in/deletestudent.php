<?php include 'header.php'?>
<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

//check connection
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {

    $sql = "select * from students where id=" . $_GET['stid'];
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {
            break;
        }
    }
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
.img1{

    align:right;
    position:relative;
    top:100px;
}

</style>
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

<!--button-->
<div class="container">
<!-- The Modal -->
<div class="row justify-content-center">
<div class=" col-md-9 col-lg-9 ">
<br>
<div class="img1" align="right" >
<img src="../students/upload/images/<?php echo $row['aadhar']; ?>.jpg" alt="12345" width="100px"/><br>
<img src="../students/upload/signatures/<?php echo $row['aadhar']; ?>.jpg" alt="12345" width="100px"/>

</div>

<table width="50%">

<tbody>
<h4>1.Candidate details</h4>
<tr>
<td>Full Name:</td>
<td><center><?php echo $row["sname"] ?></center></td>
</tr>
<tr>
<td>Father Name:</td>
<td><center><?php echo $row["fname"] ?></center></td>
</tr>
<tr>
<td>Mother Name:</td>
<td><center><?php echo $row["mname"] ?></center></td>
</tr>

<tr>
<td>Email:</td>
<td><center><?php echo $row["email"] ?></center></td>
</tr>

<tr>
<td>Date of Birth:</td>
<td><center><?php echo $row["dob"] ?></center></td>
</tr>

<tr>
<td>Gender:</td>
<td><center><?php echo $row["gender"] ?></center></td>
</tr>

<tr>
<td>Phone Number:</td>
<td><center><?php echo $row["phone"] ?></center></td>
</tr>
</tbody>
</table>
<table width="50%">
<tbody>
<br>

<h4>2.Course details</h4>
<tr>
<td>Group:</td>
<td><center><?php echo $row["group"] ?></center></td>
</tr>

<tr>
<td>Halltickt:</td>
<td><center><?php echo $row["haltckt"] ?></center></td>
</tr>

<tr>
<td>Semester:</td>
<td><center><?php echo $row["sem"] ?></center></td>
</tr>

<tr>
<td>Year:</td>
<td><center><?php echo $row["curryear"] ?></center></td>
</tr>

<tr>
<td>Aadhar:</td>
<td><center><?php echo $row["aadhar"] ?></center></td>
</tr>
</tbody>
</table>
<br>
<table width="50%">
<tbody>
<h4>3.Address of Candidate</h4>
<tr>
<td>Address:</td>
<td><center><?php echo $row["address"] ?></center></td>
</tr>
<tr>
<td>Manadal:</td>
<td><center><?php echo $row["mandal"] ?></center></td>
</tr>
<tr>
<td>City:</td>
<td><center><?php echo $row["city"] ?></center></td>
</tr>
<tr>
<td>State:</td>
<td><center><?php echo $row["state"] ?></center></td>
</tr>
<tr>
<td>Pincode :</td>
<td><center><?php echo $row["pincode"] ?></center></td>
</tr>


</tbody>
</table>

<br>
<p align="center"> <input type="submit" class="btn btn-warning" value="Delete ">
</div>
</div>
</div>
</div>
</div>
</div>
<!--end of page content-->
<?php

$id = $_GET['id'];

$result = $conn->query("delete from students where  id='" . $_GET["stid"] . "'");

if ($result) {
    ?>
    <script>
    alert ("Data deleted ");
    window.location.href='viewstudents.php';
    </script>
}else{
    <script>
    alert ("fail to delete data");
    window.location.href='deletestudent.php';
    </script>
}
 <?php
}
?>
<?php include "datatablefooter.php";?>