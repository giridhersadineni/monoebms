<?php
/*
session_start();
if(!(isset($_SESSION['login'])))
{
header("location:index.php?sessionexpired");
}*/
?>
<?php include 'config.php';


$COURSE=$_POST["course"];
$PAPERCODE=$_POST["papercode"];
$SPECIALIZATION=$_POST["specialization"];
$PAPERTYPE=$_POST["papertype"];
$PAPERNAME=$_POST["papername"];
$GROUPCODE=$_POST["code"];
$SEM=$_POST["semester"];
$MEDIUM=$_POST["medium"];
$DESCRIPTION=$_POST["description"];
$EGROUP=$_POST["egroup"];
$YEAR=$_POST["year"];


$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if (!$conn) {
    die("connection failed:" . mysqli_connect_error());
}
$sql = 'UPDATE  allpapers SET PAPERNAME= "' . $PAPERNAME . '",COURSE= "' . $COURSE . '",SEM= "' . $SEM . '", PAPERCODE="' . $PAPERCODE . '", PAPERTYPE="' . $PAPERTYPE . '", PAPERNAME="' . $PAPERNAME . '", SPECIALIZATION="' . $SPECIALIZATION . '",GROUPCODE="' . $GROUPCODE . '",MEDIUM="' . $MEDIUM . '", DESCRIPTION="' . $DESCRIPTION . '",EGROUP="' . $EGROUP . '", YEAR="' . $YEAR . '"  WHERE ID="' . $_GET['id'].'"';

if ($conn->query($sql) === true) {
    
    //echo " Record Updated  Successfully";
      header('location:papers.php?update=ture');

} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
