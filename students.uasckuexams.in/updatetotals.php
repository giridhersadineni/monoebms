<?php 


    // header('Content-type: text/csv');
    // header("Content-Disposition: attachment; filename=report.csv"); 
    // header("Content-Transfer-Encoding: binary");
    // header("Expires: 0");
    // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    // header("Cache-Control: private",false);
    
    echo "GPAID,EXAMID,HALLTICKET,TOTAL,RESULT,SGPA,CGPA,PROCESSID,NTOTAL";
    echo "\n";


    set_time_limit(600);
    include 'config.php';
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

    
            
    function consolidatereport($papers){
                $marks_sec=0;
                $totalmarks=0;
                $credit_total=0;
                $gpc_total=0;
                $gpcc_total=0;
                $percentages=array();
                $cgpa_percentages=array();
                foreach($papers as $paper){
                    $gpc_total+=$paper['GPC'];
                    $credit_total+=$paper['CREDITS'];
                    $marks_sec+=$paper['MARKS'];
                    $totalmarks+=$paper['TOTALMARKS'];
                    $gpcc_total+=$paper['GPCC'];
                }
                $result=array();
                $result['marks']=$marks_sec;
                $result['total']=$totalmarks;
                $result['gpc_total']=$gpc_total;
                $result['credits']=$credit_total;
                $result['cgpa']=$gpc_total/$credit_total;
                $result['OGPA']=$gpcc_total/$credit_total;
                $per=$marks_sec/$totalmarks*100;
                $cper=$result['OGPA']*10;
                $result['CF']=$per-$cper;
                $result['PERCENTAGE']=$per;
                $result['DIVISION']=get_division($result['cgpa']);
                return $result; 
    }
            
    function getsempapers($hallticket,$examid){
                global $conn;
                $query = "select * from  rholdernew HALLTICKET='$hallticket' AND EXAMID='$examid'";
                // echo $query;
                $queryresult=$conn->query($query);
                $papers=array();
                //print_r($papers);
                while($sub=mysqli_fetch_assoc($queryresult)){
                    array_push($papers,$sub);
                };
                return $papers;
    }
    
    function get_total($hallticket,$examid){
        global $conn;
        $query="SELECT sum(marks) as TOTAL from rholdernew where INT !='AB' AND EXT !='AB' AND HALLTICKET='$hallticket' and EXAMID='$examid' GROUP BY EXAMID";
        echo $query;
        $queryresult=$conn->query($query);
        $total=mysqli_fetch_assoc($queryresult);
        print_r($total);
        return $total['TOTAL'];
        }
            

    $getstudents="select * from gpas";
    // echo $getstudents;
    $studentsresult=$conn->query($getstudents);
    while($student=mysqli_fetch_assoc($studentsresult)){
       
       
    //   echo (implode($student,","));
       echo ",";
       echo get_total($student['HALLTICKET'],$student['EXAMID']);
       echo "\n";
       break;
    }
    
?>
       