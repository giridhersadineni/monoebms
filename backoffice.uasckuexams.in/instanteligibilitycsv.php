<?php

// Headers to generate excel file

header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-type:   application/x-msexcel; charset=utf-8");
header("Content-Disposition: attachment; filename=instanteligibility.csv"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);


include 'functions.php';
if(isset($_GET['series'])){
    $series=$_GET['series'];
    $getstudents="select haltckt,course,`group` from students where haltckt like '$series%' order by haltckt ";
}
echo "HALLTICKET,COURSE,GROUP,CREDITS ACQUIRED,COUNT,FAILED SUBS\n ";
$students=getresult($getstudents);
// print_r($students);
foreach($students as $student){
    $hallticket = $student['haltckt'];
    $course=$student['course'];
    $group=$student['group'];
    echo $hallticket.",".$course.", ". $group.", ";
    $getcredits="select sum(CREDITS) as ACK_CREDITS FROM rholdernew where HALLTICKET='$hallticket' AND RESULT = 'P' and RESULT NOT IN ( 'F' , 'AB')";
    $creditsacquired=getscalarvalue($getcredits);
    echo $creditsacquired.",";
    
    $getfailedsubs="select PAPERCODE from rholdernew where HALLTICKET='$hallticket' and PAPERCODE like '6%' and RESULT IN ('F','AB')";
// echo "<br>".$getfailedsubs."<br>";
    $subs=getresult($getfailedsubs);

// var_dump($subs);
    echo $subs->num_rows.",";
    if($subs->num_rows>0){
        while($row=mysqli_fetch_assoc($subs)){

// print_r($row);
      
        echo $row['PAPERCODE']." ";
        }
        echo ",";
    }
    else{
        echo "0,";
    }
    
    echo "\n";
}



?>