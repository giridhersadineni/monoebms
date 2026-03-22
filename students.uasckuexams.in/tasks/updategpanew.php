<?php
error_reporting(E_ALL);
include "config.php";
//check connection
$examid=$_GET['examid'];
if(!isset($_GET['processid'])){
    $processid="NA";
}
else{
    $processid=$_GET['processid']; 
}

echo $examid;
echo $processid;

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

//check connection
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
$getenrollmentids = "select DISTINCT(HALLTICKET) FROM rholdernew where EXAMID=$examid";
$deleteenrollments="DELETE FROM gpas where EXAMID=$examid";
echo $getenrollments;
$deleted=$conn->query($deleteenrollments);
var_dump($deleted);
$enrollmentids = $conn->query($getenrollmentids);
if($enrollmentids->num_rows>0){
    $total=0;
    $credit_total=0;
    $gpc_total=0;
    while($row=mysqli_fetch_assoc($enrollmentids)){
       // print_r($row);
       $sql="select * from rholdernew where HALLTICKET=".$row['HALLTICKET']." and EXAMID=$examid";
        $studentres=$conn->query($sql);
       if($studentres->num_rows>0){
            echo "<hr><table border=1>";
                $result="P";
                $total=0;
                $credit_total=0;
                $gpc_total=0;
                        
                while($paper=mysqli_fetch_assoc($studentres)){
                    $hallticket=$row['HALLTICKET'];
                    //echo "HALLTICKET :".$hallticket;
                    echo "<tr>";
                    foreach($paper as $cell){
                        echo "<td>$cell</td>";
                        
                    }
                    echo "</tr>";
                    
                    $total=$total+$paper['MARKS'];
                    $credit_total=$credit_total+$paper["CREDITS"];
                    $gpc_total=$gpc_total+$paper["GPC"];
                    if($paper['RESULT']=="F" || $paper['RESULT']=="AB"){
                        $result="R";
                    
                    
                    }
                    
                }//end of fetching papers
                echo "GPC TOTAL".$gpc_total;
                echo "CREDITS TOTAL ".$credit_total."<hr>";
                
                echo $sgpa=$gpc_total/$credit_total;
                echo $sgpa=round($sgpa,2);
                echo $cgpa=$sgpa;
                 $query="INSERT INTO `gpas`(`GPAID`,`EXAMID`, `HALLTICKET`, `TOTAL`, `RESULT`, `SGPA`, `CGPA`, `PROCESSID`) values (NULL,'$examid','$hallticket',$total,'$result','$sgpa','$cgpa','$processid')";
                echo "</table>";
                echo $query;
                if($conn->query($query)){
                    echo "<hr>Update Successfull<hr>";
                }
                
       }
        
    }
    }
}

?>
  