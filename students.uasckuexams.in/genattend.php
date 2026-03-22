<?php include "header.php"?>

<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    
    $nsql = "SELECT DISTINCT(PAPERCODE),PAPERNAME FROM allpapers ORDER BY PAPERCODE";
    $npapercoderesult = $conn->query($nsql);
    
    $sql = "SELECT DISTINCT(PAPERCODE),PAPERNAME FROM PAPERTITLES ORDER BY PAPERCODE";
    $papercoderesult = $conn->query($sql);
    
    $getexamids = "select * from examsmaster order by EXID desc";
    $exams = $conn->query($getexamids);
}
?>

<!-- Page wrapper  -->

<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Attendance Statement</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active"> Attendance Sheet Generation</li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-light ">
                    <div class="card-header bg-light text-dark">
                        <h2>GENERATE ATTENDANCE SHEET</h2>
                        <h3>Select Subject to Generate Attendance Statement</h3>
                    </div>
                    <div class="card-body">
                        <form action="printattendsheet.php" method="GET">
                            <div class="form-group">
                            <h5 for="course" class=""> Select Papercode</h5>
                            <select name="paper" class="form-control">
                                <option>Select Papercode</option>
                                <?php
                                while ($npapercode = mysqli_fetch_assoc($npapercoderesult)) {
                                echo "<option value='".$npapercode["PAPERCODE"]."'>" . $npapercode["PAPERCODE"] ." ".$npapercode["PAPERNAME"] . "</option>";
                                
                                }
                                 while ($npapercode = mysqli_fetch_assoc($papercoderesult)) {
                                echo "<option value='".$npapercode["PAPERCODE"]."'>" . $npapercode["PAPERCODE"] ." ".$npapercode["PAPERNAME"] . "</option>";
                                }
                            ?>
                           </select>
                            </div>
                            <div class="form-group">
                            <h5 for="examid" class=""> Select Exam</h5>
                            <select name="examid" class="form-control">
                                <?php
                                while ($exam = mysqli_fetch_assoc($exams)) {
                                    print_r($exam);
                                    echo "<option value='".$exam["EXID"]."'>" . $exam["EXID"] ." ".$exam['EXAMNAME']. "</option>";
                                }
                                ?>
                           </select>
                           </div>
                            <div class="form-group">
                            <input type="submit" class="btn btn-success form-control w-25">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "datatablefooter.php";?>
    