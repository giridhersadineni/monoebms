<?php
/*
session_start();
if(!(isset($_SESSION['login'])))
{
header("location:index.php?sessionexpired");
}*/
?>
<?php include "header.php"?>

<?php
if(isset($_POST['update'])){
    
   echo '<script>alert("Updated Successfull");</script>';
}


include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from allpapers";
    $result = $conn->query($sql);

}
?>

        <!-- Page wrapper  -->

<div class="page-wrapper">
<!-- Bread crumb -->
<div class="row page-titles">
<div class="col-md-5 align-self-center">
<h3 class="text-primary">Manage Exam Papers</h3> </div>
<div class="col-md-7 align-self-center">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
<li class="breadcrumb-item active">Exam Papers Master</li>
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
<h6 class="card-subtitle">Manage All Exam Papers</h6>
<div class="table-responsive m-t-40">

<table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
<p align="right"> <a href="addpapers.php" class="btn btn-primary" role="button">ADD PAPER</a></p>

                 <thead>
                          <tr>
                          <th>Id</th>
                     <th>Course</th>
                     <th>Medium</th>
                     <th>PaperCode</th>
                     <th>Paper title</th>
                     <th>Group Code</th>
                     <th>Semester</th>
                     <th>Description</th>
                     <th>EGroup</th>
                     <th>View</th>
                     <th>Edit</th>
                     <th>Delete</th>
                               </tr>
                       </thead>
                                 <tfoot>
                                     <tr>
                                     <th>Id</th>
                                     <th>Course</th>
                                     <th>Medium</th>
                                     <th>PaperCode</th>
                                     <th>Paper title</th>
                                     <th>Group Code</th>
                                     <th>Semester</th>
                                     <th>Description</th>
                                     <th>EGroup</th>
                                      <th>View</th>
                                      <th>Edit</th>
                                      <th>Delete</th>
                                     </tr>
                                 </tfoot>

<?php
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td><a href='viewpapers.php?id=" . $row["ID"] . "'>" .$row["ID"]. "</a></td>  <td>".$row["COURSE"]."</td>
        <td>".$row["MEDIUM"]."</td>
        <td>" . $row["PAPERCODE"] . "</td><td>" . $row["PAPERNAME"] . "</td><td>" . $row["GROUPCODE"] . "</td><td>" . $row["SEM"] . "</td><td>" . $row["DESCRIPTION"] . "</td><td>" . $row["EGROUP"] . "</td>";

        echo '<td><a href="viewpaper.php?id=' . $row["ID"] . '" class="btn btn-info">View</a></td>';

        echo '<td><a href="editpaper.php?id=' . $row["ID"] . '" class="btn btn-success">Edit</a></td>';

        echo '<td><a href="#" class="btn btn-danger">Delete</a></td></tr>';

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