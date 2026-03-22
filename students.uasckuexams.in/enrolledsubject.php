<?php include "header.php"?>

<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from  enrolledview where stid=" . $_GET['stid'];
    $result = $conn->query($sql);

}
?>

        <!-- Page wrapper  -->

<div class="page-wrapper">
<!-- Bread crumb -->
<!--<div class="row page-titles">-->
<!--<div class="col-md-5 align-self-center">-->
<!--<h3 class="text-primary">Current Exam Enrolled</h3> </div>-->
<!--<div class="col-md-7 align-self-center">-->
<!--<ol class="breadcrumb">-->
<!--<li class="breadcrumb-item"><a href="javascript:void(0)">Student</a></li>-->
<!--<li class="breadcrumb-item active"> Current Exam Enrolled</li>-->
<!--</ol>-->
<!--</div>-->
<!--</div>-->
<!-- End Bread crumb -->
<!-- Container fluid  -->
<div class="container-fluid">
<!-- Start Page Content -->
<div class="row">
<div class="col-12">
<div class="card">
<div class="card-body">
<h6 class="card-subtitle"></h6>
<div class="table-responsive m-t-40">

<table id="example23" class="display nowrxap table table-hover table-striped table-bordered" cellspacing="0" width="100%">


                 <thead>
                          <tr>

                     <th> Exam Name</th>
                     <th>ExamType</th>
                     <th>EnrollDate</th>
                     <!--<th>Print Hallticket</th>-->
                     <th>Action</th>
                     </tr>
                       </thead>
  
<?php
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {

        echo "<tr>";
        
        echo "<td>" . $row["EXAMNAME"] . "</td><td>" . $row["EXAMTYPE"] . "</td><td>" . $row["ENROLLEDDATE"] . "</td>";
        
        // if ($row["FEEPAID"] == 1) {
        //     echo '<td><a href="#?id=' . $row["ID"] . '" class="btn btn-success">Print Hallticket</a></td>';
        // } else {
        //     echo '<td></td>';
        // }
         
        // echo '<td><a href="bankchallan.php?id=' . $row["ID"] . '" class="btn btn-info">Generatechallan</a></td>';
        
       if ($row["FEEPAID"] == 1) {
      echo '<td><a href="getresultview.php?id=' . $row["ID"] . '" class="btn btn-warning">Results View</a></td>';
}else{
     echo '<td></td>';
}

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
<?php include "datatablefooter.php";?>