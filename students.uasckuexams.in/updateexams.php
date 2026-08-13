<?php
/*
session_start();
if(!(isset($_SESSION['login'])))
{
header("location:index.php?sessionexpired");
}*/
?>
<?php include 'config.php';

$SEMESTER = $_POST["semester"];
$EXAMTYPE = $_POST["examtype"];
$EXAMNAME = $_POST["examname"];
$MONTH = $_POST["month"];
$YEAR = $_POST["year"];
$STATUS=$_POST["status"];
$COURSE=$_POST["course"];
$FEE=$_POST["fee"];
$FINE=$_POST["fine"];
$ABOVE2SUBS=$_POST["secondfee"];
$IMPROVEMENT=$_POST["improvement"];
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if (!$conn) {
    die("connection failed:" . mysqli_connect_error());
}
$sql = 'UPDATE  examsmaster SET SEMESTER= "' . $SEMESTER . '", EXAMTYPE="' . $EXAMTYPE . '", EXAMNAME="' . $EXAMNAME . '", MONTH="' . $MONTH . '", YEAR="' . $YEAR . '",STATUS="'.$STATUS.'",COURSE="'.$COURSE.'",FEE="'.$FEE.'",ABOVE2SUBS="'.$ABOVE2SUBS.'",IMPROVEMENT="'.$IMPROVEMENT.'",FINE="'.$FINE.'" WHERE EXID=' . $_GET['id'];

if ($conn->query($sql) === true) {
    echo "<script> alert('Record Updated  Successfully')</script>";
    header("Location:examtable.php");
   
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>