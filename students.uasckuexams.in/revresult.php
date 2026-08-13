<?php
if (isset($_GET['updated']))
{
    echo '<script>alert("Updated Successfull");</script>';
}
?>
<?php 
include 'config.php';
//check connection
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "SELECT DISTINCT(haltckt) FROM enrolledview where  FEEPAID='1' ORDER BY haltckt";
    $result= $conn->query($sql);

    $nsql = "SELECT * FROM rholderrevaluation";
    $exam= $conn->query($nsql);
    
    //   $psql = "SELECT DISTINCT(PAPERCODE),PAPERNAME FROM allpapers ORDER BY PAPERCODE";
    // $paper= $conn->query($psql);
}
?>
<?php include 'header.php'?>
<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Result</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Result</li>
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
                
                        

                        <form action="vresult.php" method="GET">
                             <p class="text-muted m-b-15 f-s-12"><h4>SELECT HALLTICKET</h4></p>
                          
                            <select name="hallticket" class="form-control">
                             <option name="hallticket" class="form-control"  value="0">--Select--</option>
                                  <?php
                            while ($nresult= mysqli_fetch_assoc($result)) {
                            
                                echo '<option class="form-control" name="hallticket" value="'.$nresult['haltckt'].'">' . $nresult["haltckt"]."</option>";
                            }
                            ?>
                           </select> 
                           <br>
                         
                           <div class="row">
                                <div class="col-md-6">
                                <div class="form-group">
                                <p class="text-muted m-b-15 f-s-12"><h4>ENTER SCRIPIT CODE</h4></p>
                 <input type="text"  class="form-control" name="scripit" id="scripit" placeholder="enter code" >

                    </div></div></div>
                          <!--<label for="pcode" class="text-primary"> Select Papercode</label>-->
                          
                          <!--  <select name="pcode">-->
                          <!--   <option name="pcode"  value="0">--Select--</option>-->
                               <?php
                         //  while ($papercode= mysqli_fetch_assoc($paper)) {-->
                            
//echo '<option name="pcode" value="'.$papercode['PAPERCODE'].'">' . $papercode["PAPERCODE"].'-'.$papercode["PAPERNAME"]."</option>";-->
                         //  }-->
                           ?>
                          <!-- </select> -->
                          
                           <br>
                           
                           <center>
                            <input type="submit" >
                            </center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>








<?php include 'footer.php'?>