
<?php
include 'config.php';
$Examid=$_POST["EID"];
$Hallticket=$_POST["hallticket"];
$Papercode=$_POST["PAPERCODE"];
$Papername=$_POST["papername"];
$eid=$_POST["enrollmenid"];
$code=$_POST["CODE"];
$Ext=$_POST["ext"];
$Etotal=$_POST["etotal"];
$Int=$_POST["int"];
$totalinternal=$_POST["itotal"];
$result=$_POST["result"];
$credits=$_POST["CREDITS"];
$marks=$_POST["tmarks"];
$totalmarks=$_POST["totalmarks"];
$percentage=$_POST["percentage"];
$grade=$_POST["grade"];
$gpc=$_POST["gpc"];
$gpv=$_POST["gpv"];
//print_r($_POST);
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if (!$conn){
    die("connection failed:".mysqli_connect_error());
}
  $sql="INSERT INTO `rholdernew` ( EXAMID, `PAPERCODE`, `PAPERNAME`,`HALLTICKET`,`EID`,`CODE`,`EXT`,`ETOTAL`,`INT`,`ITOTAL`,`RESULT`,`CREDITS`,`MARKS`,`PERCENTAGE`,`GRADE`,`GPV`,`GPC`) 
VALUES ('".$Examid."','".$Papercode."','".$Papername."','".$Hallticket."','".$eid."','".$code."','".$Ext."','".$Etotal."','".$Int."','".$totalinternal."','".$result."','".$credits."','".$marks."','".$percentage."','".$grade."','".$gpv."','".$gpc."')";
 
// echo $sql;
if ($conn->query($sql) === TRUE) {
    
     header("Location:addresultholder.php?id=success");
      // echo "New record created successfully";
       
    } 
    else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();


?>