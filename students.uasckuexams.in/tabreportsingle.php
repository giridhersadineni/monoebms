
<style>
    *{
        font-family:arial;
    }
    .resulttable{
        margin-top:0px;
        padding-bottom:6px;
        width:100%;
        page-break-inside: avoid;
    }
    hr{
        border:1px dashed;
        margin-bottom:2px;
    }
    td{
        font-size:<?php echo $_GET['fontsize'];?>;
    }
    .student-title{
        padding:0px;
        margin:0px;
    }
    
</style>
<table style="width:100%">
<thead>
    <!--<h4 style="text-align:center">-->
    <!--    EXAMINATION BRANCH <BR>-->
    <!--        UNIVERSITY ARTS & SCIENCE COLLEGE-->
            
    <!--</h4>-->
    <H6 style="text-align:center;">TABULATION LIST</H6>
</thead>    
<tbody>
<?php
set_time_limit(1000);
ini_set('display_errors', 1);
include "config.php";
//check connection
    // $start=$_GET['start'];
    // if(isset($_GET['end'])){
    // $end=$_GET['end'];
    // }
    // else {
    //     $end=$start;
    // }
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    
    if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
    } 
    else{
    
    // $getstudents="select haltckt as HALLTICKET,sname,fname from students where haltckt='001171002'";
    // echo $getstudents;
    // $getstudents="select haltckt as HALLTICKET,sname,fname from students where haltckt in (001171497,001171502,001171507,001171513,001171518,001171804,001171806,001171811,001171812,001171816,001172002,001172014,001172018,001172023,001172037,001172046,001172302,001172317,001172329,001172334,001172605,001172613,001172617,001172621,001172625,001172633,001172637,001173006,001173013,001173023,001173028,001173048,001173049,001173207,001173214,001173217,001173606,001173615,001173631,001173815,001173932,001173934,001173936,001173939,001174004,001174010,001174033,001174205,001174225,001174419,001174425,001174601,001174603,001174607,001174610,001174612,001174801,001174807,001174829,001174924) order by haltckt";
    $hallticket=$_GET['ht']; 
    $getstudents="select grades.*,students.* FROM grades left join students on grades.HALLTICKET=students.haltckt where HALLTICKET='$hallticket' ORDER BY grades.HALLTICKET";
    echo $getstudents;
    // $getstudents="select * FROM grades left join students on grades.HALLTICKET=students.haltckt ORDER BY HALLTICKET LIMIT 200,250";
    // echo $getstudents;
    
    
    
?>
  <?php  
        $studentsresult=$conn->query($getstudents);
        while($student=mysqli_fetch_assoc($studentsresult)){
                echo "<tr><td>";
                echo "<hr>";
                echo "<h4 class='student-title'> HALLTICKET:  ". $student['HALLTICKET']."&nbsp;&nbsp;&nbsp;NAME: ". $student['sname']."     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  S/D/o    ".$student['fname'];
                echo '</h4>';
                echo "<hr>";
                
            
                $getexamids="select distinct(rholdernew.EXAMID),examsmaster.SEMESTER,examsmaster.EXAMTYPE from rholdernew left join examsmaster on rholdernew.EXAMID=examsmaster.EXID where HALLTICKET=".$student['HALLTICKET']." order by examsmaster.SEMESTER,examsmaster.YEAR ";    
                // echo $getexamids;
                $examsresult=$conn->query($getexamids);
            
            while($exam=mysqli_fetch_assoc($examsresult)){
                
                 $getexamdetails=$conn->query("select * from examsmaster where EXID=".$exam['EXAMID']);
                 $exam=mysqli_fetch_assoc($getexamdetails);
                 echo '<h5 style="margin-top:0px;margin-bottom:2px">'.$exam['EXAMNAME'].'</h5>';
                 echo '<div class="resulttable " style="margin-bottom:0px;padding-bottom:0px">';
                        echo '<table style="width:100%;"><tr>';
                        
                        $sql="SELECT `PAPERCODE`,`INT`,`EXT`,`GRADE`,`EID` FROM rholdernew WHERE HALLTICKET='".$student['HALLTICKET']."' AND EXAMID=".$exam['EXID']." ORDER BY PAPERCODE";
                        // echo $sql;
                        $result = $conn->query($sql);
                        echo '<td>PCODE<br>INT<br>EXT<br>GRADE<b></td>';
                        while($row=mysqli_fetch_assoc($result)){
                                echo '<td style="padding-left:2em;">'.$row['PAPERCODE'].'<br>'.ROUND($row['INT']).'<br>'.$row['EXT'].'<br>'.$row['GRADE'].'</td>';
                        }
                        $qsql="SELECT `TOTAL`,`RESULT`,`SGPA`,`CGPA` FROM gpas WHERE HALLTICKET='".$student['HALLTICKET']."' AND EXAMID=".$exam['EXID'];
                        $total= $conn->query($qsql);
                        
                        while($row=mysqli_fetch_assoc($total)){
                              echo '<td style="text-align:right;">TOTAL :'.$row['TOTAL'].'<br>';
                              echo 'RESULT : </b>'.$row['RESULT'].'<br>';
                            //   echo 'SGPA : </b>'.$row['SGPA'].'</td>';
                        }
                        echo '</tr></table>';
                        
                        echo '</div><!--endof result table-->';
                }
                echo "</td></tr></table>";
                echo "<table style='width:100%;border:solid 1px black;page-break-inside:avoid'>";
                  echo "<tr><td>CGPA (PART-1) : ".$student['P1CGPA']."</td>";
                  echo "<td> CGPA (PART-2) : ".$student['P2CGPA']. "</td>";
                  echo "<td>CGPA (OVERALL) : ".$student['ALLCGPA']." </td>";
                  echo "<td>CORRECTION FACTOR : ". $student['ALLCF']."</td>";
                  echo "</tr>";
                  echo "<tr><td>PASSED IN : ".$student['FINALDIV']."</td>";
                  echo "<td>YEAR OF ADMISSION:".$student['ADM_YEAR']."</td></tr>";
                  echo "</table>";
                
            }//end of student
                  
                  
         
    }// end of students
    
    ?>
    </tbody>
</table>
