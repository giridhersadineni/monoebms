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
    
    $getexams = "SELECT * FROM examsmaster where status='NOTIFY' ORDER BY SEMESTER";
    
    if($_GET['test']==1){
        $getexams = "SELECT * FROM examsmaster where status='NOTIFYTEST' ORDER BY SEMESTER";
    }
   
   
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

<input type='hidden' name='hallticket' value='<?php echo $student["haltckt"]?>'> </input>

<div class='container-fluid'>
<div class='row'> 

   <?php while ($row = mysqli_fetch_assoc($exams)): ?>
                    <div class='col-sm-6'>
                    <div class='card p-0'>
                    <div class="card-body p-2">
                        
                    <h5><?= $row["EXAMNAME"] ?></h5>
                    <h5>Semester : <?= $row["SEMESTER"] ?> 
                    <br> <?= $row["EXAMTYPE"] ?> EXAM</h5>
                    
                    </div>
                    <div class="card-footer p-0 m-0">
                        <form action="<?php if($row['EXAMTYPE'] == 'REGULAR') { echo 'examenrollregular.php' ; }else{ echo 'examenrollsupply.php'; } ?>" method="GET">
                            <input type="hidden" name="course" value="<?=$student["course"]?>">
                            <input type="hidden" name="group" value="<?=$student["group"]?>">
                            <input type="hidden" name="medium" value="<?=$student["medium"]?>">
                            <input type="hidden" name="semester" value="<?=$row["SEMESTER"]?>">
                            <input type="hidden" name="scheme" value="<?=$student["SCHEME"]?>">
                            <input type="hidden" name="examid" value="<?=$row["EXID"]?>">
                            <input type="hidden" name="et" value="<?=$row["EXAMTYPE"]?>">
                            <p class="text-right m-2">
                                <?php if($student['onboarding_complete']==1):?>
                                
                                    <button type="submit" class ="btn btn-info w-50">Apply <i class="fa-solid fa-arrow-right"></i></button>
                                <?php else: ?>
                                    <p class="text-danger m-2"> Update Details before applying </p> 
                                    <a href="editdetails.php" class="btn btn-primary">Update Details</a>
                                <?php endif; ?> 
                            </p>
                        </form>    
                    </div>
            
         </div>
        </div>

<?php endwhile; ?>
<?php mysqli_close($conn); ?>

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