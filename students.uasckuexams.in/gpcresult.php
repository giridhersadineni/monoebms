 <?php
 error_reporting( E_ALL );

include "config.php";

   


//check connection
$examid=$_GET['examid'];
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

//check connection
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
$getenrollmentids = "SELECT DISTINCT(EID) as enrollmentid,HALLTICKET FROM rholdernew WHERE EXAMID=$examid ORDER BY EID";
echo $res;
$enrollmentids = $conn->query($getenrollmentids);
if($enrollmentids->num_rows>0){
    echo "Total Students".$enrollmentids->num_rows;
    while($row=mysqli_fetch_assoc($enrollmentids)){
                $sql="select * from rholdernew where EID=".$row['enrollmentid'];
                //echo $sql;
                $studres=$conn->query($sql);
                if($studres->num_rows>0){
                    echo "<hr>";
                    echo $row['HALLTICKET'];
                    echo "<hr>";
                    $total=0;
                    $credit_total=0;
                    $gpc_total=0;
                    $result="P";
                    
                                while($sub=mysqli_fetch_assoc($studres))
                                {
                                   //echo implode($sub,"  ") ."<br>";
                                   //print_r($sub);
                                   echo "<br>EnrollmentID:".$row['enrollmentid'];
                                   if($sub['MARKS']=="AB"){
                                       $total=$total+0;
                                   }
                                   else{
                                       $total=$total+$sub["MARKS"];
                                   }
                                   
                                  
                                    $credit_total=$credit_total+$sub["CREDITS"];
                                    $gpc_total=$gpc_total+$sub["GPC"];
                                    if($sub["RESULT"]=="F" || $sub["RESULT"]=="AB"  )
                                        {
                                        $result="R";
                                        
                                        }
                                    
                                }
                            if($result=="P"){
                                $SGPA=$gpc_total/$credit_total;
                                echo "<hr>";
                                echo "<p style='background-color:blue;color:white'> SGPA= $SGPA &nbsp; &nbsp;  TOTAL=$total</>";
                                echo "<hr>";
                            }
                            else if($result=="R"){
                                $SGPA='0';    
                                echo "<hr>";
                                echo "<p style='background-color:red;color:white'> RESULT: R </>";
                                echo "<hr>";
                            }
                            $insertgpa="insert into gpas(EXAMID,HALLTICKET,TOTAL,RESULT,SGPA,CGPA,PROCESSID)values('$examid','".$row['HALLTICKET']."','$total','$result','$SGPA','$SGPA','".$_GET['processid']."')";
                            //echo $insertgpa;
                            $querystatus=$conn->query($insertgpa);
                            
                            if($querystatus){
                                echo "Query OK:".$quertstatus;   
                            }
                            else{
                                echo "<br>Unable to Run:".$insertgpa;
                            }
                    
                  }
        }
    
    }
}
?>
  