<?php include "header.php"?>

<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "SELECT DISTINCT(PAPERCODE),PAPERNAME FROM allpapers ORDER BY PAPERCODE";
    $papercoderesult = $conn->query($sql);
    $nsql = "SELECT DISTINCT(PAPERCODE),PAPERNAME FROM PAPERTITLES ORDER BY PAPERCODE";
    $npapercoderesult = $conn->query($nsql);
    $getexamids = "select * from examsmaster order by EXID DESC";
    $exams = $conn->query($getexamids);

}
?>

<!-- Page wrapper  -->

<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">D Form Generation</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active"> D-Form Generation</li>
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
                        <!--<h6 class="card-title">Select Subject to Generate D-Form</h6>-->
                        <!--<h1>Generate D- Form Grouped</h1>-->
                        <!--<form action="printdform.php" method="GET">-->
                        <!--    <label for="course" class="text-primary"> Select Papercode</label>-->
                        <!--    <select name="paper">-->
                        <!--        <?php
                        //    while ($papercode = mysqli_fetch_assoc($papercoderesult)) {
                        //        echo "<option>" . $papercode["PAPERCODE"] . "</option>";
                        //    }
                        //    ?>
                        <!--</select>-->
                        <!--    <input type="submit" >-->
                        <!--</form>-->
                        
                        <br><br>
                        <h1>Generate D- Form Ungrouped</h1>

                        <form action="printdformungrouped.php" method="GET">
                            <label for="course" class="text-primary"> Select Papercode</label>
                            <select name="paper" class="form-control">
                                <?php
                                while ($npapercode = mysqli_fetch_assoc($npapercoderesult)) {
                                    echo "<option value=".$npapercode["PAPERCODE"].">" . $npapercode["PAPERCODE"] ." ".$npapercode["PAPERNAME"] . "</option>";
                                }
                                ?>
                                <?php
                                while ($npapercode = mysqli_fetch_assoc($papercoderesult)) {
                                    echo "<option value=".$npapercode["PAPERCODE"].">" . $npapercode["PAPERCODE"] ." ".$npapercode["PAPERNAME"] . "</option>";
                                }
                                ?>
                                
                           </select>
                           <div class="form-group">
                            <label for="course" class="text-primary"> Select Examid</label>
                            <select name="examid" class="form-control">
                                <?php
                            while ($exam = mysqli_fetch_assoc($exams)) {
                                // print_r($exam);
                                echo "<option value='".$exam["EXID"]."'>" . $exam["EXID"] ." ".$exam['EXAMNAME']. "</option>";
                            }
                            ?>
                           </select>
                          <p class="mt-3">
                
                            <input type="submit"  class="btn btn-primary">
                          </p>
                        </form>


                        <!--<?php print_r($_POST);?>-->


                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include "datatablefooter.php";?>
    <!--form action="gendform.php" method="POST">
                            <label for="course" class="text-primary"> Select Course</label>
                            <select name="course">
                                <option>BA</option>
                                <option>BCOM</option>
                                <option>BSC</option>
                                <select>
                                    <input type="submit" name="getgroups">
                                      </form-->