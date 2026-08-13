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

}
?>

<!-- Page wrapper  -->

<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Script Generation</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active"> Script Generation</li>
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
                
                        
                        <br><br>
                        <h1>Generate Answer Script Coding</h1>

                        <form action="scriptcode.php" method="GET">
                            <label for="course" class="text-primary"> Select Papercode</label>
                            <select name="paper">
                                <?php
                                while ($npapercode = mysqli_fetch_assoc($npapercoderesult)) {
                                    echo "<option value='". $npapercode["PAPERCODE"] ."'>" . $npapercode["PAPERCODE"] ." ".$npapercode["PAPERNAME"] . "</option>";
                                }
                                while ($npapercode = mysqli_fetch_assoc($papercoderesult)) {
                                    echo "<option value='". $npapercode["PAPERCODE"] ."'>" . $npapercode["PAPERCODE"] ." ". $npapercode["PAPERNAME"] . "</option>";
                                }
                            ?>
                           </select>
                            <input type="submit" >
                        </form>


                        


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