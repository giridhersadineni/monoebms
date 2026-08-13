<?php
error_reporting(E_ALL);
include "config.php";
//check connection
$examid=$_GET['eid'];
$processid=$_GET['processid'];

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

//check connection
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
$getenrollmentids = "select ID as enrollmentid,haltckt as HALLTICKET from enrolledview where EXAMID in($examid) and feepaid=1";
echo $res;
$enrollmentids = $conn->query($getenrollmentids);
if($enrollmentids->num_rows>0){
    
    while($row=mysqli_fetch_assoc($enrollmentids)){
       // print_r($row);
       $sql="select * from rholdernew where EID=".$row['enrollmentid'];
        $studentres=$conn->query($sql);
       if($studentres->num_rows>0){
            echo "<hr>";
                $result="P";
                $total=0;
                $credit_total=0;
                $gpc_total=0;
                        
                while($paper=mysqli_fetch_assoc($studentres)){
                    $hallticket=$row['HALLTICKET'];
                    //echo "HALLTICKET :".$hallticket;
                    echo implode($paper,"  ") ."<br>";
                    $total=$total+$paper['MARKS'];
                    $credit_total=$credit_total+$paper["CREDITS"];
                    $gpc_total=$gpc_total+$paper["GPC"];
                    
                    if($paper['RESULT']=="F" || $paper['RESULT']=="AB"){
                        $result="R";
                    }
                }//end of fetching papers
                echo $gpc_total;
                echo $credit_total;
                
                $sgpa=$gpc_total/$credit_total;
                $sgpa=round($sgpa,PHP_ROUND_HALF_UP);
                $cgpa=$sgpa;
                 $query="INSERT INTO `gpas`(`EXAMID`, `HALLTICKET`, `TOTAL`, `RESULT`, `SGPA`, `CGPA`, `PROCESSID`) values ('$examid','$hallticket',$total,'$result','$sgpa','$cgpa','$processid')";
                echo $query;
                // if($conn->query($query)){
                //     echo "<hr>Update Successfull<hr>";
                // }
                
       }
        
    }
    }
}

?>
  