<?php 

include "config.php";

//check connection
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $psql="select * from rholdernew where EXAMID=".$_GET['exam']." and PAPERCODE=".$_GET['pcode'];
    $code=$conn->query($psql);
    $values=mysqli_fetch_assoc($code);

    $absql = "select count(*) AS totalabsent from rholdernew where EXAMID=".$_GET['exam']." and RESULT='AB' and PAPERCODE=".$_GET['pcode'];
    $totalabsent = $conn->query($absql);
        $values=mysqli_fetch_assoc($totalabsent);
        $absent=$values['totalabsent'];
        echo ('ABSENT :' .$absent);
echo '<br>';
echo '<br>';

    $psql = "select count(*) AS totalpass from rholdernew where EXAMID=".$_GET['exam']." and RESULT ='P' and PAPERCODE=".$_GET['pcode'];
    $totalpass = $conn->query($psql);
        $pass=mysqli_fetch_assoc($totalpass);
        $tpass=$pass['totalpass'];
        echo ('PASSED :'.$tpass);
echo '<br>';
echo '<br>';

    $fsql = "select count(*) AS totalfail from rholdernew where EXAMID=".$_GET['exam']." and RESULT ='F' and PAPERCODE=".$_GET['pcode'];
    $totalfail = $conn->query($fsql);
        $fail=mysqli_fetch_assoc($totalfail);
        $tfail=$fail['totalfail'];
        echo ('TOTALFAILED :'.$tfail);
    
echo '<br>';
echo '<br>';

    $msql = "select MIN(MARKS) AS least from rholdernew where EXAMID=".$_GET['exam']." and PAPERCODE=".$_GET['pcode']." and MARKS !='#VALUE!'";
   // echo $msql;
    $least = $conn->query($msql);
        $totalleast=mysqli_fetch_assoc($least);
        $minmarks=$totalleast['least'];
        echo ('MIN-MARKS :'.$minmarks);    


echo '<br>';
echo '<br>';

 $mxsql = "select MAX(MARKS) AS mxmarks from rholdernew where EXAMID=".$_GET['exam']." and PAPERCODE=".$_GET['pcode']." and MARKS !='#VALUE!'";
   // echo $mxsql;
    $maxmarks= $conn->query($mxsql);
        $totalmax=mysqli_fetch_assoc($maxmarks);
        $maxmarks=$totalmax['mxmarks'];
        echo ('MAX-MARKS :'.$maxmarks);        

echo '<br>';
echo '<br>';

 $avgsql = "select AVG(MARKS) AS avgmarks from rholdernew where EXAMID=".$_GET['exam']." and PAPERCODE=".$_GET['pcode']." and MARKS !='#VALUE!'";
    //echo $avgsql;
 $avrgmarks=$conn->query($avgsql);
 
    $totalavg=mysqli_fetch_assoc($avrgmarks);
    $average=$totalavg['avgmarks'];
    echo ('AVERAGE-MARKS :' .ROUND($average));
    
 $tsql = "select TOTALMARKS AS total from rholdernew where EXAMID=".$_GET['exam']." and PAPERCODE=".$_GET['pcode'];
     
     $gettotal=$conn->query($tsql);
     $total=mysqli_fetch_assoc($gettotal);
       
       $passmarks=($total['total']/100)*40;
       
        $onetopassquery="select count(TOTALMARKS) as count1 from rholdernew  where EXAMID=".$_GET['exam']." and PAPERCODE=".$_GET['pcode'] ."and MARKS=" . ($passmarks-1) ."";
        $twotopassquery="select count(TOTALMARKS) as count2 from rholdernew  where EXAMID=".$_GET['exam']." and PAPERCODE=".$_GET['pcode'] ."and MARKS=".($passmarks-2)."";
        $threetopassquery="select count(TOTALMARKS) as count3 from rholdernew  where EXAMID=".$_GET['exam']." and PAPERCODE=".$_GET['pcode'] ."and MARKS=".($passmarks-3)."";
        $fourtopassquery="select count(TOTALMARKS) as count4 from rholdernew  where EXAMID=".$_GET['exam']." and PAPERCODE=".$_GET['pcode'] ."and MARKS=".($passmarks-4)."";
        
       // echo $onetopassquery;
    $onepass=$conn->query($onetopassquery);    
    echo $onepass;
}



?>