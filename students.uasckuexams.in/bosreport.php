<!-- Page wrapper  -->
<?php 
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, "atech_ebms");
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "SELECT DISTINCT(EXAMID),examsmaster.* FROM RESULTS_PREMOD LEFT JOIN examsmaster ON examsmaster.EXID=RESULTS_PREMOD.EXAMID order by `COURSE`,SEMESTER";
    $exam= $conn->query($sql);
    
     $nsql = "SELECT DISTINCT(PAPERCODE),PAPERNAME FROM RESULTS_PREMOD ORDER BY PAPERCODE";
    $paper= $conn->query($nsql);
}
?>


<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">BOS Report Generation</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">BOS Report Generation</li>
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
                        <h3>Generate BOS Report</h3>
                        <form action="printbosreport.php" method="GET">
                           
                           <div class="form-group">
                           <label for="exam" class="text-primary"> Select Exam</label>
                          
                            <select name="exam" class="form-control">
                            <option name="exam"  value="" disabled selected>Select Exam</option>
                                  <?php
                            while ($nexam= mysqli_fetch_assoc($exam)) {
                            
                                echo '<option name="exam" value="'.$nexam['EXAMID'].'">' . $nexam["EXAMNAME"]." ". $nexam['EXAMID'] ."</option>";
                            }
                            ?>
                           </select>
                               
                           </div>
                           
                           
                          
                           <br>
                            <div class="form-group">
                            <label for="pcode" class="text-primary"> Select Papercode</label>
                          
                            <select name="pcode" class="form-control">
                             <option name="pcode"  value="0">--Select--</option>
                                  <?php
                            while ($papercode= mysqli_fetch_assoc($paper)) {
                            
                                echo '<option name="pcode" value="'.$papercode['PAPERCODE'].'">' . $papercode["PAPERCODE"].'-'.$papercode["PAPERNAME"]."</option>";
                            }
                            ?>
                           </select> 
                           </div>
                          
                           <br>
                           
                            <input type="submit" class="btn btn-primary">
                          
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

