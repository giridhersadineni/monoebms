<?php include 'config.php';

 $Ext=$_POST["ext"];
 $Etotal=$_POST["etotal"];
 $Int=$_POST["int"];
 $totalinternal=$_POST["itotal"];
 $result=$_POST["result"];
 $credits=$_POST["CREDITS"];
 $marks=$_POST["marks"];
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
 $sql="UPDATE rholdernew SET EXT='$Ext',ETOTAL='$Etotal' ,`INT`='$Int',ITOTAL='$totalinternal',RESULT='$result',CREDITS='$credits',MARKS='$marks',PERCENTAGE='$percentage', GRADE='$grade', GPV='$gpv', GPC='$gpc' WHERE   HALLTICKET=".$_GET['hallticket']." AND CODE='".$_GET['code']."' AND RHID='".$_POST["RHID"]."'";
 $result=$conn->query($sql);
 //echo $sql;
if ($conn->query($sql) === true) {
    header("Location:revresult.php?updated=true");
 //  echo "success"; 
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>