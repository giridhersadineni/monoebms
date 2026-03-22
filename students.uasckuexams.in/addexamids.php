<?php

include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

//check connection
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
$res = "select * from rhmodified";
$rhcursor=$conn->query($res);

while($rh=mysqli_fetch_assoc($rhcursor)){
    $eid= $rh['EID'];
    $query="select EXAMID from examenrollments where ID=".$eid;
   // echo $query."<br>";
    $r=$conn->query($query);
    while($examid=mysqli_fetch_assoc($r)){
        $update="update rhmodified set EXAMID=".$examid['EXAMID']." where EID=".$eid;
        //echo $update;
        $conn->query($update);
        echo $rh["RHID"] . "\n";
    }
    
    
    //echo $eid;
    
    
}

}
?>
  