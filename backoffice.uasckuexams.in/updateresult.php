<?php

include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

//check connection
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
$res = "select EID,HALLTICKET from testdata where PAPERCODE=1001";

$result = $conn->query($res);
$examtype="";
if($result->num_rows>0){
$i=1;
while($row=$result->fetch_assoc())
 { 
    echo $row["EID"]." ". $row[''];
    
    $trs="SELECT haltckt FROM enrolledview WHERE ID=".$row["EID"];
       
         $trresult=$conn->query($trs);
         if($trresult->num_rows>0)
         {
             while($halltickt=$trresult->fetch_assoc()){
            echo 'number :'. $i++." ".$hallticket["haltckt"].'<br>';
            $query="update testdata set HALLTICKET='".$subs['haltckt']." where EID='".$row["EID"]."'";
           
             }
         }
      

    }
}
    
}
?>
  