<?php include "header.php";?>

<?php
include "config.php";
//check connection
// $result=0;
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $nql= "SELECT * FROM `resultholder` where EID=".$_GET['id'];
    echo $_GET['id'];
   //echo "result fetching";
    $show= $conn->query($nql);
    //  print_r($show);

}
?>

<!-- Page wrapper  -->
<div class="page-wrapper">
<!-- Bread crumb -->

<!-- <div class="row page-titles">
 <div class="col-md-5 align-self-center">
 <h3 class="text-primary">Dashboard</h3>
 </div>

 <div class="col-md-7 align-self-center">
 <ol class="breadcrumb">
 <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
 <li class="breadcrumb-item active">Student Results</li>
 </ol>
 </div>
 </div> -->
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
                              <th>Paper Code</th>
                              <th>Paper Name</th>
                              <th>Hall-Ticket</th>
                              <th>Ext</th>
                              <th>Etotal</th>
                              <th>Int</th>
                              <th>Itotal</th>
                              <th>Result</th>
                              <th>Marks</th>
                              <th>Total Marks</th>
                              <th>Grade</th>

                      </tr>
                 </thead>
            


<?php
if ($show->num_rows > 0) {
   
    // output data of each row
    while ($row = mysqli_fetch_assoc($show)) 
    {
        echo "<tr>";
        echo "<td>" . $row['PAPERCODE'] . "</td><td>" . $row['PAPERNAME'] . "</td><td>" . $row['HALLTICKET'] . "</td><td>" . $row['EXT'] . "</td><td>" . $row['ETOTAL'] . "</td><td>" . $row['INT'] . "</td><td>" . $row['ITOTAL'] . "</td><td>" . $row['RESULT'] . "</td><td>" . $row['MARKS'] . "</td><td>" . $row['TOTALMARKS'] . "</td><td>" . $row['GRADE'] . "</td>";
        "</tr>";
    }
} 
else {
    echo '<tr><td colspan="17">No Branches - Empty Table</td></tr>';
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