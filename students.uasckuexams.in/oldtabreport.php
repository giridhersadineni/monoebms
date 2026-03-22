<?php include 'header.php' ?>

<?php
include "config.php";
//check connection
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "SELECT DISTINCT(rholdernew.EXAMID),examsmaster.EXAMNAME FROM `rholdernew` LEFT JOIN examsmaster ON EXAMID=examsmaster.EXID";
    $examid = $conn->query($sql);
  
}
?>

<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Tabulaion Report Generation</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Old Tabulaion Report Generation</li>
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
                        <h1></h1>

                        <form action="newtabreport.php" method="GET">
                           
                            <label for="exid" class="text-primary">Select Exam Id </label>
                          
                            <select  name='exid' id="exid" class="form-control">
                            <option name="exid" value="0">--Select--</option>
                                  <?php
                            while ($nexam= mysqli_fetch_assoc($examid)) {
                            
                                echo '<option  value="'.$nexam['EXAMID'].'">'.$nexam["EXAMID"].'-'. $nexam["EXAMNAME"]. "</option>";
                            }
                            ?>
                           </select>
                           <center>
                            <input type="submit">
                            </center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>






<?php include 'footer.php' ?>