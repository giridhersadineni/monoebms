<?php

header('Content-type: text/csv');
header("Content-Disposition: attachment; filename=report.csv"); 
header("Content-Transfer-Encoding: binary");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

ini_set('display_errors', 1);
include "config.php";
include "projectorm.php";


//check connection
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    
    if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
    } 
    else{
        $getstudents="select * from students where haltckt like '1702200%' order by haltckt";
        $studentsresult=$conn->query($getstudents);
        echo "HALLTICKET,COURSE,GROUP,ATTEMPTED,PASSED,FAILED/ABSENT,EXAMS ENROLLED\n";
        while($student=mysqli_fetch_assoc($studentsresult)){
            $getstudentpapers="select * from rholdernew where HALLTICKET=".$student['haltckt'];
            
            echo $hallticket=$student['haltckt'];
            echo ",";

            echo $student['course'];
            echo ",";
            
            echo $student['group'];
            echo ",";
            
            $exams=get_result_array("select distinct(EXAMID) from rholdernew where HALLTICKET='$hallticket'");
            
           
            $totalattempts=get_result_array("select * from rholdernew where HALLTICKET='$hallticket'");
            $passed=get_result_array("select * from rholdernew where HALLTICKET='$hallticket' and GRADE!='F' AND GRADE!='AB'");
            
            // echo "<hr>";
            // print_r(count($totalattempts));
            // echo "<hr>";
            // print_r(count($passed));
            
            echo count($totalattempts).",".count($passed);
            echo ",";
            echo count($totalattempts)-count($passed);
            echo ",";
             foreach($exams as $exam){
                echo $exam['EXAMID']." ";
            }
            
            

          
        
            echo "\n";
        }

    }// end of students
    ?>
