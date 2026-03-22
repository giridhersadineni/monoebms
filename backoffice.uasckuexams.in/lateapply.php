<?php include 'header.php'?>

<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $getstudent = "select * from students where haltckt='" . $_COOKIE['userid'] . "'";
    $sr = $conn->query($getstudent);
    $student = mysqli_fetch_assoc($sr);
    //echo $getstudent;
    
    $getexams = "SELECT * FROM examsmaster where COURSE='".$student['course']."' and SCHEME='".$student['SCHEME']."' and status='RUNNING' order by SEMESTER";
   // echo $getexams;
    $exams = $conn->query($getexams);
   
   
   //echo $getexams;
    
    //print_r($student);
 
 
}

?>

<div class="page-wrapper">
<!-- Bread crumb -->
<div class="row page-titles">

<div class="col-md-5 align-self-center">
<h3 class="text-primary">Exam Regular</h3>
</div>
<div class="col-md-7 align-self-center">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="javascript:void(0)">student</a></li>
<li class="breadcrumb-item active">Exam Regular </li>
</ol>
</div>
</div>

<form method="POST" action="enrollservice.php">
<input type='hidden' name='hallticket' value='<?php echo $student["haltckt"]?>'> </input>

<div class='container-fluid'>
<div class='row'> 
<?php
if ($exams->num_rows > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($exams)) {
   
        // print_r($row);
        echo "<div class='col-sm-6'>";
        echo "<div class='card'>";
        echo "<h5>".$row["EXAMNAME"]."</h5>";
       echo "<h6>Semester:".$row["SEMESTER"]."</h6>"; 
        if($row['EXAMTYPE']=="REGULAR")
        {
            
        echo '<a href="examenrollregular.php?scheme='.$row['SCHEME'].'&examid=' . $row["EXID"] . '&course=' . $student['course'] . '&group=' . $student['group'] . '&medium=' . $student['medium'] . '&semester=' . $row["SEMESTER"] . '&et=' . $row["EXAMTYPE"] . '" class="btn btn-info">Apply </a>';
        }
        else {
            echo '<a href="examenrollsupply.php?scheme='.$row['SCHEME'].'&examid=' . $row["EXID"]. '&course=' . $student['course'] . '&group=' . $student['group'] . '&medium=' . $student['medium'] . '&semester=' . $row["SEMESTER"] . '&et=' . $row["EXAMTYPE"] . '" class="btn btn-info">Apply</a>';
      
        }
 echo "</div>";
echo "</div>";
}
  
} else {
    echo '<h1> Waiting for Exams? Stay Tuned.<h1>';

}
?>


<?php
mysqli_close($conn);

?>

</div>
</div>

</div>
<!--</div>-->
<!--</div>-->
<!--</div>-->
<!--</div>-->
        <!-- End footer -->
        <!--</div>-->
        <!-- End Page wrapper  -->
    <!--</div>-->

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
