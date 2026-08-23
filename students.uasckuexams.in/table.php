<?php include 'header.php'?>

<?php
include "config.php";
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

<div class="page-wrapper">
<!-- Bread crumb -->
<div class="row page-titles">
    
<div class="col-md-5 align-self-center">
<h3 class="text-primary">Current Exam</h3> 
</div>
<div class="col-md-7 align-self-center">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="javascript:void(0)">student</a></li>
<li class="breadcrumb-item active">Exam registration</li>
</ol>
</div>
</div>

<div class="container">
<div class="row justify-content-center">  
<div class="col-sm-12">       
<div class="card">
<div class="card-body">

<div id="wrapper">
<div id="table-scroll"> 

<form method="POST" action="enrollservice.php">
<table class="table">

<thead>
<tr>
<th style="padding:0px;text-align: center;">Exam id</th>
<th style="padding:0px;text-align: left;">Semester</th>
<th style="padding:0px;text-align: left;">Exam Name</th>
<th style="padding:0px;text-align: left;">Month</th>
<th style="padding:0px;text-align: left;">Year</th>
<th style="padding:0px;text-align: left;"></th>
</tr>
</thead>

<?php
if ($result->num_rows > 0) {
    // output data of each row
while($row = mysqli_fetch_assoc($result)) 
{
echo "<tr>";
echo "<td><a href='table.php?id=". $row["EXAMID"]."'>".$row["EXAMID"]."</a></td><td>".$row["SEMESTER"]."</td><td>" .$row["EXAMNAME"]. "</td><td>" .$row["MONTH"]. "</td><td>" . $row["YEAR"]. "</td>";

echo '<td><a href="selectexam.php?examid='.$row["SEMESTER"]. '&group='.$row['COURSE'].'" class="btn btn-info">Registration</a></td></tr>';


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
<center><input type="submit" value="Finish Selection" class="btn-success"> </center>
</form>
</div>
</div>

</div>
</div>  
</div>
</div>
</div>
        <!-- End footer -->
        </div>
        <!-- End Page wrapper  -->
    </div>
    
    <!-- End Wrapper -->
    <!-- All Jquery -->
    <script src="js/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->
    <script src="js/scripts.js"></script>
    <script src="js/lib/datatables/datatables.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="js/lib/datatables/datatables-init.js"></script>
