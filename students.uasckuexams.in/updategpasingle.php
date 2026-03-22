<?php
error_reporting(E_ALL);
include "config.php";
//check connection
$examid= isset($_GET['exid']) ? $_GET['exid'] :$_GET['examid'];

$hallticket=$_GET['ht'];

echo "Exam Id: ".$examid."<br>";
echo "Hall Ticket: ".$hallticket."</br>";

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

//check connection
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
$deleteenrollments="DELETE FROM gpas where EXAMID=$examid and HALLTICKET=$hallticket";
echo $deletEenrollments;
$deleted=$conn->query($deleteenrollments);
echo "Enrollement Deleted:";
var_dump($deleted);
    
       $sql="select * from RESULTS where HALLTICKET=$hallticket and EXAMID=$examid";
       $studentres=$conn->query($sql);
       if($studentres->num_rows>0){
            echo "<hr>";
                $result="P";
                $total=0;
                $credit_total=0;
                $gpc_total=0;
                        
                while($paper=mysqli_fetch_assoc($studentres)){
                    
                    //echo "HALLTICKET :".$hallticket;
                    echo implode("  ",$paper) ."<br>";
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
                $sgpa=round($sgpa,2);
                $cgpa=$sgpa;
                 $query="INSERT INTO `gpas`(`EXAMID`, `HALLTICKET`, `TOTAL`, `RESULT`, `SGPA`, `CGPA`, `PROCESSID`) values ('$examid','$hallticket',$total,'$result','$sgpa','$cgpa','singleupdate')";
                echo $query;
                if($conn->query($query)){
                    echo "<hr>Update Successfull<hr>";
                }
                
       }
        
    
}

?>
  