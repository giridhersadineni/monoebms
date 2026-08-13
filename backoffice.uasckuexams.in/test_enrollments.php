<?php include "header.php"?>

<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from  examenrollments left join examsmaster on examenrollments.EXAMID=examsmaster.EXID where HALLTICKET='" . $_COOKIE['userid'] . "' order by SEMESTER,EXID DESC";
    $result = $conn->query($sql);
//   echo $sql;

}
?>

        <!-- Page wrapper  -->

<div class='page-wrapper'>
<!-- Bread crumb -->
<div class="row page-titles">
<div class="col-md-5 align-self-center">
<h3 class="text-primary">Exam Applications</h3> </div>
<div class="col-md-7 align-self-center">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="javascript:void(0)">Student</a></li>
<li class="breadcrumb-item active">Enrollments</li>
</ol>
</div>
</div>


<div class='container-fluid'>
<div class='row'>

<?php
if ($result->num_rows > 0) {
   
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
    // print_r($row);
    //print_r($row["FEEPAID"]);
        //echo $row['EXAMNAME']." ".$row['STATUS'];
    echo "<div class='col-sm-6'>";
    echo "<div class='card'>";
    echo '<form method="POST" action="mainresult.php">';
    echo "<h5>" . $row["EXAMNAME"]."</h5>";
    echo "<h5>Month & Year " . $row["MONTH"] ." ". $row["YEAR"]."</h5>";

    echo "<h6>Semester: ".$row["SEMESTER"]. "</h6>";
    echo '<input type="hidden" value='.$_COOKIE["userid"].' name="hallticket"></input>';
    echo '<input type="hidden" value='.$row["ID"].' name="enrollmentid"></input>';
    echo '<input type="hidden" value='.$row["EXAMID"].' name="examid"></input>';
    echo '<input type="hidden" value='.$row["stid"].' name="studentid"></input>';
    echo '<input type="hidden" value='.$row["EXAMTYPE"].' name="examtype"></input>';
    echo '<input type="hidden" value='.$row["SEMESTER"].' name="SEM"></input>';
//  echo "<center>";
        if( $row['STATUS']=="CLOSED" || $row['STATUS']=="HIDDEN" ){
          
            echo '<p style="text-align:right;"><input type="submit" class="btn btn-warning" value="Get Result"></input>';
            //echo '<style="text-align:right;"><a href="getrevchallan.php?studentid='. $row["stid"].'&examid='.$row["EXAMID"].'" class="btn btn-info">Generatechallan</a></p>';
            //print_r($row);
            if($row['REVALOPEN']==1){
             echo '<a href="applyrevaluation.php?id=' . $row["ID"] . '" class="btn btn-info">Apply for Revaluation</a>';
             }
             echo '</p>';
            
        }
        if($row["STATUS"]=='RUNNING' && $row["FEEPAID"] =='1')
        {
          echo '<a href="printhallticket.php?id=' . $row["ID"] . '" class="btn btn-success">Print Hallticket</a>';
        }
         if(($row["STATUS"]=='NOTIFY' || $row["STATUS"]=='OPEN') && ($row["FEEPAID"] =='' ||$row["FEEPAID"] =='0' || $row["FEEPAID"] =='1'))
        {
          echo '<a href="bankchallan.php?id=' . $row["ID"] . '" class="btn btn-info">Generatechallan</a>  ';
          echo '<a href="printapplication.php?id=' . $row["ID"] . '" class="btn btn-warning">Print Application</a>  ';
          if($row['FEEPAID']==0){
            echo '<a href="updatepaymentdetails.php?examid='.$row['EXAMID'].'&challan=' . $row["ID"] . '" class="btn btn-danger">Update Payment Details</a>';
            }
        }
        
    echo "</form>";
    echo "</div>";
    echo "</div>";
    }
}else {
    echo '<h1>Results Will be Updated Soon....</h1>';

}

?>


<?php
mysqli_close($conn);

?>
</div>
</div>
</div>


<?php include "datatablefooter.php";?>