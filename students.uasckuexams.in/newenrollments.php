<?php include "header.php"?>

<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from  enrolledview where haltckt='" . $_COOKIE['userid'] . "'";
    $result = $conn->query($sql);

}
?>

        <!-- Page wrapper  -->


<!-- Bread crumb -->
<div class="row page-titles">
<div class="col-md-5 align-self-center">
<h3 class="text-primary">Exams Registered</h3> </div>
<div class="col-md-7 align-self-center">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="javascript:void(0)">Student</a></li>
<li class="breadcrumb-item active">Enrollments</li>
</ol>
</div>
</div>



           
<?php
if ($result->num_rows > 0) {
   
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
    //print_r($row["FEEPAID"]);
        //echo $row['EXAMNAME']." ".$row['STATUS'];
        

         echo "<div class='container-fluid'>";
        echo "<div class='card'>";
        echo "<div class='row'>";
        echo "<div class='col-12'>";
        echo "<h5>" . $row["EXAMNAME"] . "&nbsp&nbsp&nbsp&nbsp&nbsp" . $row["EXAMTYPE"] . "&nbsp&nbsp&nbsp&nbsp&nbsp" . $row["ENROLLEDDATE"] . "</h5>";
      
     

  echo "<center>";
        echo "<div class='col-md-2'>";
        
        if($row['STATUS']=="CLOSED"){
             echo '<a href="mainresult.php?id=' . $row["ID"] . '" class="btn btn-warning">View Results</a>';
        }
    
        if($row["STATUS"]=='OPEN' && $row["FEEPAID"] =='1')
        {
           //  echo '<td><a href="printapplication.php?id=' . $row["ID"] . '" class="btn btn-info">Print Application</a></td>';
        //   echo '<td>';
          echo '<a href="printhallticket.php?id=' . $row["ID"] . '" class="btn btn-success">Print Hallticket</a>';
        }
         if($row["STATUS"]=='OPEN' && ($row["FEEPAID"] =='' ||$row["FEEPAID"] =='0'))
        {
           //  echo '<td><a href="printapplication.php?id=' . $row["ID"] . '" class="btn btn-info">Print Application</a></td>';
          echo '<a href="bankchallan.php?id=' . $row["ID"] . '" class="btn btn-info">Generatechallan</a>
          <a href="printapplication.php?id=' . $row["ID"] . '" class="btn btn-info">Print Application</a>
          ';
        }
       
 echo "</div>";
        echo "</div>";
         
echo "</center>";
echo "</div>";
echo "</div>";
echo "</div>";
         
         
}
} else {
    echo '<tr><td colspan="5">No Branches - Empty Table</td></tr>';

}

?>

<?php
mysqli_close($conn);

?>





<?php include "datatablefooter.php";?>