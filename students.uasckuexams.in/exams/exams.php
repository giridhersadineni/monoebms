
<?php include "../header.php" ?>
<?php
include "../config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
    $sql = "select * from examsmaster";
    $result = $conn->query($sql);

}
?>

        <!-- Page wrapper  -->

<div class="page-wrapper">
<!-- Bread crumb -->
  <div class="row page-titles">
     <div class="col-md-5 align-self-center">
       <h3 class="text-primary">Add/Manage Exams</h3>
      </div>

      <div class="col-md-7 align-self-center">
            <ol class="breadcrumb"> 
            <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
            <li class="breadcrumb-item active">Exams</li>
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
<div class="card-body"><h4>Add Manage Exams</h4>

<div class="table-responsive m-t-40">
<table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
<p align="right"> <a href="add.php" class="btn btn-primary" role="button">ADD EXAM</a></p>

                 <thead>
                          <tr>
                     <th>Exam Id</th>
                     <th>Semester</th>
                     <th>Course</th>
                     <th>Exam Name</th>
                     <th>Month</th>
                     <th>Year</th>  
                     <th>Exam Type</th>
                     <th>Status</th>
                     <th>Regular Fee</th>
                     <th>Above 2 subject</th>
                     <th>Fine Amount</th>
                     <th>Improvement Amount</th>
                     <th>Edit</th>
                     <!--<th>Delete</th>-->
                               </tr>
                       </thead>
                   

<?php
if ($result->num_rows > 0) {
    // output data of each row
while($row = mysqli_fetch_assoc($result)) 
{
echo "<tr>";
echo "<td> <a href='examtable.php?EXID=". $row["EXID"]."'>".$row["EXID"]."</a></td><td>".$row["SEMESTER"]."</td><td>".$row['COURSE']."</td><td>" .$row["EXAMNAME"]. "</td><td>" .$row["MONTH"]. "</td> <td>" . $row["YEAR"]. "</td><td>" . $row["EXAMTYPE"]. "</td><td>".$row["STATUS"]."</td><td>".$row["FEE"]."</td><td>".$row["ABOVE2SUBS"]."</td><td>".$row["FINE"]."</td><td>".$row["IMPROVEMENT"]."</td>";

/*echo '<td><a href="#?examid='.$row["EXID"].'" class="btn btn-info">View</a></td>';*/

echo '<td><a href="edit.php?EXID='.$row["EXID"].'" class="btn btn-success">Edit</a></td>';


 }
}
 else {
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
<?php include "../datatablefooter.php"; ?>