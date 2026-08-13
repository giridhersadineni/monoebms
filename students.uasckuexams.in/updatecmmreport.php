<?php include 'config.php';

 $Ext=$_POST["ext"];
 $papername=$_POST['papername'];
 $PAPERCODE=$_POST['PAPERCODE'];
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
  $sql="UPDATE rholdernew SET EXT='$Ext',ETOTAL='$Etotal' ,`INT`='$Int',ITOTAL='$totalinternal',PAPERNAME='$papername',PAPERCODE='$papercode',RESULT='$result',CREDITS='$credits',MARKS='$marks',PERCENTAGE='$percentage', GRADE='$grade', GPV='$gpv', GPC='$gpc' WHERE  HALLTICKET=".$_GET['hallticket']."  AND RHID='".$_POST["RHID"]."'";
  $result=$conn->query($sql);
//echo $sql;
if ($conn->query($sql) === true) {

echo "<form method='post' action='cmmreport.php'>";
echo "<input type='hidden' name='ht' value='".$_GET['hallticket']."'>";
?>
<h2>Update Student Marks Successfully</h2>
<input type="submit" value="Go Back" class="btn btn-success">
</form>


<?php
 //  echo "success"; 
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();

?>