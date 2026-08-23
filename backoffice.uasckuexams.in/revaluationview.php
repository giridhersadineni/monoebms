<?php include "header.php";?>

<?php
include "config.php";
//check connection
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from revaluationenrollments";
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
<div class="row">
<div class="col-12">
<div class="card">
<div class="card-body">
<div class="table-responsive m-t-40">
<table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">

            <thead>
                                   <tr>
                              <th>Student Id</th>
                              <th>Student name</th>
                              <th>Hallticket</th>                         
                              <th>Enrolled Id</th>
                              <th>View</th>    
                              <!-- <th>View</th> -->

                              </tr>
                 </thead>
            


<?php
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" .$row["stid"] . "</td><td>" . $row["sname"] . "</td><td>" . $row["haltckt"] . "</td><td>" . $row["ID"] . "</td>";

        echo '<td><a href="enrolledsubject.php?stid=' . $row["stid"] . '" class="btn btn-info">Enrolled View</a></td></tr>';
}
} else {
    echo '<tr><td colspan="5">No Branches - Empty Table</td></tr>';
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