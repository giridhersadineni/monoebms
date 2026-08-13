<?php include "header.php";?>

<?php
include "config.php";
//check connection
$conn = mysqli_connect($servername, $dbuser, $dbpwd, "atech_ebms");

if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select distinct(HALLTICKET),EXAMID,EXAMNAME from RESULTS_STAGE LEFT JOIN examsmaster on RESULTS_STAGE.EXAMID=examsmaster.EXID order by HALLTICKET";
    $result = $conn->query($sql);
}
?>


<!-- End Left Sidebar  -->

<!-- Page wrapper  -->
<div class="page-wrapper">
<!-- Bread crumb -->

<div class="row page-titles">
 <div class="col-md-5 align-self-center">
 <!--<h3 class="text-primary">Dashboard</h3> -->
 </div>

 <div class="col-md-7 align-self-center">
 <ol class="breadcrumb">
 <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
 <li class="breadcrumb-item active">Enrolled Details</li>
 </ol>
 </div>
 </div>
 <!-- End Bread crumb -->

<!-- Container fluid  -->
<div class="container-fluid">
<!-- Start Page Content -->
<div class="row wrapper">
<div class="col-12">
<div class="card">
<div class="card-body">
<div class="table-responsive m-t-40">
<table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">

<thead>
        <tr>
            <th>ACTION</th>
            <th>HALLTICKET</th>
            <th>EXAMID</th>
            <th>EXAM NAME</th>
            <th>ACTION</th>
            
        </tr>
</thead>

<?php

while($row=mysqli_fetch_assoc($result)){
    echo '<tr>';
        echo '<td><a href="offlinememo.php?exid='.$row['EXAMID'].'&ht='.$row["HALLTICKET"].'" class="btn btn-success" target="_BLANK"><i class="fas fa-print"></i> Print</td>';
        echo '<td>'.$row['HALLTICKET'].'</td>';
        echo '<td>'.$row['EXAMID'].'</td>';
        echo '<td>'.$row['EXAMNAME'].'</td>';
        echo '<td><a href="studentgpaupdate.php?exid='.$row['EXAMID'].'&ht='.$row["HALLTICKET"].'" class="btn btn-success" target="_BLANK"><i class="fas fa-pen"></i> GPAUPDATE</td>';
    echo '</tr>';
}

?>
<?php
mysqli_close($conn);
?>

</table>
</div>
</div>
</div>
</div>
</div>
<!-- footer -->
<?php include "datatablefooter.php";?>