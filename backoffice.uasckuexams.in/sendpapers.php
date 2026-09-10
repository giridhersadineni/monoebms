<?php
include 'config.php';

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

if (!$conn){
    die("connection failed:".mysqli_connect_error());
}

$sql = "INSERT INTO `allpapers` ( `COURSE`, `PAPERCODE`, `SPECIALIZATION`, `PAPERTYPE`, `PAPERNAME`,`GROUPCODE`, `SEM`, `MEDIUM`, `EGROUP`, `DESCRIPTION`,`YEAR`) 
VALUES ('".$COURSE."','".$PAPERCODE."','".$SPECIALIZATION."','".$PAPERTYPE."','".$PAPERNAME."','".$GROUPCODE."','".$SEM."','".$MEDIUM."','".$EGROUP."','".$DESCRIPTION."','".$YEAR."')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} 
else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>