<?php
include 'functions.php';
if(isset($_GET['SERIES'])){
    $series=$_GET['series'];
}
echo "HALLTICKET,CREDITS ACQUIRED,COUNT,FAILED SUBS\n <br>";


$getstudents="select haltckt from students where haltckt like '17022%' order by haltckt ";
$students=getresult($getstudents);
// print_r($students);
foreach($students as $student){
    $hallticket = $student['haltckt'];
    echo $hallticket.", ";
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
        echo "";
    }
    
    echo "\n<br>";
}



?>